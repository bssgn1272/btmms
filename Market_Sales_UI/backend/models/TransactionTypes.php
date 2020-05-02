<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaction_types".
 *
 * @property int $transaction_type_id
 * @property string $name
 * @property string $description
 * @property string $date_created
 * @property string|null $date_modified
 */
class TransactionTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unza_transaction_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_type_id', 'name', 'description'], 'required'],
            [['transaction_type_id'], 'integer'],
            [['description'], 'string'],
            [['date_created', 'date_modified'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['transaction_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transaction_type_id' => 'Transaction Type ID',
            'name' => 'Name',
            'description' => 'Description',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }
    
       public static function getTransactionTypes() {
        $types = static::find()->orderBy(['transaction_type_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($types, 'transaction_type_id', 'name');
        return $list;
    }
}
