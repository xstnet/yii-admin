<?php

namespace common\models;

use common\exceptions\DatabaseException;
use Yii;

/**
 * This is the model class for table "{{%pm_permissions}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $status
 * @property string $menu_id
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class Permissions extends BaseModel
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
        return '{{%pm_permissions}}';
    }

	public function formName()
	{
		return '';
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'menu_id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 150],
            ['url', 'string', 'max' => 60],
            ['url', 'unique', 'message' => '路由已存在'],
			['status', 'default', 'value' => static::STATUS_ACTIVE]
        ];
    }

    public static function getListField()
	{
		return [
			'id',
			'name',
			'description',
			'menu_id',
			'url',
			'status',
			'created_at',
		];
	}

    public function scenarios()
	{
		return array_merge(
			parent::scenarios(),
			[
				self::SCENARIO_INSERT => [
					'name',
					'menu_id',
					'description',
					'status',
					'url',
				],
				self::SCENARIO_UPDATE => [
					'name',
					'menu_id',
					'description',
					'status',
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
            'name' => 'Name',
            'description' => 'Description',
            'menu_id' => 'Meun ID',
            'url' => 'Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function findModel(int $id)
	{
		if ($id === 0) {
			throw new DatabaseException(DatabaseException::UNKNOWN,"权限节点不存在");
		}
		$model = self::findOne($id);
		if (!$model) {
			throw new DatabaseException(DatabaseException::UNKNOWN, '权限节点不存在');
		}

		return $model;
	}
}
