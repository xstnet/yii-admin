<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%count_total}}".
 *
 * @property integer $id
 * @property integer $date_at
 * @property integer $total_count
 * @property integer $created_at
 * @property integer $updated_at
 */
class CountTotal extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%count_total}}';
    }
}
