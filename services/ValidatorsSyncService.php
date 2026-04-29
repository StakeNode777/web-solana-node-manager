<?php

namespace app\services;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\Validator;

class ValidatorsSyncService
{
    private ValidatorDataProvider $provider;
    private $cluster = "mainnet";

    public function __construct($cluster)
    {
        // cluster
        Helper::validateClusterName($cluster);
        $this->cluster = $cluster;

        // provider
        $this->provider = new ValidatorDataProvider($this->cluster);
    }

    public function updateIPs()
    {
        $provider = $this->provider;

        $IPs = $provider->fetchIPs();
        $nodes = $provider->getNodeList();

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
        $provider = $this->provider;

        $validators = $provider->fetchValidatorsFromCluster();
        $nodes = $provider->getNodeList();

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
            
            $node->health = Helper::defineHealth($validator);
            if (!$node->save()) {
                echo "error while save Node!";
            }
        }
    }
}