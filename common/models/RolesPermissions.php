<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pm_roles_permissions}}".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $permission_id
 * @property integer $permission_type
 * @property integer $created_at
 * @property integer $updated_at
 */
class RolesPermissions extends BaseModel
{
	const PERMISSION_TYPE_ACTION = 1; // 操作权限
	const PERMISSION_TYPE_MENU = 2; // 菜单权限

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pm_roles_permissions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'permission_id', 'permission_type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'permission_id' => 'Permission ID',
            'permission_type' => 'Permission Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
