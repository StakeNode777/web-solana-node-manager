<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class SnmActionLogQuery extends ActiveQuery
{
    public function own()
    {
        if (!Yii::$app->user->isGuest) {
            $this->andWhere(['user_id' => Yii::$app->user->id]);
        } else {
            // Prevent guests from seeing any records
            $this->andWhere('0=1'); // Returns no results
        }
        return $this;
    }
}