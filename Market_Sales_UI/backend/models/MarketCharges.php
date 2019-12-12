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
        return 'market_charges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'amount'], 'required'],
            ['status', 'integer'],
            ['name', 'unique', 'message' => 'Market Charge already exists!'],
            [['name', 'amount'], 'string', 'max' => 255],
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
            'amount' => 'Amount',
        ];
    }

}
