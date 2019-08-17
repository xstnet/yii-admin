<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%article_contents}}".
 *
 * @property integer $id
 * @property string $content
 * @property string $markdown_content
 * @property string $directory
 * @property integer $created_at
 * @property integer $updated_at
 */
class ArticleContents extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_contents}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['content', 'markdown_content', 'directory'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'markdown_content' => 'Markdown content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
