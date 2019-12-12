<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $active
 * @property int $date_created
 * @property int $created_by
 * @property int $date_updated
 * @property int $updated_by
 *
 * @property PermissionsToRoles[] $permissionsToRoles
 * @property PermissionsToUsers[] $permissionsToUsers
 * @property Users[] $users
 */
class Roles extends \yii\db\ActiveRecord {

    public $permissions;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'permissions'], 'required'],
            [['name'], 'string'],
            ['name', 'unique', 'message' => 'Role name already exists!'],
            ['permissions', 'checkIsArray'],
                //[[ 'date_created', 'created_by', 'date_updated', 'updated_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'permissions' => 'Permissions',
            'date_created' => 'Date Created',
            'created_by' => 'Created By',
            'date_updated' => 'Date Updated',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritdoc}

      public function behaviors() {
      return [
      'timestamp' => [
      'class' => 'yii\behaviors\TimestampBehavior',
      'attributes' => [
      ActiveRecord::EVENT_BEFORE_INSERT => ['date_created', 'date_updated'],
      ActiveRecord::EVENT_BEFORE_UPDATE => ['date_updated'],
      ],
      ],
      ];
      } */
    public function checkIsArray() {
        if (!is_array($this->permissions)) {
            $this->addError('permissions', 'Please select one option!');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionsToRoles() {
        return $this->hasMany(PermissionsToRoles::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionsToUsers() {
        return $this->hasMany(PermissionsToUsers::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(Users::className(), ['role_id' => 'user_id']);
    }

    public static function findById($id) {
        return static::findOne(['role_id' => $id]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getRoleById($id) {
        $role = Self::find()->where(['role_id' => $id])->one();
        return $role->name ;
    }

    public static function getRoles() {
        $_response = [];
        $query = static::find()
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();
        return \yii\helpers\ArrayHelper::map($query, 'role_id', 'name');
    }

    public static function getNames() {
        $_response = [];
        $query = static::find()
                ->orderBy(['name' => SORT_ASC])
                ->asArray()
                ->all();
        return \yii\helpers\ArrayHelper::map($query, 'name', 'name');
    }

    public static function getCodes() {
        $query = static::find()
                ->orderBy(['code' => SORT_ASC])
                ->asArray()
                ->all();
        return \yii\helpers\ArrayHelper::map($query, 'code', 'code');
    }

}
