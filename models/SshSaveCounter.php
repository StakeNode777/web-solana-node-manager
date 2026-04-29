<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class SshSaveCounter extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%ssh_save_counter}}';
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
            [['user_id'], 'required'],
            [['user_id', 'per_day', 'per_month', 'created_at', 'updated_at', 'per_month_started_at'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'per_day' => 'Per Day',
            'per_month' => 'Per Month',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'per_month_started_at' => 'Per Month Started At'
        ];
    }

    public static function checkAndIncrease(User $user)
    {
        $today = date('Y-m-d');
        $now = time();

        $MAX_PER_DAY = \Yii::$app->params['SSHSavePerDay'];
        $MAX_PER_MONTH = \Yii::$app->params['SSHSavePerMonth'];

        /** @var self $model */
        $model = self::findOne(['user_id' => $user->id]);

        // 1. If no record exists → create fresh
        if (!$model) {
            $model = new self();
            $model->user_id = $user->id;
            $model->per_day = 1;
            $model->per_month = 1;
            $model->per_month_started_at = $now;
            $model->created_at = $now;
            $model->updated_at = $now;
            return $model->save(false);
        }

        // 2. MONTH LOGIC: Check if month period expired (30 days)
        if ($model->per_month_started_at < ($now - 30 * 86400)) {
            // Reset month counters
            $model->per_month = 1;
            $model->per_month_started_at = $now;
        } else {
            // Still in same monthly period → enforce limit
            if ($model->per_month >= $MAX_PER_MONTH) {
                return false; // monthly limit reached
            }
        }

        // 3. DAY LOGIC: Reset per_day if updated_at is not today
        $lastUpdateDay = date('Y-m-d', $model->updated_at);
        if ($lastUpdateDay !== $today) {
            $model->per_day = 1;
            $model->per_month += 1;
            $model->updated_at = $now;
            return $model->save(false);
        }

        // 4. Same day: enforce per_day limit
        if ($model->per_day >= $MAX_PER_DAY) {
            return false; // daily limit reached
        }

        // 5. Increase counters
        $model->per_day += 1;
        $model->per_month += 1;
        $model->updated_at = $now;

        return $model->save(false);
    }

}
