<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TokenProcurement;

/**
 * TokenProcurementSearch represents the model behind the search form of `backend\models\TokenProcurement`.
 */
class TokenProcurementSearch extends TokenProcurement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token_procurement_id', 'trader_id', 'agent_id', 'organisation_id', 'payment_method_id'], 'integer'],
            [['amount_tendered', 'token_value'], 'number'],
            [['reference_number', 'procuring_msisdn', 'device_serial', 'transaction_date', 'date_created', 'date_modified'], 'safe'],
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
        $query = TokenProcurement::find();

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
            'token_procurement_id' => $this->token_procurement_id,
            'trader_id' => $this->trader_id,
            'amount_tendered' => $this->amount_tendered,
            'token_value' => $this->token_value,
            'agent_id' => $this->agent_id,
            'organisation_id' => $this->organisation_id,
            'payment_method_id' => $this->payment_method_id,
            'transaction_date' => $this->transaction_date,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'procuring_msisdn', $this->procuring_msisdn])
            ->andFilterWhere(['like', 'device_serial', $this->device_serial]);

        return $dataProvider;
    }
}
