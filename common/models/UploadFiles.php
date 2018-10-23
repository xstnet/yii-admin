<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%upload_files}}".
 *
 * @property integer $id
 * @property string $save_name
 * @property string $path
 * @property integer $size
 * @property string $extend
 * @property string $md5
 * @property string $token
 * @property integer $created_at
 * @property integer $updated_at
 */
class UploadFiles extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upload_files}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size', 'created_at', 'updated_at'], 'integer'],
            [['save_name'], 'string', 'max' => 100],
            [['path'], 'string', 'max' => 150],
            [['extend'], 'string', 'max' => 20],
            [['md5'], 'string', 'max' => 32],
            [['token'], 'string', 'max' => 128],
            [['md5'], 'unique'],
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
            'save_name' => 'Save Name',
            'path' => 'Path',
            'size' => 'Size',
            'extend' => 'Extend',
            'md5' => 'Md5',
            'token' => 'Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
