<?php

namespace app\services\snm;

use Ramsey\Uuid\Uuid;
use Yii;
use app\models\Validator;
use app\models\Server;
use yii\helpers\ArrayHelper;

class SyncService
{
    public function updateById($validatorId)
    {
        $validator = Validator::findOne($validatorId);
        if (!$validatorId) return;

        $ssh = new SSHConfig(
                $validator->snm_server,
                $validator->snm_ssh_login,
                $validator->getSshPassword(),
            );
        
        try {
            $this->sendAndSaveResult_GetInfo($ssh, $validator); 
        } catch (\Exception $e) {
            \Yii::error('[SyncService::updateById]' . $e->getMessage());
        }
    }

    public function updateAll()
    {
        $validators = Validator::find()
            ->all();
        
        foreach ($validators as $validator) {
            $this->updateById($validator->id);
        }
    }

    private function sendAndSaveResult_GetInfo(SSHConfig $ssh, Validator $validator)
    {
        //echo "[SyncService::sendAndSaveResult_GetInfo] ..." . "\n";

        $res = $this->GetInfo($ssh);
        $hasErrors = ArrayHelper::getValue($res, 'has_errors');
        if ($hasErrors) {
            echo "[SyncService::sendAndSaveResult_GetInfo] has errors: exit";
            return;
        }

        $data = json_decode(ArrayHelper::getValue($res, 'data'), true);
        if (!is_array($data)) {
            echo "[SyncService::sendAndSaveResult_GetInfo] data MUST be an array: exit";
            return;
        }

        $servers = ArrayHelper::getValue($data, 'servers');
        if (!is_array($servers)) {
            echo "[SyncService::sendAndSaveResult_GetInfo] servers MUST be an array: exit";
            return;
        }

        // first, clear up old servers
        Server::deleteAll(['validator_id' => $validator->id]);

        foreach ($servers as $server) {
            $model = new Server();
            $model->setAttributes($server);
            $model->validator_id = $validator->id;
            
            if (!$model->save()) {
                //echo "[SyncService::sendAndSaveResult_GetInfo] error while saving server: " . json_encode($server) . "\n";
            }
        }
    }

    public function GetInfo(SSHConfig $sshConfig): array
    {
        $payload = json_encode([
            "name" => "get-info",
            "params" => [],
        ]);
        
        return $this->sendCommandAndFetchResult(
            $sshConfig,
            $payload
        );
    }

     /**
     * Processes the JSON string according to the specified workflow.
     *
     * @param string $json The input JSON string.
     * @return bool|string True on success, error message on failure.
     */
    public function sendCommandAndFetchResult(SSHConfig $ssh, string $payload): array
    {
        $config = Yii::$app->params['snm'] ?? [];

        // Validate required config keys (simplified, add more checks as needed)
        $requiredKeys = ['file_prefix', 'tmp_dir', 'max_retries', 'base_sleep', 'wait_timeout', 'wait_interval'];
        foreach ($requiredKeys as $key) {
            if (!isset($config[$key])) {
                return [
                    'has_errors' => true, 
                    'data' => "Missing config key: $key",
                ];
            }
        }

        // Generate filename
        $uuid = Uuid::uuid4()->toString();
        $filename = $config['file_prefix'] . $uuid . '.json';

        // Write to tmp file
        $tmpFile = rtrim($config['tmp_dir'], '/') . '/' . $filename;
        if (file_put_contents($tmpFile, $payload) === false) {
            return [
                'has_errors' => true, 
                'data' => 'Failed to write temporary file',
            ];
        }

        // Send file with retries
        $sender = new RequestFileSender($ssh);
        $sent = false;
        $maxAttempts = 1 + (int)$config['max_retries'];
        $lastException = "";
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $remoteFile = rtrim($ssh->remotePath, '/') . '/' . $filename;
                $sender->sendFile($tmpFile, $remoteFile);
                $sent = true;
            } catch (\Exception $e) {
                $lastException = $e->getMessage();
                if ($attempt < $maxAttempts) {
                    sleep((int)$config['base_sleep'] * pow(2, $attempt - 1));
                }
            }
            if ($sent) {
                break;
            }
        }

        // Clean up tmp file regardless, but return error if not sent
        @unlink($tmpFile);

        if (!$sent) {
            return [
                'has_errors' => true, 
                'data' => 'Failed to send file after ' . $config['max_retries'] . ' retries. ' . $lastException,
            ];
        }

        // Wait loop for file availability on receive server
        $receiver = new RespFileReceiver($ssh);
        $startTime = time();
        $available = false;
        $content = "";
        $lastException = "";
        while (!$available && (time() - $startTime) < (int)$config['wait_timeout']) {
            try {
                $remoteFile = rtrim($ssh->remotePath, '/') . '/res/' . $filename;
                if ($content = $receiver->get($remoteFile)) {
                    $available = true;
                }
            } catch (\Exception $e) {
                // Ignore and retry
                $lastException = $e->getMessage();
            }
            if (!$available) {
                sleep((int)$config['wait_interval']);
            }
        }

        if (!$available) {
            return [
                'has_errors' => true, 
                'data' => 'Timeout waiting for file availability on receive server: ' . $lastException,
            ];
        }

        return [
            'has_errors' => false, 
            'data' => $content,
        ];
    }
}