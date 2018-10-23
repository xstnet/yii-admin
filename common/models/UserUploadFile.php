<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_upload_file}}".
 *
 * @property integer $id
 * @property string $origin_name
 * @property string $save_name
 * @property string $path
 * @property integer $size
 * @property string $extend
 * @property string $token
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserUploadFile extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_upload_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size', 'created_at', 'updated_at'], 'integer'],
            [['origin_name', 'save_name'], 'string', 'max' => 100],
            [['path'], 'string', 'max' => 150],
            [['extend'], 'string', 'max' => 20],
            [['token'], 'string', 'max' => 128],
            [['token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'origin_name' => 'Origin Name',
            'save_name' => 'Save Name',
            'path' => 'Path',
            'size' => 'Size',
            'extend' => 'Extend',
            'token' => 'Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
