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
use common\helpers\Helpers;
use common\models\Article;
use common\models\ArticleComment;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\web\NotFoundHttpException;

class ArticleController extends BaseController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'PageCache' => [
				'class' => '\common\helpers\ArticlePageCache',
				'only' => ['index'],
				'duration' => 3600,
				'enabled' => YII_ENV == 'prod',
				'variations' => [
					Yii::$app->request->isAjax,
					Yii::$app->request->get('page', 1),
					Yii::$app->request->get('id', 0),
					Yii::$app->request->get('debug', 0),
				],
				'dependency' => [
					'class' => 'yii\caching\DbDependency',
					'sql' => "SELECT `updated_at` FROM x_article where id = :id",
					'params' => [
						':id' => ((int) Yii::$app->request->get('id', 0)),
					]
				],
			],
		];
	}
	/**
	 * Displays Article Content.
	 * @param int $id
	 * @return string
	 * @throws NotFoundHttpException
	 * @throws \yii\db\Exception
	 */
	public function actionIndex(int $id)
	{
		$this->layout = 'article-main';
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
		
		$breadcrumb = $this->getArticleBreadcrumb($article);
		
		// 上一条数据
		$prevArticle = Article::find()
			->select(['id', 'title'])
			->where(['>', 'created_at', $article->created_at])
            ->andWhere(['is_delete' => Article::IS_DELETE_NO, 'is_show' => Article::IS_SHOW_YES])
            ->limit(1)
            ->orderBy('created_at desc')
			->asArray()
			->one();
		
		// 下一条数据
		$nextArticle = Article::find()
			->select(['id', 'title'])
            ->where(['<', 'created_at', $article->created_at])
            ->andWhere(['is_delete' => Article::IS_DELETE_NO, 'is_show' => Article::IS_SHOW_YES])
			->limit(1)
            ->orderBy('created_at desc')
			->asArray()
			->one();
		
		if (empty($article->content->directory))  {
			$this->layout = 'main';
		}
		
		return $this->render('index', [
			'article' => $article,
			'breadcrumb' => $breadcrumb,
			'prevArticle' => $prevArticle,
			'nextArticle' => $nextArticle,
		]);
		
	}
	
	/**
	 * 发布评论
	 */
	public function actionRelease()
	{
		if (strpos(Yii::$app->request->userIP, '5.188') !== false) {
		    Yii::info("5.188...........");
			exit("<script>alert('发布成功');location.href='/'</script>");
		}
		
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
		if ($article->is_allow_comment == Article::IS_DELETE_NO) {
			exit("<script>alert('该篇文章不支持评论!');history.go(-1)</script>");
		}
		
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$comment = new ArticleComment();
			$comment->article_id = $articleId;
			$comment->avatar = sprintf('/uploads/avatar/%d.jpg', rand(1, 5));
			$comment->nickname = HtmlPurifier::process(trim($params['nickname'] ?? ''));
			$comment->email = HtmlPurifier::process(trim($params['email'] ?? ''));
			$comment->content = trim($params['content'] ?? '');
			if (empty($comment->content)) {
                throw new ParameterException(ParameterException::INVALID, "请输入文章内容!");
            }
			if (mb_strlen($comment->content) < 2) {
                throw new ParameterException(ParameterException::INVALID, "最少输入2个文字!");
            }
			// 处理评论内容
			$this->processContent($comment);
            $comment->ip = Yii::$app->request->userIP; // set ip
            // 查询是否回复, 有的话就使用同一个头像
            $prevComment =
                ArticleComment::find()
                    ->where([
                            'article_id' => $articleId,
                            'email' => $comment->email,
                            'nickname' => $comment->nickname]
                    )
                    ->one();
            if ($prevComment) {
                $comment->avatar = $prevComment->avatar;
            }
            unset($prevComment);

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
		
		list ($count, $pages) = $this->getPage($query, 200);
		
		$commentList = $query->asArray()
            ->indexBy('id')
			->all();

		$commentTree = Helpers::getTree($commentList, 'reply_comment_id');

		return $this->renderContentFilter($this->render('get-comments', [
			'commentList' => $commentTree,
			'pages' => $pages,
		]));
	}

    /**
     * 处理评论内容
     *
     * @param $comment ArticleComment
     */
	public function processContent($comment)
    {
        $content = strip_tags($comment->content,'<br>');
        $content = str_replace(["\n", "<br>", "<br/>"], '[br]', $content);

        // 处理 @
        $isMatched = preg_match('/\[@.*?(?=#\d+])#(\d+)]/m', $content, $matches);
        if ($isMatched) {
            $replacement = ArticleComment::getCustomTag('$1', 'span', 'replay-to');
            $content = preg_replace('/\[(@.*?(?=#\d+]))#(\d+)]/m', $replacement, $content);

            $replyId = (int) $matches[1];
            $replyModel = ArticleComment::findOne($replyId);
            if (!empty($replyModel)) {
                $comment->reply_comment_id = $replyId;
                $comment->reply_comment_ids = ltrim($replyModel->reply_comment_ids . ',' . $replyId, ',');

                // 如果回复的层级超过最大层级, 使该回复 同被评论的上级
                if (count(explode(',', $replyModel->reply_comment_ids)) >= ArticleComment::MAX_REPLY_LEVEL) {
                    $comment->reply_comment_id = $replyModel->reply_comment_id;
                }

                // 查询上层中是否有自已的回复, 有的话就在本级
                if ($replyModel->reply_comment_ids != '') {
                    $hasComment =
                        ArticleComment::find()
                        ->where([
                            'id' => explode(',', $replyModel->reply_comment_ids),
                            'email' => $comment->email,
                            'nickname' => $comment->nickname]
                        )
                        ->exists();
                    if ($hasComment) {
                        $comment->reply_comment_id = $replyModel->reply_comment_id;
                    }
                }
            }
        }

        // todo send email

        $comment->content = $content;
    }

    /**
	 *
	 * @param Article $article
	 * @return array
	 */
	private function getArticleBreadcrumb($article)
	{
		
		return array_merge([
			[
				'name' => '首页',
				'href' => '/',
			]
		], $this->getAllCategoryBreadcrumb($article->category_id),
			[
				[
					'name' => $article->title,
					'href' => "/article-{$article->id}.html",
				],
			]
		);
	}
}