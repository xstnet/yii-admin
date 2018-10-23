<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%todolist}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Todolist extends BaseModel
{
	/**
	 * todos 状态
	 */
	const STATUS_COMPLETED = 1; // 已完成
	const STATUS_UNDERWAY = 0; // 进行中
	const STATUS_DELETE = 2; // 已删除
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%todolist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
			['status', 'default', 'value' => self::STATUS_UNDERWAY],
            [['name'], 'string', 'max' => 100],
            [['name'], 'required', 'message' => '请输入{attribute}',],
			[
				'name', // 多个字段第一个元素为数组
				'filter',
				'filter' => function ($value) {
					return static::filterStr($value);
				}
			],
        ];
    }

    public function fields()
	{
		return array_merge(parent::fields(), [
			'text' => function (self $model) {
				return $model->name;
			},
			'completed' => function (self $model) {
				return $model->status;
			},
		]);
	}

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => '名称',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
