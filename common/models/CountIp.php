<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%count_ip}}".
 *
 * @property integer $id
 * @property integer $date_at
 * @property integer $count
 * @property integer $created_at
 * @property integer $updated_at
 */
class CountIp extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%count_ip}}';
    }
}
