<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%error_log}}".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $type
 * @property integer $status
 * @property string $message
 * @property string $params_get
 * @property string $params_post
 * @property string $trace
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class ErrorLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%error_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['params_get', 'params_post', 'trace'], 'required'],
            [['params_get', 'params_post', 'trace'], 'string'],
            [['type'], 'string', 'max' => 20],
            [['message'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'status' => 'Status',
            'message' => 'Message',
            'params_get' => 'Params Get',
            'params_post' => 'Params Post',
            'trace' => 'Trace',
            'url' => 'Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
