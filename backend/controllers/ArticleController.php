<?php
/**
 * 文章管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/27
 * Time: 13:46
 */

namespace backend\controllers;


use backend\services\article\ArticleService;
use common\helpers\Helpers;
use yii\filters\VerbFilter;

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

	/**
	 * @Desc: 获取分类列表 tree
	 * @return array
	 */
	public function actionGetCategories()
	{
		$result = ArticleService::instance()->getCategoryList();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $result);
	}

	/**
	 * @Desc: 分类管理 页面
	 */
	public function actionCategory()
	{
		$categories  = ArticleService::instance()->getCategoryList();
		$treeSelect = Helpers::getTreeSelect($categories);
		return $this->render('category', [
			'treeSelect' => $treeSelect,
		]);
	}

	/**
	 * @Desc: 添加分类
	 * @return array
	 */
	public function actionAddCategory()
	{
		$params = self::postParams();
		ArticleService::instance()->addCategory($params);
		return self::ajaxSuccess('添加成功');
	}

	/**
	 * @Desc: 更新分类
	 * @return array
	 */
	public function actionSaveCategory()
	{
		$params = self::postParams();
		ArticleService::instance()->saveCategory($params);
		return self::ajaxSuccess('更新成功');
	}

	/**
	 * @Desc: 删除分类
	 * @return array
	 */
	public function actionDeleteCategory()
	{
		$categoryId = self::postParams('id', 0);
		$moveArticle = self::postParams('move_article', 0);
		$deleteArticle = self::postParams('delete_article', 0);
		$moveToCategoryId = self::postParams('move_to_category_id', 0);
		if (($moveArticle + $deleteArticle) !== 1) {
			self::ajaxReturn('参数错误');
		}
		ArticleService::instance()->deleteCategory($categoryId, $moveArticle, $deleteArticle, $moveToCategoryId);
		return self::ajaxSuccess('删除成功');
	}
}