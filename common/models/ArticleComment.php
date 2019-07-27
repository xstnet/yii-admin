<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article_comment}}".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $nickname
 * @property string $email
 * @property string $avatar
 * @property string $content
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleComment extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'article_id'], 'integer'],
			['nickname', 'required', 'message' => '名称不能为空!'],
			['ip', 'string'],
            [['nickname'], 'string', 'min' => 1, 'max' => 30, 'tooLong' => '名称不能超过30个字符', 'tooShort' => '名称不能小于1个字符'],
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
