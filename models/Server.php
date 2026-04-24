<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "snm_server".
 *
 * @property int $id
 * @property int $validator_id
 * @property string $name
 * @property string $ip
 * @property string $status
 * @property string|null $status_msg
 * @property bool $is_active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Validator $validator
 */
class Server extends ActiveRecord
{
    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERROR';

    public static function tableName()
    {
        return '{{%server}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        $rules = [
            [['validator_id', 'name', 'ip'], 'required'],
            [['validator_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['status'], 'in', 'range' => [self::STATUS_OK, self::STATUS_ERROR]],
            [['status'], 'default', 'value' => self::STATUS_OK],
            [['status_msg'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 255],
            [['ip'], 'ip'],
            [['validator_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Validator::class,
                'targetAttribute' => ['validator_id' => 'id']
            ],
        ];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'validator_id' => 'Validator ID',
            'name' => 'Name',
            'ip' => 'IP Address',
            'status' => 'Status',
            'status_msg' => 'Status Message',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getValidator()
    {
        return $this->hasOne(Validator::class, ['id' => 'validator_id']);
    }

    public static function getList($validatorID = null)
    {
        $servers = is_null($validatorID)
            ? self::find()->all()
            : self::find()->where(['validator_id' => $validatorID])->all();
        
        return \yii\helpers\ArrayHelper::map($servers, 'name', 'name');
    }
}
