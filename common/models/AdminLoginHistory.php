<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_login_history}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $nickname
 * @property integer $login_at
 * @property string $login_ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdminLoginHistory extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_login_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'login_at', 'created_at', 'updated_at'], 'integer'],
            [['nickname'], 'string', 'max' => 30],
            [['login_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'nickname' => 'Nickname',
            'login_at' => 'Login At',
            'login_ip' => 'Login Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
