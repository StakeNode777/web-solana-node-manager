<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "snm_action_log".
 *
 * @property int $id
 * @property int $user_id
 * @property int $validator_id
 * @property string $action
 * @property string $params
 * @property string $result
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Validator $validator
 */
class SnmActionLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%snm_action_log}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'validator_id', 'action', 'params', 'result'], 'required'],
            [['user_id', 'validator_id'], 'integer'],
            [['params', 'result'], 'string'],
            [['action'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
            [['validator_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Validator::class,
                'targetAttribute' => ['validator_id' => 'id']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'validator_id' => 'Validator ID',
            'action' => 'Action',
            'params' => 'Params',
            'result' => 'Result',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getValidator()
    {
        return $this->hasOne(Validator::class, ['id' => 'validator_id']);
    }

    public static function find()
    {
        return new SnmActionLogQuery(get_called_class());
    }

    public static function writeOperation($identity, $payload, $response)
    {
        $validator = Validator::find()->where(['identity' => $identity, 'user_id' => Yii::$app->user->id])->one();
        if (!$validator) {
            return;
        }

        $log = new self();

        $log->validator_id = $validator->id;
        $log->user_id = Yii::$app->user->id; 
        $log->action = ArrayHelper::getValue(json_decode($payload, true), 'name', 'ERROR');
        $log->params = $payload;
        $log->result = json_encode($response);
        
        if (!$log->save()) {
            var_dump($log->getErrors());
        }
    }
}
