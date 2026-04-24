<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\base\ErrorException;
use yii\helpers\BaseStringHelper;

class Validator extends ActiveRecord
{
    public static function tableName()
    {
        return 'validator';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) { // Only set for new records
                $this->user_id = Yii::$app->user->id; // Set user_id to current user's ID
            }
            return true;
        }
        return false;
    }

    public function rules()
    {
        return [
            [['cluster', 'health', 'identity', 'vote_account'], 'required'],
            ['cluster', 'in', 'range' => ['Testnet', 'Mainnet']],
            ['health', 'in', 'range' => ['OK', 'DELINQUENT', 'UNDEFINED']],
            ['snm_server', 'ip'],
            ['name', 'string', 'max' => 255],
            ['img_url', 'url'],
            ['identity', 'string', 'length' => [32, 44]],
            ['vote_account', 'string', 'length' => [32, 44]],
            ['configured', 'boolean'],
            ['snm_ssh_login', 'string', 'max' => 255],
            ['snm_ssh_password', 'safe'],
            //[['user_id', /* other fields */], 'required'],
            ['user_id', 'integer'],
        ];
    }

    public function setSshPassword($password)
    {
        //$this->snm_ssh_password = Yii::$app->security->generatePasswordHash($password);
        $this->snm_ssh_password = base64_encode($password);
    }

    public function getSshPassword()
    {
         return base64_decode($this->snm_ssh_password);
    }

    public static function find()
    {
        return new ValidatorQuery(get_called_class());
    }
}