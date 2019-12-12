<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Sales;

/**
 * SalesSearch represents the model behind the search form of `backend\models\Sales`.
 */
class SalesSearch extends Sales
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cart_id', 'marketeer_id', 'buyer_id', 'status', 'points_marketeer_earned', 'points_buyer_earned'], 'integer'],
            [['external_trans_id', 'status_description', 'device_serial', 'transaction_date', 'date_created', 'date_modified'], 'safe'],
            [['amount', 'token_tendered'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Sales::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'cart_id' => $this->cart_id,
            'marketeer_id' => $this->marketeer_id,
            'buyer_id' => $this->buyer_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'token_tendered' => $this->token_tendered,
            'points_marketeer_earned' => $this->points_marketeer_earned,
            'points_buyer_earned' => $this->points_buyer_earned,
            'transaction_date' => $this->transaction_date,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'external_trans_id', $this->external_trans_id])
            ->andFilterWhere(['like', 'status_description', $this->status_description])
            ->andFilterWhere(['like', 'device_serial', $this->device_serial]);

        return $dataProvider;
    }
}
