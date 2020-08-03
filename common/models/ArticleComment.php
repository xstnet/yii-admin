<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article_comment}}".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $nickname
 * @property string $reply_comment_ids
 * @property integer $reply_comment_id
 * @property string $email
 * @property string $avatar
 * @property string $content
 * @property string $ip
 * @property integer $is_delete
 * @property integer $is_read
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleComment extends BaseModel
{
	const IS_READ_YES = 1;
	const IS_READ_NO = 0;
	
	const IS_DELETE_YES = 1;
	const IS_DELETE_NO = 0;

	const CUSTOM_HTML_BEGIN_TAG = 'n!#a';
	const CUSTOM_HTML_END_TAG = 'a!#n';
	const CUSTOM_HTML_QUOTE = 'Y!#Y';

	// 最大回复层级
	const MAX_REPLY_LEVEL = 5;

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
            [['created_at', 'updated_at', 'article_id', 'is_delete', 'is_read', 'reply_comment_id'], 'integer'],
			['nickname', 'required', 'message' => '名称不能为空!'],
			['content', 'required', 'message' => '内容不能为空!'],
			['ip', 'string'],
			['reply_comment_ids', 'string'],
            [['nickname'], 'string', 'min' => 1, 'max' => 30, 'tooLong' => '名称不能超过30个字符', 'tooShort' => '名称不能小于1个字符'],
            [['email', 'avatar'], 'string', 'max' => 100, 'tooLong' => '邮箱不能超过100个字符'],
            [['content'], 'string', 'min' => 2, 'max' => 65535, 'tooLong' => '内容不能超过65535个字符', 'tooShort' => '内容不能小于2个字符'],
            [['is_read', 'is_delete', 'reply_comment_id'], 'default', 'value' => 0],
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

    public static function getCustomTag($content, $tag, $class = '')
    {
        return self::CUSTOM_HTML_BEGIN_TAG
            . $tag . (empty($class) ? '' : ' class=' . self::CUSTOM_HTML_QUOTE . $class . self::CUSTOM_HTML_QUOTE)
            . self::CUSTOM_HTML_END_TAG
            . $content
            . self::CUSTOM_HTML_BEGIN_TAG . '/' . $tag
            . self::CUSTOM_HTML_END_TAG;
    }
}
