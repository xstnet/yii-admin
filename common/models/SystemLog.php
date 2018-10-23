<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%system_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $nickname
 * @property string $title
 * @property string $ip
 * @property string $request_method
 * @property string $params
 * @property string $route
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class SystemLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['nickname', 'params', 'title', 'request_method', 'route'], 'string'],
            [['title', 'url'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Uid',
            'nickname' => 'Username',
            'params' => 'Params',
            'request_method' => 'request Method',
            'title' => 'Title',
            'ip' => 'Ip',
            'route' => 'Route',
            'url' => 'Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
