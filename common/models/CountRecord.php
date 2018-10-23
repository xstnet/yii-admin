<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%count_record}}".
 *
 * @property integer $id
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property integer $count
 * @property integer $created_at
 * @property integer $updated_at
 */
class CountRecord extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%count_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'month', 'day', 'count', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'month' => 'Month',
            'day' => 'Day',
            'count' => 'Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
