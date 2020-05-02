<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "market_charges".
 *
 * @property int $id
 * @property string $name
 * @property string $amount
 */
class MarketCharges extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'unza_transaction_charges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'value'], 'required'],
            ['status', 'integer'],
            ['name', 'unique', 'message' => 'Market Charge already exists!'],
            [['name', 'value', 'charge_type', 'charge_frequency'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'value' => 'Value',
            'charge_type' => 'Charge type',
            //'charge_frequency' => 'Charge Frequency',
        ];
    }

}
