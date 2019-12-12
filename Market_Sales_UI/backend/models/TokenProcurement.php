<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "token_procurement".
 *
 * @property int $token_procurement_id
 * @property int $trader_id
 * @property double $amount_tendered
 * @property double $token_value
 * @property string $reference_number
 * @property int $agent_id
 * @property int $organisation_id
 * @property int $payment_method_id
 * @property string $procuring_msisdn
 * @property string $device_serial
 * @property string $transaction_date
 * @property string $date_created
 * @property string $date_modified
 */
class TokenProcurement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token_procurement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trader_id', 'amount_tendered', 'token_value', 'payment_method_id', 'procuring_msisdn', 'device_serial'], 'required'],
            [['trader_id', 'agent_id', 'organisation_id', 'payment_method_id'], 'integer'],
            [['amount_tendered', 'token_value'], 'number'],
            [['transaction_date', 'date_created', 'date_modified'], 'safe'],
            [['reference_number', 'device_serial'], 'string', 'max' => 200],
            [['procuring_msisdn'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token_procurement_id' => 'Token Procurement ID',
            'trader_id' => 'Trader ID',
            'amount_tendered' => 'Amount Tendered',
            'token_value' => 'Token Value',
            'reference_number' => 'Reference Number',
            'agent_id' => 'Agent ID',
            'organisation_id' => 'Organisation ID',
            'payment_method_id' => 'Payment Method ID',
            'procuring_msisdn' => 'Procuring Msisdn',
            'device_serial' => 'Device Serial',
            'transaction_date' => 'Transaction Date',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }
}
