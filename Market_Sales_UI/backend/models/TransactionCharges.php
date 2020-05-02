<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaction_charges".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property int $status
 * @property string $charge_type
 * @property string $charge_frequency
 */
class TransactionCharges extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unza_transaction_charges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value', 'status', 'charge_type','created_by'], 'required'],
            [['status','created_by','modified_by'], 'integer'],
            [['date_created','date_modified'], 'safe'],
            [['name', 'value'], 'string', 'max' => 255],
            [['charge_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
            'status' => 'Status',
            'charge_type' => 'Charge Type',
            'created_by' => 'Added by',
            'date_created' => 'Date added',
            'modified_by' => 'Updated by',
            'date_modified' => 'Date updated',
          
        ];
    }
}
