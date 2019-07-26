<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%messages}}".
 *
 * @property integer $id
 * @property string $nickname
 * @property string $email
 * @property string $avatar
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class Messages extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['nickname'], 'string', 'min' => 1, 'max' => 20, 'tooLong' => '名称不能超过20个字符', 'tooShort' => '名称不能小于1个字符'],
            [['email', 'avatar'], 'string', 'max' => 100, 'tooLong' => '邮箱不能超过100个字符'],
            [['content'], 'string', 'min' => 2, 'max' => 255, 'tooLong' => '内容不能超过255个字符', 'tooShort' => '内容不能小于2个字符'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => 'Nickname',
            'email' => 'Email',
            'avatar' => '头像',
            'content' => '内容',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
