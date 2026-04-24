<?php

namespace app\services;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\models\Validator;

class Helper
{
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