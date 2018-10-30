<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property string $title
 * @property string $title_style
 * @property string $author
 * @property string $description
 * @property integer $hits
 * @property integer $comment_count
 * @property integer $is_allow_comment
 * @property integer $top
 * @property integer $bad
 * @property integer $is_delete
 * @property integer $is_show
 * @property integer $is_hot
 * @property integer $sort_value
 * @property string $source
 * @property string $from_platform
 * @property integer $created_at
 * @property integer $updated_at
 */
class Article extends BaseModel
{
	/**
	 * 是否展示
	 */
	const IS_SHOW_YES = 1;
	const IS_SHOW_NO = 0;

	/**
	 * 是否删除
	 */
	const IS_DELETE_YES = 1;
	const IS_DELETE_NO = 0;

	/**
	 * 是否允许评论
	 */
	const IS_ALLOW_COMMENT_YES = 1;
	const IS_ALLOW_COMMENT_NO = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'hits', 'comment_count', 'is_allow_comment', 'top', 'bad', 'is_show', 'is_delete', 'is_hot', 'sort_value', 'created_at', 'updated_at'], 'integer'],
            [['from_platform'], 'string'],
            [['title', 'title_style'], 'string', 'max' => 50],
            [['author', 'source'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 200],
			['is_show', 'default', 'value' => self::IS_SHOW_YES],
			['is_allow_comment', 'default', 'value' => self::IS_ALLOW_COMMENT_YES],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Uid',
            'category_id' => 'Cotegory ID',
            'title' => 'Title',
            'title_style' => 'Title Style',
            'author' => 'Author',
            'description' => 'Description',
            'hits' => 'Hits',
            'comment_count' => 'Comment Count',
            'is_allow_comment' => 'Is Allow Comment',
            'top' => 'Top',
            'bad' => 'Bad',
            'is_delete' => 'Is Delete',
            'is_show' => 'Is Show',
            'is_hot' => 'Is Hot',
            'sort_value' => 'Sort Value',
            'source' => 'Source',
            'from_platform' => 'From Platform',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
