<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "permissions_to_roles".
 *
 * @property int $id
 * @property int $permission_id
 * @property int $role_id
 *
 * @property Roles $role
 * @property Permissions $permission
 */
class PermissionsToRoles extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'unza_permission_to_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['permission_id', 'role_id'], 'required'],
            [['permission_id', 'role_id'], 'integer'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['role_id' => 'role_id']],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Permissions::className(), 'targetAttribute' => ['permission_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'permission_id' => 'Permission ID',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole() {
        return $this->hasOne(Roles::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermission() {
        return $this->hasOne(Permissions::className(), ['id' => 'permission_id']);
    }

    public static function getRolePermissions($roleid) {
        $rights = \backend\models\Permissions::find()
                ->joinWith('permissionsToRoles')
                ->where([\backend\models\PermissionsToRoles::tableName() . '.role_id' => $roleid])
                ->orderBy(['name' => SORT_ASC])
                ->all();
        return \yii\helpers\ArrayHelper::map($rights, 'id', 'name');
    }
 

}
