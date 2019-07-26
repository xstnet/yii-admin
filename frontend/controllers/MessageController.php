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
		
		$messageList = $query->orderBy(['id' => SORT_DESC])
			->asArray()
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
		$params = Yii::$app->request->post();
		$message = new Messages();
		$message->avatar = sprintf('/uploads/avatar/%d.jpg', rand(1, 5));
		$message->nickname = HtmlPurifier::process(trim($params['nickname']));
		$message->email = HtmlPurifier::process(trim($params['email']));
		$message->content = trim($params['content']);
		$error = '';
		if (!$message->save()) {
			$error = current($message->getFirstErrors());
		}
		
		if ($error != '') {
			exit("
				<script>alert('$error');history.go(-1)</script>
			");
		}
		
		exit("
				<script>alert('发布成功');location.href='/message.html'</script>
			");
		
	}
}