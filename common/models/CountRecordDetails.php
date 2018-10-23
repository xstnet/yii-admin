<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%count_record_details}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $ip
 * @property string $url
 * @property string $url_full
 * @property string $params
 * @property string $type
 */
class CountRecordDetails extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%count_record_details}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['params'], 'required'],
            [['params'], 'string'],
            [['ip'], 'string', 'max' => 16],
            [['url'], 'string', 'max' => 200],
            [['url_full'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'ip' => 'Ip',
            'url' => 'Url',
            'url_full' => 'Url Full',
            'params' => 'Params',
            'type' => 'Type',
        ];
    }
}
