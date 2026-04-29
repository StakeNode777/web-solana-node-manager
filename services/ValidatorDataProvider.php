<?php

namespace app\services;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\Validator;

class ValidatorDataProvider
{
    private $client;
    private $cluster = "mainnet";


    public function __construct($cluster)
    {
        // cluster
        Helper::validateClusterName($cluster);
        $this->cluster = $cluster;

        // client
        $this->setupClient();
    }

    private function setupClient()
    {
        $clusterPart = ($this->cluster == "mainnet")
            ? "mainnet-beta"
            : "testnet";

        $endpoint = "https://api.{$clusterPart}.solana.com";
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'getClusterNodes',
        ];

        $this->client = new SolanaRPCClient($endpoint, $payload);
    }

    public function fetchIPs()
    {
        if (is_null($this->client)) {
            echo "Client is null. Exit...\n";
            return;
        }

        $result = $this->client->sendRequestWithTries();

        return ArrayHelper::map($result, 'pubkey', 'gossip');
    }

    public function getNodeList(): array|null
    {
        return Validator::find()
            ->where(['cluster' => $this->cluster])
            ->all();
    }

    public function fetchValidatorsFromCluster(): array
    {
        // exec command
        $output = shell_exec($this->getCmd());

        if (empty($output)) {
            throw new \Exception("Exec returned empty response!");
        }

        // cast result to array
        $outputAssoc = json_decode($output, true);
        if (!is_array($outputAssoc)) {
            throw new \Exception("Exec returned non-JSON string!");
        }

        $validators =  ArrayHelper::getValue($outputAssoc, 'validators');
        if (is_null($validators)) {
            throw new \Exception("'validators' key is missed in exec response!");
        }
        if (empty($validators)) {
            throw new \Exception("Validator List must not be empty!");
        }
        
        return $validators;
    }

    private function getCmd(): string
    {
        $cluster_param = 'um';
        if ($this->cluster=='testnet') {
            $cluster_param = 'ut';
        }

        return "solana -{$cluster_param} validators --output json-compact";
    }
}