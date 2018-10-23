<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%friendly_link}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property integer $status
 * @property integer $sort_value
 * @property string $target
 * @property integer $created_at
 * @property integer $updated_at
 */
class FriendlyLink extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%friendly_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'sort_value', 'created_at', 'updated_at'], 'integer'],
            [['name', 'target'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'url' => 'Url',
            'status' => 'Status',
            'sort_value' => 'Sort Value',
            'target' => 'Target',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
