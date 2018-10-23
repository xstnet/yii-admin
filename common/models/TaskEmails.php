<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%task_emails}}".
 *
 * @property integer $id
 * @property string $user_email
 * @property string $subject
 * @property string $content
 * @property integer $status
 * @property string $remark
 * @property string $plan_at
 * @property string $processed_at
 * @property string $created_at
 * @property string $updated_at
 */
class TaskEmails extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task_emails}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['status', 'plan_at', 'processed_at', 'created_at', 'updated_at'], 'integer'],
            [['user_email'], 'string', 'max' => 100],
            [['subject'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_email' => 'User Email',
            'subject' => 'Subject',
            'content' => 'Content',
            'status' => 'Status',
            'remark' => 'Remark',
            'plan_at' => 'Plan At',
            'processed_at' => 'Processed At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
