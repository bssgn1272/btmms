<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property int $role_id
 * @property string $first_name
 * @property string $last_name
 * @property string $other_name
 * @property string $salutation
 * @property string $id_number
 * @property string $gender
 * @property string $dob
 * @property string $phone
 * @property string $email
 * @property string $email
 * @property int $status
 * @property string $auth_key
 * @property string $password
 * @property string $password_reset_token
 * @property string $verification_token
 * @property int $date_created
 * @property int $created_by
 * @property int $date_updated
 * @property int $updated_by
 *
 * @property PermissionsToUsers[] $permissionsToUsers
 * @property Roles $role
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 2;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $confirm_password;
    public $confirm_email;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['role_id', 'mobile_number', 'status', 'auth_key', 'password', 'token_balance'], 'required'],
            [['role_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['firstname', 'lastname', 'nrc', 'mobile_number','account_number'], 'string'],
            //[['firstname', 'lastname', 'nrc', 'mobile_number','QR_code','account_number'], 'string'],
            [['dob'], 'safe'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'string', 'min' => 6],
            [['gender'], 'string', 'max' => 10],
            ['mobile_number','unique','targetClass' => '\backend\models\User', 'message' => 'A record with this mobile number exists in the system.'],
            //['nrc','unique','targetClass' => '\backend\models\User', 'message' => 'A record with this NRC exists in the system.'],
            //['account_number','unique','targetClass' => '\backend\models\User', 'message' => 'A record with this Account number exists in the system.'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'This email address has already been taken.'],
            ['confirm_email', 'compare', 'compareAttribute' => 'email', 'message' => "Emails do not match!"],
            [['password_reset_token'], 'unique'],
            [['auth_key'], 'string', 'max' => 32],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['role_id' => 'role_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_id' => 'ID',
            'role_id' => 'Role',
            'first_name' => 'First Name',
            'last_name' => 'Surname',
            'nrc' => 'NRC',
            'gender' => 'Gender',
            'dob' => 'Date of Birth',
            'mobile_number' => 'Mobile number',
            //'QR_code' => 'QR Code',
            'token_balance' => 'Token Balance',
           // 'account_number' => 'Account number',
            'email' => 'Email',
            'status' => 'Status',
            'date_created' => 'Date Created',
            'created_by' => 'Created By',
            'date_updated' => 'Date Updated',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritdoc}
     */
   /* public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_created', 'date_updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_updated'],
                ],
            ],
        ];
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionsToUsers() {
        return $this->hasMany(PermissionsToUsers::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole() {
        return $this->hasOne(Roles::className(), ['role_id' => 'role_id']);
    }

    public function getFullName() {
        return $this->title . " " . $this->first_name . " " . $this->other_name . " " . $this->last_name;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUsername($email) {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findById($id) {
        return static::findOne(['user_id' => $id]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByPasswordResetTokenInactiveAccount($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_INACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
                    'verification_token' => $token,
                    'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password . $this->auth_key, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password . $this->auth_key);
    }

    public function setStatus() {
        $this->status = self::STATUS_ACTIVE;
    }

    public function setByIDs($id) {
        $this->created_by = $id;
        $this->updated_by = $id;
    }

    /**
     * Generates "remember me" authentication key
     * @throws \yii\base\Exception
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws \yii\base\Exception
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateEmailVerificationToken() {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public static function userIsAllowedTo($right) {
        $session = Yii::$app->session;
        $rights = explode(',', $session['rights']);
        if (in_array($right, $rights)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public static function getActiveUsers() {
        $query = static::find()
                ->select(['email', 'user_id'])
                ->where(['status' => self::STATUS_ACTIVE])
                ->andWhere(['NOT IN', 'user_id', [Yii::$app->user->identity->user_id]])
                ->orderBy(['email' => SORT_ASC])
                ->asArray()
                ->all();
        return ArrayHelper::map($query, 'user_id', 'email');
    }

    public function getUsernameById($id) {
        $query = self::findOne(['user_id' => $id]);
        return $query->email;
    }

    /**
     * @return array
     */
    public static function getMobileNumbers() {
        $users = static::find()->orderBy(['email' => SORT_ASC])->all();
        $list = ArrayHelper::map($users, 'mobile_number', 'mobile_number');
        return $list;
    }

}
