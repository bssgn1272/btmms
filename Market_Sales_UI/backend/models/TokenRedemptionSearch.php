<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TokenRedemption;

/**
 * TokenRedemptionSearch represents the model behind the search form of `backend\models\TokenRedemption`.
 */
class TokenRedemptionSearch extends TokenRedemption
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token_redemption_id', 'trader_id', 'agent_id', 'organisation_id', 'payment_method_id'], 'integer'],
            [['token_value_tendered', 'amount_redeemed'], 'number'],
            [['reference_number', 'recipient_msisdn', 'device_serial', 'transaction_date', 'date_created', 'date_modified'], 'safe'],
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
        $query = TokenRedemption::find();

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
            'token_redemption_id' => $this->token_redemption_id,
            'trader_id' => $this->trader_id,
            'token_value_tendered' => $this->token_value_tendered,
            'amount_redeemed' => $this->amount_redeemed,
            'agent_id' => $this->agent_id,
            'organisation_id' => $this->organisation_id,
            'payment_method_id' => $this->payment_method_id,
            'transaction_date' => $this->transaction_date,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'recipient_msisdn', $this->recipient_msisdn])
            ->andFilterWhere(['like', 'device_serial', $this->device_serial]);

        return $dataProvider;
    }
}
