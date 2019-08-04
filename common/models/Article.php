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
 * @property string $title_image
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
 * @property string $keyword
 * @property string $from_platform
 * @property integer $created_at
 * @property integer $updated_at
 * @property ArticleContents $content
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
            [['id', 'user_id', 'category_id', 'hits', 'comment_count', 'is_allow_comment', 'top', 'bad', 'is_show', 'is_delete', 'is_hot', 'sort_value', 'created_at', 'updated_at'], 'integer'],
            [['from_platform', 'keyword'], 'string'],
            [['author', 'source'], 'string', 'max' => 30],
            [['description', 'title_image'], 'string', 'max' => 200],
			['is_show', 'default', 'value' => self::IS_SHOW_YES],
			['is_delete', 'default', 'value' => self::IS_DELETE_NO],
			['is_hot', 'default', 'value' => 0],
			['is_allow_comment', 'default', 'value' => self::IS_ALLOW_COMMENT_YES],
			[['is_show', 'is_allow_comment'], 'in', 'range' => [self::IS_SHOW_YES, self::IS_SHOW_NO]],
			['title_style', 'filter', 'filter' => function ($value) {
				return self::filterTitleStyle($value);
			}],
			[['comment_count', 'top', 'hits', 'bad'], 'default', 'value' => 0],
			['keyword', 'filter', 'filter' => function ($value) {
        		$value = trim($value);
        		$value = str_replace('， ', ',', $value);
        		$value = str_replace(', ', ',', $value);
        		$value = str_replace('，', ',', $value);
        		$value = str_replace(' ', ',', $value);
				return $value;
			}],
			[['keyword', 'title', 'description', 'title_style'], 'filter', 'filter' => 'trim'],
        ];
    }

	public function scenarios()
	{
		return array_merge(
			parent::scenarios(),
			[
				'update-brief' => [
					'title',
					'category_id',
					'sort_value',
					'author',
					'source',
				],
				'change-is_show' => [
					'is_show',
				],
				'change-is_allow_comment' => [
					'is_allow_comment',
				],
				'delete' => [
					'is_delete',
				],
				'update-comment_count' => [
					'comment_count',
				],
				'update-hits' => [
					'hits',
				],
			]
		);
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

	public static function getListField()
	{
		return [
			'article.*',
			'category_name',
			'nickname',
		];
	}

	public function getContent()
	{
		// ['id' => 'id']， 主 => 副
		return $this->hasOne(ArticleContents::className(), ['id' => 'id']);
	}

	/**
	 * @Desc: 转化 title_style
	 * @param $value
	 * @return string
	 */
	public static function filterTitleStyle($value)
	{
		if (empty($value)) {
			return '';
		}
		if (!is_array($value)) {
			return $value;
		}
		$titleStyle = '';
		foreach ($value as $k => $v) {
			$titleStyle .= sprintf('%s:%s;', $k, $v);
		}
		return  strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '-$1', $titleStyle));
	}
	
	public static function searchFields()
	{
		return [
			'title' => [
				'name' => '标题',
				'field' => 'title',
				'width' => 0,
				'type' => 'text',
				'condition' => 'like',
				'format' => ''
			],
			'category_id' => [
				'name' => '分类',
				'type' => 'select',
				'format' => 'intval',
			],
			'start_at' => [
				'name' => '开始时间',
				'field' => 'article.created_at',
				'type' => 'date',
				'condition' => '>=',
			],
			'end_at' => [
				'name' => '结束时间',
				'field' => 'article.created_at',
				'type' => 'date',
				'condition' => '<',
			],
			'id' => [
				'field' => 'article.id',
				'name' => 'ID',
				'type' => 'number',
				'condition' => '=',
			],
		];
	}
	
	public static function getSearchFieldByAction(string $action) : array
	{
		$searchFieldKeyList = [
			'index' => [
				'id', 'title', 'category_id', 'start_at', 'end_at',
			],
		];
		$result = [];
		if (isset($searchFieldKeyList[$action])) {
			$searchFields = self::searchFields();
			foreach ($searchFieldKeyList[$action] as $item) {
				if (isset($searchFields[$item])) {
					$result[$item] = array_merge(static::$defaultSearchField, $searchFields[$item]);
				}
			}
		}
		
		return $result;
	}

}
