<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%menus}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $sort_value
 * @property integer $status
 * @property string $icon
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class Menus extends BaseModel
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
        return '{{%menus}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort_value', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['icon'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 100],
            [['url'], 'unique', 'message' => '路由已存在'],
        ];
    }

    public function scenarios()
	{
		return array_merge(
			parent::scenarios(),
			[
				self::SCENARIO_INSERT => [
					'name',
					'parent_id',
					'sort_value',
					'status',
					'icon',
					'url',
				],
				self::SCENARIO_UPDATE => [
					'name',
					'parent_id',
					'sort_value',
					'status',
					'icon',
					'url',
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
            'name' => '菜单名称',
            'parent_id' => '所属菜单',
            'sort_value' => '排序值',
            'status' => '状态',
            'icon' => '图标',
            'url' => '路由',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getList()
	{
		return [
			'id',
			'label' => 'name',
			'parent_id',
			'sort_value',
			'url',
			'href' => 'url',
			'status',
			'icon',
		];
	}
}
