<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Validator;

/**
 * ValidatorSearch represents the model behind the search form of `app\models\Validator`.
 */
class ValidatorSearch extends Validator
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'configured'], 'integer'],
            [['name', 'cluster', 'snm_server', 'health', 'details', 'identity', 'vote_account'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Validator::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cluster' => $this->cluster,
            'health' => $this->health,
            'configured' => $this->configured,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'snm_server', $this->snm_server])
            ->andFilterWhere(['like', 'details', $this->details])
            ->andFilterWhere(['like', 'identity', $this->identity])
            ->andFilterWhere(['like', 'vote_account', $this->vote_account]);

        return $dataProvider;
    }
}