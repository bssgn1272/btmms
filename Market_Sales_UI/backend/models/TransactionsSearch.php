<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Transactions;

/**
 * TransactionsSearch represents the model behind the search form of `backend\models\Transactions`.
 */
class TransactionsSearch extends Transactions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cart_id', 'transaction_type_id', 'probase_status_code'], 'integer'],
            [['external_trans_id', 'probase_status_description', 'route_code', 'transaction_channel', 'id_type', 'passenger_id', 'bus_schedule_id', 'travel_date', 'travel_time', 'seller_id', 'seller_firstname', 'seller_lastname', 'seller_mobile_number', 'buyer_id', 'buyer_firstname', 'buyer_lastname', 'buyer_mobile_number', 'buyer_email', 'device_serial', 'transaction_date', 'debit_msg', 'debit_reference', 'debit_code', 'callback_msg', 'callback_reference', 'callback_code', 'callback_system_code', 'callback_transactionID', 'credit_msg', 'credit_reference', 'credit_code', 'credit_system_code', 'credit_transactionID', 'sms_seller', 'sms_buyer', 'date_created', 'date_modified'], 'safe'],
            [['amount', 'transaction_fee'], 'number'],
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
        $query = Transactions::find();

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

         if (!empty($params['TransactionsSearch']['transaction_date'])) {
            $date_arry = explode("to", $params['TransactionsSearch']['transaction_date']);
            $start_date = $date_arry[0];
            $end_date = $date_arry[1];
            $query->andFilterWhere(["BETWEEN", 'Date(transaction_date)', $start_date, $end_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'cart_id' => $this->cart_id,
            'transaction_type_id' => $this->transaction_type_id,
            'probase_status_code' => $this->probase_status_code,
            'amount' => $this->amount,
            'transaction_fee' => $this->transaction_fee,
            //'transaction_date' => $this->transaction_date,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'external_trans_id', $this->external_trans_id])
            ->andFilterWhere(['like', 'probase_status_description', $this->probase_status_description])
            ->andFilterWhere(['like', 'route_code', $this->route_code])
            ->andFilterWhere(['like', 'transaction_channel', $this->transaction_channel])
            ->andFilterWhere(['like', 'id_type', $this->id_type])
            ->andFilterWhere(['like', 'passenger_id', $this->passenger_id])
            ->andFilterWhere(['like', 'bus_schedule_id', $this->bus_schedule_id])
            ->andFilterWhere(['like', 'travel_date', $this->travel_date])
            ->andFilterWhere(['like', 'travel_time', $this->travel_time])
            ->andFilterWhere(['like', 'seller_id', $this->seller_id])
            ->andFilterWhere(['like', 'seller_firstname', $this->seller_firstname])
            ->andFilterWhere(['like', 'seller_lastname', $this->seller_lastname])
            ->andFilterWhere(['like', 'seller_mobile_number', $this->seller_mobile_number])
            ->andFilterWhere(['like', 'buyer_id', $this->buyer_id])
            ->andFilterWhere(['like', 'buyer_firstname', $this->buyer_firstname])
            ->andFilterWhere(['like', 'buyer_lastname', $this->buyer_lastname])
            ->andFilterWhere(['like', 'buyer_mobile_number', $this->buyer_mobile_number])
            ->andFilterWhere(['like', 'buyer_email', $this->buyer_email])
            ->andFilterWhere(['like', 'device_serial', $this->device_serial])
            ->andFilterWhere(['like', 'debit_msg', $this->debit_msg])
            ->andFilterWhere(['like', 'debit_reference', $this->debit_reference])
            ->andFilterWhere(['like', 'debit_code', $this->debit_code])
            ->andFilterWhere(['like', 'callback_msg', $this->callback_msg])
            ->andFilterWhere(['like', 'callback_reference', $this->callback_reference])
            ->andFilterWhere(['like', 'callback_code', $this->callback_code])
            ->andFilterWhere(['like', 'callback_system_code', $this->callback_system_code])
            ->andFilterWhere(['like', 'callback_transactionID', $this->callback_transactionID])
            ->andFilterWhere(['like', 'credit_msg', $this->credit_msg])
            ->andFilterWhere(['like', 'credit_reference', $this->credit_reference])
            ->andFilterWhere(['like', 'credit_code', $this->credit_code])
            ->andFilterWhere(['like', 'credit_system_code', $this->credit_system_code])
            ->andFilterWhere(['like', 'credit_transactionID', $this->credit_transactionID])
            ->andFilterWhere(['like', 'sms_seller', $this->sms_seller])
            ->andFilterWhere(['like', 'sms_buyer', $this->sms_buyer]);

        return $dataProvider;
    }
}
