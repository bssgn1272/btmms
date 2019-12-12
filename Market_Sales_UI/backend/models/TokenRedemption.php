<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "token_redemption".
 *
 * @property int $token_redemption_id
 * @property int $trader_id
 * @property double $token_value_tendered
 * @property double $amount_redeemed
 * @property string $reference_number
 * @property int $agent_id
 * @property int $organisation_id
 * @property int $payment_method_id
 * @property string $recipient_msisdn
 * @property string $device_serial
 * @property string $transaction_date
 * @property string $date_created
 * @property string $date_modified
 */
class TokenRedemption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_redemption';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trader_id', 'token_value_tendered', 'amount_redeemed', 'payment_method_id', 'recipient_msisdn', 'device_serial'], 'required'],
            [['trader_id', 'agent_id', 'organisation_id', 'payment_method_id'], 'integer'],
            [['token_value_tendered', 'amount_redeemed'], 'number'],
            [['transaction_date', 'date_created', 'date_modified'], 'safe'],
            [['reference_number', 'device_serial'], 'string', 'max' => 200],
            [['recipient_msisdn'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token_redemption_id' => 'Token Redemption ID',
            'trader_id' => 'Trader ID',
            'token_value_tendered' => 'Token Value Tendered',
            'amount_redeemed' => 'Amount Redeemed',
            'reference_number' => 'Reference Number',
            'agent_id' => 'Agent ID',
            'organisation_id' => 'Organisation ID',
            'payment_method_id' => 'Payment Method ID',
            'recipient_msisdn' => 'Recipient Msisdn',
            'device_serial' => 'Device Serial',
            'transaction_date' => 'Transaction Date',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }
}
