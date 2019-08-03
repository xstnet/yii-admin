<?php
/**
 * Task
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 19-7-26
 * Time: 上午10:00
 */
namespace console\controllers;

use common\models\Config;
use common\models\TaskMail;
use Yii;
use common\models\Article;
use common\models\ArticleTag;

class TaskController extends BaseController
{
	/**
	 * 发送邮件, 手动执行 run `php ./yii task/send-mails`
	 *
	 * 自动执行
	 * ```
	 * 每天9点-23点, 每隔半小时执行一次
	 * 0,30 9-23 * * * cd project && /usr/bin/php ./yii task/send-mails
	 * ```
	 * @return bool
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionSendMails()
	{
		$this->printf('开始发送邮件');
		
		$mailList = TaskMail::find()
			->where(['is_send' => TaskMail::IS_SEND_FALSE])
			->andWhere(['<', 'call_count', TaskMail::MAX_CALL_COUNT])
			->orderBy(['id' => SORT_ASC])
			->limit(10)
			->all();
		
		$this->printf(sprintf('本次共有%d封邮件待发送', count($mailList)));
		
		if (empty($mailList)) {
			$this->printf("结束发送邮件" . PHP_EOL);
			return true;
		}
		
		/**
		 * 更新邮件服务
		 */
		// 后台设置的邮件配置
		$mailConfig = Config::find()
			->where(['category_id' => 2])
			->asArray()
			->indexBy('code')
			->all();
		
		// 代码中配置的参数
		$mailParams = Yii::$app->getComponents()['mailer'];
		
		$newMailerConfig = [
			'transport' => [
				'host' => $mailConfig['email_host']['value'],
				// qq邮箱
				'username' => $mailConfig['email_username']['value'],
				//授权码, 什么是授权码， http://service.mail.qq.com/cgi-bin/help?subtype=1&&id=28&&no=1001256
				'password' => $mailConfig['email_password']['value'],
				'port' => $mailConfig['email_port']['value'],
				'encryption' => $mailConfig['email_encryption']['value'],
			],
			'messageConfig' => [
				'from' => ["{$mailConfig['email_from']['value']}" => $mailConfig['email_from_title']['value'],],
			],
		];
		
		$newMailerConfig = \yii\helpers\ArrayHelper::merge($mailParams, $newMailerConfig);
		
		Yii::$app->set('mailer', $newMailerConfig);
		
		$mail = Yii::$app->mailer->compose();
		
		$successCount = 0;
		foreach ($mailList as $item) {
			try {
				$result = $mail->setTo($item->to_mail)
					->setSubject($item->subject)
					->setHtmlBody($item->content)
					->send();
				if ($result) {
					$item->send_at = time();
					$item->is_send = TaskMail::IS_SEND_TRUE;
					$successCount ++;
				}
				$item->call_count ++;
				// 不判断, 失败了就等下次计划任务
				$item->save();
			} catch (\Exception $e) {
				$this->printf(sprintf('任务ID: %d 发送失败, 原因%s', $item->id, $e->getMessage()));
				Yii::error($e->getTraceAsString());
			}
		}
		
		$this->printf(sprintf('共成功发送%d封邮件', $successCount));
		$this->printf("结束发送邮件" . PHP_EOL);
	}
}