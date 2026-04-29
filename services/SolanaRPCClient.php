<?php

namespace app\services;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\Validator;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class SolanaRPCClient
{
    private $endpoint = "";
    private $payload = [];

    public function __construct($endpoint, $payload)
    {
        $this->endpoint = $endpoint;
        $this->payload = $payload;
    }

    public function sendRequestWithTries()
    {
        $client = new Client([
            'baseUrl' => $this->endpoint,
            'requestConfig' => [
                'format' => Client::FORMAT_JSON,
                'options' => [
                    CURLOPT_TIMEOUT => 10, // Default cURL timeout for data receiving
                    CURLOPT_CONNECTTIMEOUT => 10, // Default cURL connection timeout
                ],
            ],
        ]);

        $maxTries = 3;
        $lastError = null;

        for ($i = 0; $i < $maxTries; $i++) {
            try {
                $response = $client->post('', $this->payload, ['Content-Type' => 'application/json'])
                    ->send();

                if ($response->isOk) {
                    $data = $response->getData();
                    if (isset($data['result']) && is_array($data['result'])) {
                        return $data['result'];
                    }
                    throw new \Exception("Invalid response format: " . json_encode($data));
                }

                $lastError = "HTTP error: " . $response->statusCode;
            } catch (Exception $e) {
                $lastError = $e->getMessage();
            }

            sleep(1); // wait before retry
        }

        throw new \Exception("Failed to fetch cluster nodes after {$maxTries} attempts. Last error: {$lastError}");
    }
}