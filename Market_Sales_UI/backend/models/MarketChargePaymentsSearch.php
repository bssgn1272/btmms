<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MarketChargePayments;

/**
 * MarketChargePaymentsSearch represents the model behind the search form of `backend\models\MarketChargePayments`.
 */
class MarketChargePaymentsSearch extends MarketChargePayments {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['uuid', 'first_name', 'last_name', 'other_name', 'msisdn', 'stand_number', 'amount', 'date_created', 'date_modified'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = MarketChargePayments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($params['MarketChargePaymentsSearch']['date_created'])) {
            $date_arry = explode("to", $params['MarketChargePaymentsSearch']['date_created']);
            $start_date = $date_arry[0];
            $end_date = $date_arry[1];
            $query->andFilterWhere(["BETWEEN", 'Date(date_created)', $start_date, $end_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
           // 'date_created' => $this->date_created,
            'created_by' => $this->created_by,
            'date_modified' => $this->date_modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
                ->andFilterWhere(['like', 'first_name', $this->first_name])
                ->andFilterWhere(['like', 'last_name', $this->last_name])
                ->andFilterWhere(['like', 'other_name', $this->other_name])
                ->andFilterWhere(['like', 'msisdn', $this->msisdn])
                ->andFilterWhere(['like', 'stand_number', $this->stand_number])
                ->andFilterWhere(['like', 'amount', $this->amount]);

        return $dataProvider;
    }

}
