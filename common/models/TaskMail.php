<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%task_mail}}".
 *
 * @property int $id
 * @property string $to_mail
 * @property string $subject
 * @property string $content
 * @property int $is_html
 * @property int $is_send
 * @property int $call_count
 * @property int $send_at
 * @property int $created_at 创建时间，时间戳
 * @property int $updated_at 更新时间，时间戳
 */
class TaskMail extends BaseModel
{
	const IS_SEND_TRUE = 1;
	const IS_SEND_FALSE = 0;
	
	const IS_HTML_YES = 1;
	const IS_HTML_NO = 0;
	
	/**
	 * 最大调用次数
	 */
	const MAX_CALL_COUNT = 5;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%task_mail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
			['is_html', 'default', 'value' => self::IS_HTML_YES],
			['is_send', 'default', 'value' => self::IS_SEND_FALSE],
			[['send_at', 'call_count'], 'default', 'value' => 0],
			[['to_mail', 'subject', 'content', 'send_at'], 'required'],
			[['content'], 'string'],
			[['is_html', 'is_send', 'send_at', 'created_at', 'updated_at'], 'integer'],
			[['to_mail', 'subject'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'to_mail' => 'To Mail',
            'subject' => 'Subject',
            'content' => 'Content',
            'is_html' => 'Is Html',
            'is_send' => 'Is Send',
            'send_at' => 'Send At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function addOne(array $data)
	{
		$this->setAttributes($data);
		
		try {
			$this->saveModel();
		} catch (\Exception $e) {
			Yii::error("创建邮件任务失败, 原因: " . $e->getMessage());
		}
	}
}
