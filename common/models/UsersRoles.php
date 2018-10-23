<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pm_users_roles}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class UsersRoles extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pm_users_roles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'user_id',
            'role_id' => 'Role ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
