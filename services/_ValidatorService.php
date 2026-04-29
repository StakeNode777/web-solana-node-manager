<?php

namespace app\services;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\Validator;

class _ValidatorService
{
    private $cmd = "";
    private $cluster = "mainnet";


    public function __construct($cluster)
    {
        // cluster
        self::validateClusterName($cluster);
        $this->cluster = $cluster;

        // cmd
        $this->setupCmd();
    }

    public function updateIPs()
    {
        $IPs = (new ValidatorIPService($this->cluster))->fetchIPs();
        $nodes = $this->getNodeList();

        foreach($nodes as $node) {
            $ip = ArrayHelper::getValue($IPs, $node->identity);
            if (!$ip) {
                continue;
            }
            $node->snm_server = $ip;
            $node->save();
        }
    }

    public function updateHealthes()
    {
        $validators = $this->fetchValidatorsFromCluster();
        $nodes = $this->getNodeList();

        $this->matchNodesWithValidators($nodes, $validators);
    }

    private function matchNodesWithValidators($nodes, $validators)
    {
        $valiadtorIDX = [];
        foreach ($validators as $validator) {
            $identity = ArrayHelper::getValue($validator, 'identityPubkey');
            if (is_null($identity)) {
                continue;
            }

            $valiadtorIDX[$identity] = $validator;
        }

        foreach ($nodes as $node) {
            $identity = $node->identity;
            $validator = ArrayHelper::getValue($valiadtorIDX, $identity);
            
            $node->health = ValidatorService::defineHealth($validator);
            if (!$node->save()) {
                echo "error while save Node!";
            }
        }
    }

    private function getNodeList(): array|null
    {
        return Validator::find()
            ->where(['cluster' => $this->cluster])
            ->all();
    }

    private function setupCmd()
    {
        $cluster_param = 'um';
        if ($this->cluster=='testnet') {
            $cluster_param = 'ut';
        }

        $this->cmd = "solana -{$cluster_param} validators --output json-compact";
    }

    private function fetchValidatorsFromCluster(): array
    {
        // exec command
        $output = shell_exec($this->cmd);

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

    /**
     * Helper functions
     * 
     */
    public static function validateClusterName($cluster)
    {
        $correctClusterNames = [
            'mainnet',
            'testnet',
        ];

        if (!in_array($cluster, $correctClusterNames)) {
            throw new \Exception("Wrong cluster name!");
        }
    }

    public static function defineHealth($validator): string
    {
        if (is_null($validator)) {
            return "UNDEFINED";
        }

        $delinquent = ArrayHelper::getValue($validator, "delinquent", false);
        if ($delinquent) {
            return "DELINQUENT";
        }

        return "OK";
    }

    public static function healthColor($health) {
        switch ($health) {
            case 'OK':
                $color = 'green';
                break;
            case 'DELINQUENT':
                $color = 'red';
                break;
            default:
                $color = 'orange';
        }
        return $color;
    }

    public static function yesNoHelper(bool $boolValue) {
        return $boolValue === true ? 'Yes' : 'No';
    }
}