<?php
/**
 * Message
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 19-7-25
 * Time: 下午3:37
 */

namespace frontend\controllers;


use common\models\Article;
use common\models\Messages;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\NotFoundHttpException;

class MessageController extends BaseController
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'release' => ['post'],
					'index' => ['get'],
				],
			],
			'PageCache' => [
				'class' => 'yii\filters\PageCache',
				'only' => ['index'],
				'duration' => 0,
				'enabled' => true,
				'variations' => Yii::$app->request->get(),
				'dependency' => [
					'class' => 'yii\caching\DbDependency',
					'sql' => "SELECT COUNT(*) FROM x_messages",
				],
			],
		];
	}
	
	/**
	 * Displays Message Content.
	 * @return string
	 */
	public function actionIndex()
	{
		$query = Messages::find();
		
		list ($count, $pages) = $this->getPage($query, 20);
		
		$messageList = $query->asArray()
			->all();
		
		return $this->render('index', [
			'messageList' => $messageList,
			'pages' => $pages,
		]);
		
	}
	
	/**
	 * 发布留言
	 */
	public function actionRelease()
	{
		// 验证留言间隔, 1分钟内只能发一次
		$session = Yii::$app->session;
		$session->open();
		$lastPubAt = $session->get('message_last_pub_at', 0);
		
		if ($lastPubAt > 0 && ($lastPubAt + 60) > time()) {
			exit("<script>alert('1分钟内只能发布一次哦!');history.go(-1)</script>");
		}
		$params = Yii::$app->request->post();
		$message = new Messages();
		$message->avatar = sprintf('/uploads/avatar/%d.jpg', rand(1, 5));
		$message->nickname = HtmlPurifier::process(trim($params['nickname']));
		$message->email = HtmlPurifier::process(trim($params['email']));
		$message->content = trim($params['content']);
		$message->ip = Yii::$app->request->userIP;
		
		$error = '';
		if (!$message->save()) {
			$error = current($message->getFirstErrors());
		}
		
		if ($error != '') {
			exit("
				<script>alert('$error');history.go(-1)</script>
			");
		}
		
		$session->set('message_last_pub_at', time());
		
		exit("
				<script>alert('发布成功');location.href='/message.html'</script>
			");
		
	}
}