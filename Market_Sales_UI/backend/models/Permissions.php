<?php

namespace backend\models;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "permissions".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @property PermissionsToRoles[] $permissionsToRoles
 * @property PermissionsToUsers[] $permissionsToUsers
 */
class Permissions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unza_permissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description'], 'string'],
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
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionsToRoles()
    {
        return $this->hasMany(PermissionsToRoles::className(), ['permission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissionsToUsers()
    {
        return $this->hasMany(PermissionsToUsers::className(), ['permission_id' => 'id']);
    }
    
     public static function getPermissions() {
        $query = static::find()->all();
        return $query;
    }
    
     public static function getPermissionList() {
        $query = static::find()
                ->orderBy(['name' => SORT_ASC])
                ->all();
        $list = ArrayHelper::map($query, 'id', 'name');
        return $list;
    }
     public static function findById($id) {
        return static::findOne(['id' => $id]);
    }
}
