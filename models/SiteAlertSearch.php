<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SiteAlert;

class SiteAlertSearch extends SiteAlert
{
    public $date_from;
    public $date_to;

    public function rules()
    {
        return [
            [['id', 'type', 'user_id', 'status'], 'integer'],
            [['message', 'url', 'referrer', 'custom', 'comment', 'created', 'date_from', 'date_to'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SiteAlert::find()->with('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['>=', 'created', $this->date_from ? $this->date_from . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'created', $this->date_to ? $this->date_to . ' 23:59:59' : null]);

        return $dataProvider;
    }
}
