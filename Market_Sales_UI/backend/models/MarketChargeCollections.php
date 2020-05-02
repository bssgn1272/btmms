<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "market_charge_collections".
 *
 * @property int $id
 * @property string $marketeer_msisdn
 * @property string $collection_msisdn
 * @property float $amount
 * @property string $transaction_type
 * @property int $status
 * @property string $transaction_details
 * @property string $transaction_date
 * @property string|null $created_by
 * @property string|null $date_modified
 * @property string|null $modified_by
 */
class MarketChargeCollections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unza_market_charge_collections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marketeer_msisdn', 'collection_msisdn', 'amount', 'stand_number', 'transaction_details'], 'required'],
            [['amount'], 'number'],
            [['transaction_details'], 'string'],
            [['transaction_date', 'date_modified'], 'safe'],
            [['marketeer_msisdn', 'collection_msisdn'], 'string', 'max' => 15],
            [['stand_number', 'created_by', 'modified_by'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marketeer_msisdn' => 'Marketeer msisdn',
            'collection_msisdn' => 'Collection msisdn',
            'amount' => 'Amount',
            'stand_number' => 'Stand number',
            //'status' => 'Status',
            'transaction_details' => 'Description',
            'transaction_date' => 'Transaction Date',
            'created_by' => 'Created By',
            'date_modified' => 'Date Modified',
            'modified_by' => 'Modified By',
        ];
    }
    
     public static function getCollectionMsisdns() {
        $users = static::find()->select(['collection_msisdn'])->orderBy(['id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'collection_msisdn', 'collection_msisdn');
        return $list;
    }
     public static function getStandNumbers() {
        $users = static::find()->select(['stand_number'])->orderBy(['stand_number' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'stand_number', 'stand_number');
        return $list;
    }
    public static function getMarketeerMsisdns() {
        $users = static::find()->select(['marketeer_msisdn'])->orderBy(['id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'marketeer_msisdn', 'marketeer_msisdn');
        return $list;
    }
}
