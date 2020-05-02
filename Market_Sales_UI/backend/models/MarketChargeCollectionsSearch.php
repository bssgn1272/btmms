<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MarketChargeCollections;

/**
 * MarketChargeCollectionsSearch represents the model behind the search form of `backend\models\MarketChargeCollections`.
 */
class MarketChargeCollectionsSearch extends MarketChargeCollections {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['marketeer_msisdn', 'collection_msisdn', 'stand_number', 'transaction_details', 'transaction_date', 'created_by', 'date_modified', 'modified_by'], 'safe'],
            [['amount'], 'number'],
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
        $query = MarketChargeCollections::find();

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

        if (!empty($params['MarketChargeCollectionsSearch']['transaction_date'])) {
            $date_arry = explode("to", $params['MarketChargeCollectionsSearch']['transaction_date']);
            $start_date = $date_arry[0];
            $end_date = $date_arry[1];
            $query->andFilterWhere(["BETWEEN", 'Date(transaction_date)', $start_date, $end_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'marketeer_msisdn', $this->marketeer_msisdn])
                ->andFilterWhere(['like', 'collection_msisdn', $this->collection_msisdn])
                ->andFilterWhere(['like', 'stand_number', $this->stand_number])
                ->andFilterWhere(['like', 'transaction_details', $this->transaction_details])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'modified_by', $this->modified_by]);

        return $dataProvider;
    }

}
