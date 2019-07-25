<?php
/**
 *
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 19-7-25
 * Time: 下午3:37
 */

namespace frontend\controllers;


use common\models\Article;
use Yii;
use yii\web\NotFoundHttpException;

class ArticleController extends BaseController
{
	/**
	 * Displays Article Content.
	 * @param int $id
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionIndex(int $id)
	{
		$article = Article::findOne($id);
		if (empty($article)) {
			throw new NotFoundHttpException('该文章不存在');
		}
		
		Yii::$app->db->createCommand()->update(Article::tableName(), ['hits' => $article->hits+1], 'id='.$article->id)->execute();
		
		$breadcrumb = [
			[
				'name' => '首页',
				'href' => '/',
			],
			[
				'name' => Yii::$app->userCache->getArticleCategoryNameById($article->category_id),
				'href' => "/category-{$article->category_id}.html",
			],
			[
				'name' => $article->title,
				'href' => "/article-{$article->id}.html",
			],
		];
		
		// 上一条数据
		$prevArticle = Article::find()
			->select(['id', 'title'])
			->where(['>', 'id', $article->id])
			->limit(1)
			->asArray()
			->one();
		
		// 下一条数据
		$nextArticle = Article::find()
			->select(['id', 'title'])
			->where(['<', 'id', $article->id])
			->limit(1)
			->asArray()
			->one();
		
		return $this->render('index', [
			'article' => $article,
			'breadcrumb' => $breadcrumb,
			'prevArticle' => $prevArticle,
			'nextArticle' => $nextArticle,
		]);
		
	}
}