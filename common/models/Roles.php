<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pm_roles}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $sort_value
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Roles extends BaseModel
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
        return '{{%pm_roles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_value', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 30],
            ['sort_value', 'default', 'value' => 100],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['description'], 'string', 'max' => 100],
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
            'sort_value' => 'Sort Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

	public static function getListField()
	{
		return [
			'id',
			'name',
			'description',
			'sort_value',
			'status',
			'created_at',
		];
	}
}
