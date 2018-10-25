<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%config_category}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort_value
 * @property string $code
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ConfigCategory extends BaseModel
{
	const STATUS_ACTIVE = 1;  // 启用
	const STATUS_DISABLED = 0; // 禁用
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_value', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['code'], 'string', 'max' => 20],
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
            'sort_value' => 'Sort Value',
            'code' => 'Code',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
