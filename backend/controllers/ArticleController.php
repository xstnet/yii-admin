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
use common\models\Article;
use common\models\ArticleComment;
use yii\filters\VerbFilter;
use Yii;

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
						'add' => ['get'],
						'edit' => ['get'],
						'add-article' => ['post'],
						'get-articles' => ['get'],
						'save-article' => ['post'],
						'save-article-brief' => ['post'],
						'delete-article' => ['post'],
						'delete-articles' => ['post'],
						'add-categoy' => ['post'],
						'save-categoy' => ['post'],
						'save-categoy' => ['post'],
						'get-categories' => ['get'],
						'tags' => ['get'],
						'get-tags' => ['get'],
						'change-tag-status' => ['post'],
						'delete-tags' => ['post'],
						'add-tags' => ['post'],
						'comment' => ['get'],
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
		$categories  = ArticleService::instance()->getCategoryList();
		$treeSelect = Helpers::getTreeSelect($categories);
		return $this->render('index', [
			'treeSelect' => $treeSelect,
			'searchFields' => Article::getSearchFieldByAction('index'),
		]);
	}

	/**
	 * 发布文章 页面
	 * @return string
	 */
	public function actionAdd()
	{
		$categories  = ArticleService::instance()->getCategoryList();
		$treeSelect = Helpers::getTreeSelect($categories);
		$type = Yii::$app->request->get('type', 'markdown');
		$view = 'add';
		if ($type === 'html') {
			$view = 'add_uedit';
		}
		return $this->render($view, [
			'treeSelect' => $treeSelect,
		]);
	}
	
	/**
	 * 编辑文章 页面
	 * @param $id
	 * @param string $type
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionEdit($id, $type ='')
	{
		$model = Article::findOne($id);
		if (empty($model)) {
			self::ajaxReturn('文章不存在');
		}
		$categories  = ArticleService::instance()->getCategoryList();
		$treeSelect = Helpers::getTreeSelect($categories);
		$view = 'edit_html';
		if (!empty($model->content->markdown_content)) {
			$view = 'edit_markdown';
		}
		if (!empty($type)) {
			$view = 'edit_' . $type;
		}
		return $this->render($view, [
			'treeSelect' => $treeSelect,
			'model' => $model,
		]);
	}

	/**
	 * @Desc: 获取文章列表
	 * @return array
	 */
	public function actionGetArticles()
	{
		$result = ArticleService::instance()->getArticeList();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $result);
	}

	/**
	 * @Desc: 更新文章是否展示
	 * @return array
	 */
	public function actionChangeIsShow()
	{
		$id = self::postParams('id', 0);
		ArticleService::instance()->changeIsShow($id);
		return self::ajaxSuccess('更新成功');
	}

	/**
	 * @Desc: 更新文章是否允许评论
	 * @return array
	 */
	public function actionChangeIsAllowComment()
	{
		$id = self::postParams('id', 0);
		ArticleService::instance()->changeIsAllowComment($id);
		return self::ajaxSuccess('更新成功');
	}

	/**
	 * @Desc: 发布文章
	 * @return array
	 */
	public function actionAddArticle()
	{
		$params = self::postParams();
		ArticleService::instance()->addArtice($params);
		return self::ajaxSuccess('发布成功');
	}

	/**
	 * @Desc: 编辑文章
	 * @return array
	 */
	public function actionSaveArticle()
	{
		$params = self::postParams();
		ArticleService::instance()->saveArtice($params);
		return self::ajaxSuccess('更新成功');
	}

	/**
	 * @Desc: 删除文章
	 * @return array
	 */
	public function actionDeleteArticle()
	{
		$articleId = self::postParams('id', 0);
		ArticleService::instance()->deleteArtice($articleId);
		return self::ajaxSuccess('删除成功');
	}

	/**
	 * @Desc: 删除文章 批量
	 * @return array
	 */
	public function actionDeleteArticles()
	{
		$articleIds = self::postParams('ids', 0);
		ArticleService::instance()->deleteArtices($articleIds);
		return self::ajaxSuccess('删除成功');
	}

	/**
	 * @Desc: 快速编辑 保存
	 * @return array
	 */
	public function actionSaveArticleBrief()
	{
		$params = self::postParams();
		ArticleService::instance()->saveArticeBrief($params);
		return self::ajaxSuccess('更新成功');
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
	
	/********************************************标签管理****************/
	
	/**
	 * Display tags view
	 * @return string
	 */
	public function actionTags()
	{
		return $this->render('tags');
	}
	
	/**
	 * Ajax Get Tag List
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionGetTags()
	{
		$result = ArticleService::instance()->getTagList();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $result);
	}
	
	/**
	 * Ajax Change Tag is show
	 * @return array
	 * @throws \common\exceptions\DatabaseException
	 * @throws \common\exceptions\ParameterException
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionChangeTagStatus()
	{
		$tagId = self::postParams('id', 0);
		$result = ArticleService::instance()->changeTagIsShow($tagId);
		return self::ajaxSuccess('更新成功');
	}
	
	/**
	 * Ajax Delete Tags
	 * @return array
	 * @throws \common\exceptions\ParameterException
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionDeleteTags()
	{
		$tagIds = self::postParams('ids', 0);
		ArticleService::instance()->deleteTags($tagIds);
		return self::ajaxSuccess('删除成功');
	}
	
	/**
	 * Add Tag
	 * @return array
	 * @throws \common\exceptions\DatabaseException
	 * @throws \yii\base\InvalidConfigException
	 */
	public function actionAddTag()
	{
		$params = self::postParams();
		ArticleService::instance()->addTag($params);
		return self::ajaxSuccess('添加成功');
	}
	
	public function actionComment()
	{
		return $this->render('comment');
	}
	
	public function actionGetComments()
	{
		$result = ArticleService::instance()->getCommentList();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $result);
	}
	
	public function actionCommentReadAll()
	{
		$affectedRows = ArticleComment::updateAll(['is_read' => ArticleComment::IS_READ_YES], ['is_read' => ArticleComment::IS_DELETE_NO]);
		if ($affectedRows !== false) {
			return self::ajaxSuccess('操作成功');
		}
		return self::ajaxReturn('操作失败');
	}
	
	public function actionDeleteComments()
	{
		$ids = self::postParams('ids', 0);
		ArticleService::instance()->deleteComments($ids);
		return self::ajaxSuccess('删除成功');
	}
}