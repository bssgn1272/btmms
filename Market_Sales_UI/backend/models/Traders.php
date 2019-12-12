<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "traders".
 *
 * @property int $trader_id
 * @property string $role
 * @property string $firstname
 * @property string $lastname
 * @property string $nrc
 * @property string $gender
 * @property string $mobile_number
 * @property string $QR_code
 * @property double $token_balance
 * @property string $account_number
 * @property string $dob
 * @property string $image
 * @property string $password
 * @property string $auth_key
 * @property string $verification_code
 * @property string $password_reset_token
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property string $date_created
 * @property string $date_updated
 */
class Traders extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'traders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['firstname', 'lastname', 'nrc', 'mobile_number', 'account_number'], 'required'],
            [['token_balance'], 'number'],
            [['dob', 'date_created', 'date_updated'], 'safe'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['role', 'nrc'], 'string', 'max' => 50],
            [['firstname', 'lastname', 'QR_code', 'password', 'stand_no','auth_key', 'verification_code', 'password_reset_token'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 20],
            [['mobile_number'], 'string', 'max' => 15],
            [['account_number', 'image'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'trader_id' => 'Trader ID',
            'role' => 'Role',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'nrc' => 'NRC',
            'gender' => 'Gender',
            'mobile_number' => 'Mobile Number',
            'QR_code' => 'QR Code',
            'token_balance' => 'Token Balance',
            'account_number' => 'Account Number',
            'dob' => 'DOB',
            'image' => 'Image',
            'stand_no' => 'Stand No',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'verification_code' => 'Verification Code',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    public function getTraders() {
        $query = static::find()
                ->select(["CONCAT(CONCAT(firstname,' ',lastname),'/',mobile_number) as name", 'trader_id', 'status'])
                ->where(["IN", 'status', [1]])
                ->orderBy(['trader_id' => SORT_ASC])
                ->asArray()
                ->all();

        return \yii\helpers\ArrayHelper::map($query, 'trader_id', 'name');
    }

    public static function getMobileNumbers() {
        $users = static::find()->orderBy(['trader_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'mobile_number', 'mobile_number');
        return $list;
    }

    public static function getNRCs() {
        $users = static::find()->orderBy(['trader_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'nrc', 'nrc');
        return $list;
    }
    public static function getQRCodes() {
        $users = static::find()->orderBy(['trader_id' => SORT_ASC])->all();
        $list = \yii\helpers\ArrayHelper::map($users, 'QR_code', 'QR_code');
        return $list;
    }
   

}
