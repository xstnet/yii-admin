<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article_category}}".
 *
 * @property integer $id
 * @property string $category_name
 * @property integer $parrent_id
 * @property string $parents
 */
class ArticleCategory extends BaseModel
{
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
            [['parrent_id'], 'integer'],
            [['category_name'], 'string', 'max' => 30],
            [['parents'], 'string', 'max' => 200],
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
            'parrent_id' => 'Parrent ID',
            'parents' => 'Parents',
        ];
    }
}
