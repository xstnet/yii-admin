<?php
/**
 * Desc: 缓存管理
 * Created by PhpStorm.
 * User: xstnet
 * Date: 19-07-26
 * Time: 下午12:56
 */

namespace backend\controllers;

use common\exceptions\ParameterException;
use common\models\AdminUser;
use yii\filters\VerbFilter;
use Yii;

class CacheController extends AdminLogController
{
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'index' => ['get'],
						'clear-index' => ['get'],
						'clear-all' => ['get'],
					],
				],
			]
		);
	}

	public function actions()
	{
		return [

		];
	}

	/**
	 * @Desc: 个人信息，页面
	 * @return string
	 */
	public function actionIndex()
	{

		return $this->render('index', [
		]);
	}
	
	/**
	 * 清理首页缓存
	 * @return array
	 */
	public function actionClearIndex()
	{
		Yii::$app->userCache->refresh('setting');
		Yii::$app->userCache->refresh('articleCategory');
		Yii::$app->userCache->refresh('ArticleCategoryTree');
		Yii::$app->userCache->refresh('latestArticle');
		Yii::$app->userCache->refresh('tagList');
		
		$indexHtml = file_get_contents('http://www.xstnet.com');
		file_put_contents('./index.html', $indexHtml);
		
		return self::ajaxSuccess('清理成功');
	}
	
	/**
	 * 清理全部缓存
	 * @return array
	 */
	public function actionClearAll()
	{
		Yii::$app->userCache->flush();
		
		$indexHtml = file_get_contents('http://www.xstnet.com');
		file_put_contents('./index.html', $indexHtml);
		
		return self::ajaxSuccess('清理成功');
	}

}