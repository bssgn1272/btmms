<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "market_charge_collection_accounts".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $account
 * @property string $type
 * @property int $status
 * @property string $percentage
 * @property string $date_created
 * @property int $created_by
 * @property string|null $date_modified
 * @property int|null $modified_by
 */
class CollectionAccounts extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'unza_market_charge_collection_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['code', 'name', 'account', 'type', 'percentage', 'created_by'], 'required'],
            [['code', 'name'], 'string'],
            [['status', 'created_by', 'modified_by'], 'integer'],
            [['date_created', 'date_modified'], 'safe'],
            [['account'], 'string', 'max' => 15],
            ['percentage', 'checkPercentage'],
            ['name', 'unique', 'message' => 'Account name already exists!'],
            ['code', 'unique', 'message' => 'Account code already exists!'],
            ['account', 'unique', 'message' => 'An account with this account number already exist!'],
            [['type'], 'string', 'max' => 100],
            [['percentage'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'account' => 'Account',
            'type' => 'Type',
            'status' => 'Status',
            'percentage' => 'Percentage',
            'date_created' => 'Date Created',
            'created_by' => 'Created By',
            'date_modified' => 'Date Modified',
            'modified_by' => 'Modified By',
        ];
    }

    public static function getCollectionAccounts() {
        $users = static::find()->select(['account'])->where(["status" => 1])->orderBy(['id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'account', 'account');
        return $list;
    }

    public function checkPercentage($attribute, $params) {
        $_used_percent = (float) \backend\models\CollectionAccounts::find()
                        ->where(["status" => 1])
                        ->sum("percentage");

        if (!empty($this->getOldAttributes()['percentage'])) {
            $_used_percent = $_used_percent - $this->getOldAttributes()['percentage'];
        }
        $_remaining_percent = 100 - $_used_percent;

        if ($_used_percent == 100) {
            $this->addError('percentage', 'You cannot add any more accounts. Total percentage for active accounts is 100%');
        }
        if ($this->percentage > $_remaining_percent) {
            $this->addError('percentage', 'Max percent for new account cannot exceed ' . $_remaining_percent . "%");
        }
    }

}
