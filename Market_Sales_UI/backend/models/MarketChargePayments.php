<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "unza_market_charge_payments".
 *
 * @property int $id
 * @property string|null $uuid
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $other_name
 * @property string $msisdn
 * @property string $stand_number
 * @property string $amount
 * @property int $status
 * @property string $date_created
 * @property int|null $created_by
 * @property string|null $date_modified
 * @property int|null $modified_by
 */
class MarketChargePayments extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'unza_market_charge_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['first_name', 'msisdn', 'stand_number', 'amount'], 'required'],
            [['status', 'created_by', 'modified_by'], 'integer'],
            [['date_created', 'date_modified'], 'safe'],
            [['uuid'], 'string', 'max' => 200],
            [['first_name', 'last_name', 'other_name'], 'string', 'max' => 50],
            [['msisdn'], 'string', 'max' => 16],
            [['stand_number'], 'string', 'max' => 45],
            [['amount'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'uuid' => 'UUID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'other_name' => 'Other Name',
            'msisdn' => 'Mobile',
            'stand_number' => 'Stand number',
            'amount' => 'Amount',
            'status' => 'Status',
            'date_created' => 'Date Created',
            'created_by' => 'Created By',
            'date_modified' => 'Date Modified',
            'modified_by' => 'Modified By',
        ];
    }

    /**
     * 
     * @return type array
     */
    public static function getFullNames() {
        $query = static::find()
                ->select([ 'first_name'])
                //->where(["IN", 'status', [self::STATUS_ACTIVE]])
                ->orderBy(['first_name' => SORT_ASC])
                ->asArray()
                ->all();

        return \yii\helpers\ArrayHelper::map($query, 'first_name', 'first_name');
    }
    public static function getUUIDs() {
        $query = static::find()
                ->select([ 'uuid'])
                //->where(["IN", 'status', [self::STATUS_ACTIVE]])
                ->orderBy(['uuid' => SORT_ASC])
                ->asArray()
                ->all();

        return \yii\helpers\ArrayHelper::map($query, 'uuid', 'uuid');
    }
    public static function getMobiles() {
        $query = static::find()
                ->select([ 'msisdn'])
                //->where(["IN", 'status', [self::STATUS_ACTIVE]])
                ->orderBy(['msisdn' => SORT_ASC])
                ->asArray()
                ->all();

        return \yii\helpers\ArrayHelper::map($query, 'msisdn', 'msisdn');
    }
    public static function getStands() {
        $query = static::find()
                ->select([ 'stand_number'])
                //->where(["IN", 'status', [self::STATUS_ACTIVE]])
                ->orderBy(['stand_number' => SORT_ASC])
                ->asArray()
                ->all();

        return \yii\helpers\ArrayHelper::map($query, 'stand_number', 'stand_number');
    }

}
