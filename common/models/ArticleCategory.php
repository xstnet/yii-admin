<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article_category}}".
 *
 * @property integer $id
 * @property string $category_name
 * @property integer $parent_id
 * @property string $parents
 * @property integer $status
 * @property integer $sort_value
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleCategory extends BaseModel
{

	/**
	 * 状态
	 */
	const STATUS_ACTIVE = 0;  // 启用
	const STATUS_DISABLED = 1; // 禁用

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'sort_value', 'created_at', 'updated_at'], 'integer'],
            [['category_name'], 'string', 'max' => 30],
			['category_name', 'required'],
            ['parents', 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'parent_id' => 'Parrent ID',
            'parents' => 'Parents',
        ];
    }
}
