<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaction_summaries".
 *
 * @property int $cart_id
 * @property int $transaction_type_id
 * @property string|null $external_trans_id
 * @property int $probase_status_code
 * @property string|null $probase_status_description
 * @property string|null $route_code
 * @property string|null $transaction_channel
 * @property string|null $id_type
 * @property string|null $passenger_id
 * @property string|null $bus_schedule_id
 * @property string|null $travel_date
 * @property string|null $travel_time
 * @property string|null $seller_id
 * @property string|null $seller_firstname
 * @property string|null $seller_lastname
 * @property string|null $seller_mobile_number
 * @property string|null $buyer_id
 * @property string|null $buyer_firstname
 * @property string|null $buyer_lastname
 * @property string|null $buyer_mobile_number
 * @property string|null $buyer_email
 * @property float $amount
 * @property float $transaction_fee
 * @property string|null $device_serial
 * @property string $transaction_date
 * @property string|null $debit_msg
 * @property string|null $debit_reference
 * @property string|null $debit_code
 * @property string|null $callback_msg
 * @property string|null $callback_reference
 * @property string|null $callback_code
 * @property string|null $callback_system_code
 * @property string|null $callback_transactionID
 * @property string|null $credit_msg
 * @property string|null $credit_reference
 * @property string|null $credit_code
 * @property string $credit_system_code
 * @property string $credit_transactionID
 * @property string|null $sms_seller
 * @property string|null $sms_buyer
 * @property string $date_created
 * @property string|null $date_modified
 */
class Transactions extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'unza_transaction_summaries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['transaction_type_id', 'transaction_date', 'credit_system_code', 'credit_transactionID'], 'required'],
            [['transaction_type_id', 'probase_status_code'], 'integer'],
            [['amount', 'transaction_fee'], 'number'],
            [['transaction_date', 'date_created', 'date_modified'], 'safe'],
            [['external_trans_id'], 'string', 'max' => 255],
            [['probase_status_description'], 'string', 'max' => 300],
            [['route_code', 'transaction_channel', 'id_type', 'passenger_id', 'travel_date', 'travel_time'], 'string', 'max' => 150],
            [['bus_schedule_id'], 'string', 'max' => 50],
            [['seller_id', 'buyer_id', 'buyer_email'], 'string', 'max' => 250],
            [['seller_firstname', 'seller_lastname', 'buyer_firstname', 'buyer_lastname','final_status','final_status_desc'], 'string', 'max' => 100],
            [['seller_mobile_number', 'buyer_mobile_number'], 'string', 'max' => 20],
            [['device_serial', 'debit_msg', 'debit_reference', 'callback_msg', 'callback_reference', 'callback_transactionID', 'credit_msg', 'credit_reference', 'credit_transactionID'], 'string', 'max' => 200],
            [['debit_code', 'callback_code', 'callback_system_code', 'credit_code', 'credit_system_code'], 'string', 'max' => 10],
            [['sms_seller', 'sms_buyer'], 'string', 'max' => 350],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'cart_id' => 'TransactionID',
            'transaction_type_id' => 'Transaction Type',
            'external_trans_id' => 'External Trans ID',
            'probase_status_code' => 'Probase Status Code',
            'probase_status_description' => 'Probase Status Description',
            'route_code' => 'Route Code',
            'transaction_channel' => 'Transaction Channel',
            'id_type' => 'Id Type',
            'passenger_id' => 'Passenger ID',
            'bus_schedule_id' => 'Bus Schedule ID',
            'travel_date' => 'Travel Date',
            'travel_time' => 'Travel Time',
            'seller_id' => 'Seller ID',
            'seller_firstname' => 'Seller Firstname',
            'seller_lastname' => 'Seller Lastname',
            'seller_mobile_number' => 'Seller Mobile',
            'buyer_id' => 'Buyer ID',
            'buyer_firstname' => 'Buyer Firstname',
            'buyer_lastname' => 'Buyer Lastname',
            'buyer_mobile_number' => 'Buyer Mobile',
            'buyer_email' => 'Buyer Email',
            'amount' => 'Amount',
            'transaction_fee' => 'Transaction Fee',
            'device_serial' => 'Device Serial',
            'transaction_date' => 'Transaction Date',
            'debit_msg' => 'Debit Msg',
            'debit_reference' => 'Debit Reference',
            'debit_code' => 'Debit Code',
            'callback_msg' => 'Callback Msg',
            'callback_reference' => 'Callback Reference',
            'callback_code' => 'Callback Code',
            'callback_system_code' => 'Callback System Code',
            'callback_transactionID' => 'Callback Transaction ID',
            'credit_msg' => 'Credit Msg',
            'credit_reference' => 'Credit Reference',
            'credit_code' => 'Credit Code',
            'credit_system_code' => 'Credit System Code',
            'credit_transactionID' => 'Credit Transaction ID',
           // 'sms_seller' => 'Sms Seller',
           // 'sms_buyer' => 'Sms Buyer',
            'final_status'=>'Status',
            'final_status_desc'=>'Status desc',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }

    public static function getTransactionIDs() {
        $users = static::find()->orderBy(['cart_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'cart_id', 'cart_id');
        return $list;
    }

    public static function getExternalTransactionIDs() {
        $users = static::find()->orderBy(['cart_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'external_trans_id', 'external_trans_id');
        return $list;
    }
    public static function getSellerMobiles() {
        $users = static::find()->select(['seller_mobile_number'])->orderBy(['cart_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'seller_mobile_number', 'seller_mobile_number');
        return $list;
    }
    public static function getBuyerMobiles() {
        $users = static::find()->select(['buyer_mobile_number'])->orderBy(['cart_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'buyer_mobile_number', 'buyer_mobile_number');
        return $list;
    }

}
