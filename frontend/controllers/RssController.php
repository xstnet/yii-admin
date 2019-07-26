<?php
/**
 * Rss
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 19-7-25
 * Time: 下午3:37
 */

namespace frontend\controllers;


use common\helpers\Rss;
use common\models\Article;
use Yii;
use yii\web\NotFoundHttpException;

class RssController extends BaseController
{
	public function actionIndex()
	{
//		$rss = <<<ETO
/*<?xml version="1.0" encoding="utf-8"?><rss version="2.0">*/
//<channel>
//    <title>媒体名称/定义网站频道名称</title>
//    <description>媒体名称/定义网站频道介绍</description>
//    <link>网站频道地址</link>
//    <generator>xstnet.com</generator>
//    <image>
//        <url>http://xstnet.com/favicon.ico</url>
//        <title>徐善通的随笔</title>
//        <link>http://xstnet.com</link>
//    </image>
//    <item>
//        <title><![CDATA[ 文章标题 ]]></title>
//        <link>文章URL地址（绝对地址）</link>
//        <description><![CDATA[ 摘要/全文 ]]></description>
//        <pubDate>Mon, 07 Jul 2014 13:42:28 +0800</pubDate> // 最后发布时间
//    </item>
//</channel>
//</rss>
//ETO;
//
//		echo $rss;
		
		$rss = new Rss();
		
		$articleList = Article::find()
			->select(['id', 'title', 'created_at', 'description'])
			->where(['is_delete' => Article::IS_DELETE_NO, 'is_show' => Article::IS_SHOW_YES])
			->orderBy(['created_at' => SORT_DESC])
			->asArray()
			->all();
		
		foreach ($articleList as $item) {
			$rss->setItem([
				'title' => $item['title'],
				'link' => Yii::$app->request->hostInfo . "/article-{$item['id']}.html",
				'description' => $item['description'],
				'pubDate' => date("D, d M Y H:i:s ", $item['created_at']) . "GMT",
				'guid' => Yii::$app->request->hostInfo . "/article-{$item['id']}.html",
			]);
		}
		
		Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
		Yii::$app->response->content = $rss->renderRss();
	}
}