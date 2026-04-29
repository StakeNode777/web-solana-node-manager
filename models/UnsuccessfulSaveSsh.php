<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class UnsuccessfulSaveSsh extends ActiveRecord
{
    public static function tableName()
    {
        return 'unsucessful_save_ssh';
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert && empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }

    /**
     * Registers unsuccessful try
     */
    public static function registerUnsuccessfulTry(User $user): bool
    {
        // Create new record
        $model = new self();
        $model->user_id = $user->id;

        return $model->save();
    }

    public static function atLeastOneRecordExists(User $user): bool
    {
        // Does at least one record exist?
        return self::find()->where(['user_id' => $user->id])->exists();
    }

    public static function isMonthlyLimitExceeded(User $user, $maxPerMonth = null): bool
    {
        $MAX_PER_MONTH = $maxPerMonth ?? \Yii::$app->params['SSHSavePerMonth'];
        
        $monthStart  = date('Y-m-01 00:00:00');
        $monthEnd    = date('Y-m-t 23:59:59');

        // Count per month
        $countMonth = self::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['between', 'created_at', $monthStart, $monthEnd])
            ->count();

        // Check limits
        if ($countMonth >= $MAX_PER_MONTH) {
            return true;
        }

        return false;
    }

    public static function isDailyLimitExceeded(User $user, $maxPerDay = null): bool
    {
        $MAX_PER_DAY = $maxPerDay ?? \Yii::$app->params['SSHSavePerDay'];

        // Prepare date ranges
        $todayStart  = date('Y-m-d 00:00:00');
        $todayEnd    = date('Y-m-d 23:59:59');

        // Count per day
        $countToday = self::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['between', 'created_at', $todayStart, $todayEnd])
            ->count();

        // Check limits
        if ($countToday >= $MAX_PER_DAY) {
            return true;
        }

        return false;
    }

    public static function areLimitsExceeded(User $user): bool
    {
        if (!self::atLeastOneRecordExists($user)) {
            return false;
        }

        return self::isMonthlyLimitExceeded($user) || self::isDailyLimitExceeded($user);
    }
}
