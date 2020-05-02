<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaction_summaries".
 *
 * @property int $cart_id
 * @property string $external_trans_id
 * @property int $marketeer_id
 * @property int $buyer_id
 * @property double $amount
 * @property int $status
 * @property string $status_description
 * @property double $token_tendered
 * @property string $device_serial
 * @property int $points_marketeer_earned
 * @property int $points_buyer_earned
 * @property string $transaction_date
 * @property string $date_created
 * @property string $date_modified
 */
class Sales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unza_transaction_summaries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marketeer_id', 'buyer_id', 'amount', 'status', 'status_description','buyer_mobile_number', 'token_tendered', 'device_serial', 'transaction_date'], 'required'],
            [['marketeer_id', 'buyer_id', 'status', 'points_marketeer_earned', 'points_buyer_earned'], 'integer'],
            [['amount', 'token_tendered'], 'number'],
            [['transaction_date', 'date_created', 'date_modified'], 'safe'],
            [['external_trans_id', 'status_description'], 'string', 'max' => 255],
            [['device_serial'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cart_id' => 'Cart ID',
            'external_trans_id' => 'External Trans ID',
            'marketeer_id' => 'Marketeer ID',
            'buyer_id' => 'Buyer ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'buyer_mobile_number'=>"Payer number",
            'status_description' => 'Status Description',
            'token_tendered' => 'Token Tendered',
            'device_serial' => 'Device Serial',
            'points_marketeer_earned' => 'Points Marketeer Earned',
            'points_buyer_earned' => 'Points Buyer Earned',
            'transaction_date' => 'Transaction Date',
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
}
