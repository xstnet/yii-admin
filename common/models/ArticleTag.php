<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article_tag}}".
 *
 * @property int $id
 * @property string $name
 * @property int $article_count
 * @property int $is_show
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ArticleTag extends BaseModel
{
	/**
	 * 是否展示
	 */
	const IS_SHOW_YES = 1;
	const IS_SHOW_NO = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['article_count', 'is_show', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'unique'],
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
            'article_count' => 'Article Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	public function scenarios()
	{
		return array_merge(
			parent::scenarios(),
			[
				'change-is_show' => [
					'is_show',
				],
			]
		);
	}
}
