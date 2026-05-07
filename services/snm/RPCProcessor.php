<?php

namespace app\services\snm;

use Ramsey\Uuid\Uuid;
use Yii;
use app\models\SnmActionLog;
use yii\helpers\ArrayHelper;

class RPCProcessor
{
    public function DoTransfer(SSHConfig $sshConfig, TransferDTO $dto, $writeToLog = true): array
    {   
        $syncService = new SyncService();
        $payload = $this->makePayloadTransfer($dto);
        $res = $syncService->sendCommandAndFetchResult(
            $sshConfig,
            $payload,
        );

        SnmActionLog::writeOperation($dto->identity, $payload, $res['data']);

        if (ArrayHelper::getValue($res, 'has_errors') === false) {
            $arrContent = json_decode(ArrayHelper::getValue($res, 'data'), true);
            if (ArrayHelper::getValue($arrContent, 'error')) {
                $res = [
                    'has_errors' => true, 
                    'data' => ArrayHelper::getValue($arrContent, 'msg', 'unknown error, see logs for delails'),
                ];
            }

            $res['log'] = ArrayHelper::getValue($arrContent, 'log', []);
        }

        return $res;
    }

    private function makePayloadTransfer(TransferDTO $dto): string
    {
        $payload = [
            "name" => "do-transfer",
            "params" => [
                "mode" => $this->getMode($dto->operation, $dto->safe),
                "pub_identity" => $dto->identity,
                //"from" => $dto->from,
                "to" => $dto->to,
            ],
        ];

        if ($dto->operation === 'transfer') {
            $payload['params']['from'] = $dto->from;
        }

        return json_encode($payload);
    }

    private function getMode($operation, bool $safe)
    {
        switch ($operation) {
            case "transfer":
                return $safe ? "safe" : "transfer_only";
            case "activate":
                return $safe ? "safe_activate" : "activate_only";
            default:
                throw new \Exception("Wrong operation");
        }
    }
}