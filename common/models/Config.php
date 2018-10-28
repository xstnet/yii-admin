<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property string $description
 * @property integer $status
 * @property string $type
 * @property string $attribute
 * @property integer $sort_value
 * @property integer $created_at
 * @property integer $updated_at
 */
class Config extends BaseModel
{
	const STATUS_ACTIVE = 1;  // 启用
	const STATUS_DISABLED = 0; // 禁用
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status', 'sort_value', 'created_at', 'updated_at'], 'integer'],
            [['type'], 'string'],
            [['code', 'name'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 200],
            [['attribute'], 'string', 'max' => 255],
			['status', 'default', 'value' => self::STATUS_ACTIVE],
			[['code', 'category_id'], 'unique', 'targetAttribute' => ['code', 'category_id'], 'message' => '同一类别下code不能重复'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'code' => 'Code',
            'name' => 'Name',
            'value' => 'Value',
            'description' => 'Description',
            'status' => 'Status',
            'type' => 'Type',
            'attribute' => 'Attribute',
            'sort_value' => 'Sort Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
