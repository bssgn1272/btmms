<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "marketeer_products".
 *
 * @property int $marketeer_products_id
 * @property int $trader_id
 * @property int $product_id
 * @property int $unit_of_measure_id
 * @property double $price
 * @property string $date_created
 * @property string $date_modified
 */
class MarketeerProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'marketeer_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trader_id', 'product_id'], 'required'],
            [['trader_id', 'product_id', 'unit_of_measure_id'], 'integer'],
            [['price'], 'number'],
            [['date_created', 'date_modified'], 'safe'],
            [['trader_id', 'product_id', 'unit_of_measure_id', 'price'], 'unique', 'targetAttribute' => ['trader_id', 'product_id', 'unit_of_measure_id', 'price']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'marketeer_products_id' => 'Marketeer Products ID',
            'trader_id' => 'Trader',
            'product_id' => 'Product',
            'unit_of_measure_id' => 'Unit',
            'price' => 'Price',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }
}
