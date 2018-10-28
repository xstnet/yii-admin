<?php
/**
 * 文章管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/27
 * Time: 13:46
 */

namespace backend\controllers;


class ArticleController extends AdminLogController
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
						'category' => ['get'],
						'add-article' => ['post'],
						'save-article' => ['post'],
						'delete-article' => ['post'],
						'add-categoy' => ['post'],
						'save-categoy' => ['post'],
						'save-categoy' => ['post'],
					],
				],
			]
		);
	}

	/**
	 * 打开文章管理页面
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionGetArticles()
	{

	}
}