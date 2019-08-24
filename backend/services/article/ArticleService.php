<?php
/**
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/10/27
 * Time: 13:48
 */

namespace backend\services\article;


use backend\services\BaseService;
use common\exceptions\DatabaseException;
use common\exceptions\ParameterException;
use common\helpers\Helpers;
use common\models\AdminUser;
use common\models\Article;
use common\models\ArticleCategory;
use common\models\ArticleComment;
use common\models\ArticleContents;
use common\models\ArticleTag;
use Yii;

class ArticleService extends BaseService implements ArticleServiceInterface
{
	/**
	 * @Desc: 获取文章列表
	 * @return array
	 */
	public function getArticeList()
	{
		$query = Article::find()
			->alias('article')
			->where(['is_delete' => Article::IS_DELETE_NO]);

		list ($count, $page) = self::getPageAndSearch($query, Article::getSearchFieldByAction('index'));
		
		$articles = $query->select(Article::getListField())
			->leftJoin(['user' => AdminUser::tableName()], 'user.id = article.user_id')
			->leftJoin(['category' => ArticleCategory::tableName()], 'category.id = article.category_id')
			->orderBy('article.created_at desc')
			->asArray()
			->all();

		return [
			'total' => $count,
			'list' => $articles,
			'page' => $page,
		];
	}

	/**
	 * @Desc: 添加文章
	 * @param $params
	 * @throws \Exception
	 */
	public function addArtice($params)
	{
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$article = new Article();
			$article->load($params);
			if (!empty($params['release_time'])) {
				$article->created_at = $article->updated_at = strtotime($params['release_time']);
				$article->detachBehavior('TimestampBehavior');
			}
			$article->user_id = Yii::$app->user->id;
			if (empty($article->description)) {
				$article->description = mb_substr(strip_tags($params['content']), 0, 200, 'utf-8');
			} else {
				$article->description = strip_tags($article->description);
			}
			$article->saveModel($transaction);
			// 保存文章内容
			$content = new ArticleContents();
			$content->id = $article->id;
			$content->content = $params['content'];
			$content->markdown_content = $params['markdown_content'] ?? '';
			// 生成文章目录, 并为目录添加锚点
			list ($directory, $articleContent) = Helpers::createArticleDirectory($content->content);
			$content->content = $articleContent;
			$content->directory = $directory;
			
			$content->saveModel($transaction);
			// 添加标签
			if (!empty($article->keyword)) {
				foreach (explode(',', $article->keyword) as $tag) {
					Yii::$app->db->createCommand()->upsert(ArticleTag::tableName(), [
						'name' => $tag,
						'article_count' => 1,
						'is_show' => ArticleTag::IS_SHOW_YES,
						'created_at' => $article->created_at,
						'updated_at' => $article->created_at,
					], [
						'article_count' => new \yii\db\Expression('article_count + 1'),
						'updated_at' => time(),
					])->execute();
				}
			}

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}

	}

	/**
	 * @Desc: 更新文章
	 * @param $params
	 * @throws \Exception
	 */
	public function saveArtice($params)
	{
		$id = $params['id'] ?? 0;
		$transaction = Yii::$app->db->beginTransaction();
		try {
			// 更新文章内容
			$article = self::findArticleModel($id);
			$oldKeyword = $article->keyword;
			
			$article->load($params);
			if (empty($article->description)) {
				$article->description = mb_substr(strip_tags($params['content']), 0, 200, 'utf-8');
			} else {
				$article->description = strip_tags($article->description);
			}
			$article->saveModel($transaction);
			// 保存文章内容
			$article->content->content = $params['content'];
			$article->content->markdown_content = $params['markdown_content'] ?? '';
			
			list ($directory, $content) = Helpers::createArticleDirectory($article->content->content);
			$article->content->content = $content;
			$article->content->directory = $directory;
			$article->content->saveModel($transaction);
			
			// 更新标签
			if (!empty($article->keyword)) {
				foreach (explode(',', $article->keyword) as $tag) {
					Yii::$app->db->createCommand()->upsert(ArticleTag::tableName(), [
						'name' => $tag,
						'article_count' => 1,
						'is_show' => ArticleTag::IS_SHOW_YES,
						'created_at' => $article->created_at,
						'updated_at' => $article->created_at,
					], [
						'article_count' => new \yii\db\Expression('article_count + 1'),
						'updated_at' => time(),
					])->execute();
				}
			}
			if (!empty($oldKeyword)) {
				foreach (explode(',', $oldKeyword) as $tag) {
					ArticleTag::updateAll(['article_count' => new \yii\db\Expression('article_count - 1')], "article_count > 0 and name = '$tag'");
				}
			}

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}

	/**
	 * @Desc: 更新文章简要信息
	 * @param $params
	 * @return mixed
	 */
	public function saveArticeBrief($params)
	{
		$id = $params['id'] ?? 0;
		$article = self::findArticleModel($id);
		$article->scenario = 'update-brief';
		$article->load($params);
		$article->saveModel();
	}


	/**
	 * @Desc: 删除文章
	 * @param $articleId
	 * @return mixed
	 */
	public function deleteArtice($articleId)
	{
		$article = self::findArticleModel($articleId);
		$article->scenario = 'delete';
		$article->is_delete = Article::IS_DELETE_YES;
		$article->saveModel();
	}

	/**
	 * @Desc: 删除文章 批量
	 * @param $articleIds
	 * @throws ParameterException
	 * @return mixed
	 */
	public function deleteArtices($articleIds)
	{
		$affectedRows = Article::updateAll(['is_delete' => Article::IS_DELETE_YES], ['id' => $articleIds]);
		if ($affectedRows === false) {
			throw new ParameterException(ParameterException::INVALID, '批量删除失败');
		}
	}

	/**
	 * @Desc: 移动某分类下的文章到某分类
	 * @param 被移动的分类ID $categoryId
	 * @param 移动到的分类ID $toCategoryId
	 * @throws ParameterException
	 * @return mixed
	 */
	public function changeCategoryByCategory($categoryId, $toCategoryId)
	{
		$category = self::findCategoryModel($categoryId);
		$affectedRows = Article::updateAll(['category_id' => $toCategoryId], "FIND_IN_SET(category_id, '{$category->parents}')");
		if ($affectedRows === false) {
			throw new ParameterException(ParameterException::INVALID, '更新文章分类出错');
		}
	}

	/**
	 * @Desc: 更新文章是否展示
	 * @param $articleId
	 * @return mixed
	 */
	public function changeIsShow($articleId)
	{
		$article = self::findArticleModel($articleId);
		$article->scenario = 'change-is_show';
		$article->is_show = $article->is_show == Article::IS_SHOW_YES ? Article::IS_SHOW_NO : Article::IS_SHOW_YES;
		$article->saveModel();
	}

	/**
	 * @Desc: 修改文章是否允许评论
	 * @param $articleId
	 * @return mixed
	 */
	public function changeIsAllowComment($articleId)
	{
		$article = self::findArticleModel($articleId);
		$article->scenario = 'change-is_allow_comment';
		$article->is_allow_comment = $article->is_allow_comment == Article::IS_ALLOW_COMMENT_YES ? Article::IS_ALLOW_COMMENT_NO : Article::IS_ALLOW_COMMENT_YES;
		$article->saveModel();
	}

	/**
	 * @Desc: 获取菜单列表 tree结构
	 * @return array
	 */
	public function getCategoryList()
	{
		$categories = ArticleCategory::find()
			->select(['label' => 'category_name', 'id', 'parent_id', 'sort_value'])
			->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();
		if (empty($categories)) {
			return [];
		}
		$data = [];
		foreach ($categories as $item) {
			$data[ $item['id'] ] = $item;
		}
		unset($categories);

		$result = Helpers::getTree($data);

		return $result;
	}

	/**
	 * @Desc: 添加分类
	 * @param $params
	 * @throws ParameterException
	 * @throws \Exception
	 */
	public function addCategory($params)
	{
		if (!isset($params['parent_id'])) {
			throw new ParameterException(ParameterException::INVALID);
		}
		$category = new ArticleCategory();
		$category->load($params);
		$category->parents = '';
		if ($category->parent_id != 0) {
			$parentCategory = self::findCategoryModel($category->parent_id);
			$category->parents = (string) $parentCategory->parents;
		}
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$category->saveModel($transaction);
			// 保存自己的 ID 到 parents
			if (empty($category->parents)) {
				$category->parents = (string) $category->id;
			} else {
				$category->parents = $category->id . ',' . $category->parents;
			}
			$category->saveModel($transaction);
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}

	/**
	 * @Desc: 更新分类信息
	 * @param $params
	 * @return mixed
	 */
	public function saveCategory($params)
	{
		$id = (int) ($params['id'] ?? 0);
		$category = self::findCategoryModel($id);
		$category->load($params);
		// 更新分类信息
		if ($category->parent_id == 0) {
			$category->parents = (string) $category->id;
 		} else {
			$parentCategory = self::findCategoryModel($params['parent_id']);
			$category->parents = $category->id . ',' . $parentCategory->parents;
		}
		$category->saveModel();
	}

	/**
	 * @Desc: 删除分类
	 * @param $categoryId
	 * @param $moveArticle 是否移动文章
	 * @param $deleteArticle 是否删除文章
	 * @param $moveToCategoryId 移动到分类的Id
	 * @return mixed
	 * @throws \Exception
	 */
	public function deleteCategory($categoryId, $moveArticle, $deleteArticle, $moveToCategoryId)
	{
		$category = self::findCategoryModel($categoryId);
		$transaction = Yii::$app->db->beginTransaction();

		try {
			if ($moveArticle == 1) { // 移动文章到某个分类下
				if (empty($moveToCategoryId)) {
					throw new ParameterException(ParameterException::INVALID);
				}
				// 移动分类下的文章到某分类
				$this->changeCategoryByCategory($categoryId, $moveToCategoryId);
			} elseif ($deleteArticle == 1) { // 删除该分类下的的文章
				Article::updateAll(['is_delete' => Article::IS_DELETE_YES], "FIND_IN_SET(category_id, '{$category->parents}')");
			} else {
				throw new ParameterException(ParameterException::INVALID);
			}
			// 删除该分类
			$category->delete();
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}
	
	/**
	 * 获取标签列表
	 * @return array
	 */
	public function getTagList()
	{
		$query = ArticleTag::find();
		
		list ($count, $page) = self::getPageAndSearch($query);
		
		$list = $query->orderBy('created_at desc')
			->asArray()
			->all();
		
		return [
			'total' => $count,
			'list' => $list,
			'page' => $page,
		];
	}
	
	/**
	 * 获取标签列表
	 * @return array
	 */
	public function getCommentList()
	{
		$query = ArticleComment::find();
		
		list ($count, $page) = self::getPageAndSearch($query);
		
		$list = $query->orderBy('created_at desc')
			->alias('comment')
			->select('comment.*, article.title as article_name')
			->leftJoin(['article' => Article::tableName()], 'article.id = comment.article_id')
			->asArray()
			->all();
		
		return [
			'total' => $count,
			'list' => $list,
			'page' => $page,
		];
	}
	
	
	/**
	 * Update Tag Is show
	 * @param $tagId
	 * @return mixed|void
	 * @throws ParameterException
	 * @throws \common\exceptions\DatabaseException
	 */
	public function changeTagIsShow($tagId)
	{
		$model = self::findArticleTagModel($tagId);
		$model->scenario = 'change-is_show';
		$model->is_show = $model->is_show == ArticleTag::IS_SHOW_YES ? ArticleTag::IS_SHOW_NO : ArticleTag::IS_SHOW_YES;
		$model->saveModel();
		Yii::$app->userCache->refresh('tagList');
	}

	//deleteTags
	/**
	 * @Desc: 删除标签
	 * @param $tagIds
	 * @throws ParameterException
	 * @return mixed
	 */
	public function deleteTags($tagIds)
	{
		$affectedRows = ArticleTag::deleteAll(['id' => $tagIds]);
		if ($affectedRows === false) {
			throw new ParameterException(ParameterException::INVALID, '删除失败');
		}
		Yii::$app->userCache->refresh('tagList');
	}
	
	/**
	 * Add Tag
	 * @param array $params
	 * @throws DatabaseException
	 */
	public function addTag(array $params)
	{
		$tag = new ArticleTag();
		$tag->name = trim($params['name']);
		$tag->article_count = 0;
		$result = $tag->save();
		if ($result == false) {
			throw new DatabaseException(DatabaseException::INSERT_ERROR, '添加失败');
		}
		Yii::$app->userCache->refresh('tagList');
	}
	
	public function deleteComments($ids)
	{
		$affectedRows = ArticleComment::updateAll(['is_delete' => ArticleComment::IS_DELETE_YES, 'is_read' => ArticleComment::IS_READ_YES], ['id' => $ids]);
		if ($affectedRows === false) {
			throw new ParameterException(ParameterException::INVALID, '删除失败');
		}
		Yii::$app->userCache->refresh('tagList');
	}
	
	/**
	 * @Desc: 获取文章Model
	 * @param $id
	 * @return Article
	 * @throws ParameterException
	 */
	protected static function findArticleModel($id)
	{
		$article = Article::findOne($id);
		if (empty($article)) {
			throw new ParameterException(ParameterException::INVALID, '文章不存在');
		}

		return $article;
	}
	
	/**
	 * @Desc: 获取标签Model
	 * @param $id
	 * @return ArticleTag
	 * @throws ParameterException
	 */
	protected static function findArticleTagModel($id)
	{
		$model = ArticleTag::findOne($id);
		if (empty($model)) {
			throw new ParameterException(ParameterException::INVALID, '标签不存在');
		}
		
		return $model;
	}

	/**
	 * @Desc: 获取分类Model
	 * @param $id
	 * @return ArticleCategory
	 * @throws ParameterException
	 */
	protected static function findCategoryModel($id)
	{
		$category = ArticleCategory::findOne($id);
		if (empty($category)) {
			throw new ParameterException(ParameterException::INVALID, '分类不存在');
		}

		return $category;
	}


}