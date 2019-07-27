<?php
/**
 *
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 19-7-25
 * Time: 下午3:37
 */

namespace frontend\controllers;


use common\exceptions\ParameterException;
use common\models\Article;
use common\models\ArticleComment;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\web\NotFoundHttpException;

class ArticleController extends BaseController
{
	/**
	 * Displays Article Content.
	 * @param int $id
	 * @return string
	 * @throws NotFoundHttpException
	 * @throws \yii\db\Exception
	 */
	public function actionIndex(int $id)
	{
		// 获取评论
		if (Yii::$app->request->isAjax) {
			$page = Yii::$app->request->get('page', 0);
			if ($page > 0) {
				return $this->getComments();
			}
		}
		
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
			'messageList' => [],
		]);
		
	}
	
	/**
	 * 发布评论
	 */
	public function actionRelease()
	{
		// 验证留言间隔, 1分钟内只能发一次
		$session = Yii::$app->session;
		$session->open();
		$lastPubAt = $session->get('comment_last_pub_at', 0);
		
		if ($lastPubAt > 0 && ($lastPubAt + 60) > time()) {
			exit("<script>alert('1分钟内只能发布一次哦!');history.go(-1)</script>");
		}
		$params = Yii::$app->request->post();
		$articleId = (int) ($params['article_id'] ?? 0);
		$article = Article::findOne($articleId);
		if (empty($article)) {
			throw new NotFoundHttpException('该文章不存在!');
		}
		
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$comment = new ArticleComment();
			$comment->article_id = $articleId;
			$comment->avatar = sprintf('/uploads/avatar/%d.jpg', rand(1, 5));
			$comment->nickname = HtmlPurifier::process(trim($params['nickname'] ?? ''));
			$comment->email = HtmlPurifier::process(trim($params['email'] ?? ''));
			$comment->content = trim($params['content'] ?? '');
			$comment->ip = Yii::$app->request->userIP;
			
			if (!$comment->save()) {
				$error = current($comment->getFirstErrors());
				throw new ParameterException(ParameterException::INVALID, $error);
			}
			
			$article->comment_count ++;
			$article->scenario = 'update-comment_count';
			if (!$article->save()) {
				throw new ParameterException(ParameterException::INVALID, '发布失败');
			}
			
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			exit("<script>alert('". $e->getMessage() ."');history.go(-1)</script>");
		}
		
		$session->set('comment_last_pub_at', time());
		
		exit("<script>alert('发布成功');location.href='/article-".$articleId.".html'</script>");
	}
	
	/**
	 * 获取文章评论列表
	 * @return string
	 */
	public function getComments()
	{
		$this->layout = false;
		
		$articleId = (int) Yii::$app->request->get('id', 0);
		
		$query = ArticleComment::find()->where(['article_id' => $articleId]);
		
		list ($count, $pages) = $this->getPage($query, 30);
		
		$commentList = $query->asArray()
			->all();

		return $this->renderContentFilter($this->render('get-comments', [
			'commentList' => $commentList,
			'pages' => $pages,
		]));
	}
}