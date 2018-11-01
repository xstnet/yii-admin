<?php
/**
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/10/27
 * Time: 13:48
 */

namespace backend\services\article;


use backend\services\BaseService;
use common\exceptions\ParameterException;
use common\helpers\Helpers;
use common\models\AdminUser;
use common\models\Article;
use common\models\ArticleCategory;
use function foo\func;
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
			->where(['is_delete' => Article::IS_DELETE_NO]);

		list ($count, $page) = self::getPage($query);

		$articles = $query->alias('article')
			->select(Article::getListField())
			->leftJoin(['user' => AdminUser::tableName()], 'user.id = article.user_id')
			->leftJoin(['category' => ArticleCategory::tableName()], 'category.id = article.category_id')
			->orderBy('article.sort_value asc')
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
//		$titleStyle = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $str));
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$article = new Article();
			$article->load($params);
			if (empty($article->description)) {

				$article->description = mb_substr(preg_replace("/<[^>]+>/is", "", $params['content']), 0, 200, 'utf-8');
			} else {
//				$article->description = strip_tags($ar)
			}
			print_r($article->getAttributes());
			die;

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}

	}

	/**
	 * @Desc: 更新文章
	 * @param $params
	 * @return mixed
	 */
	public function saveArtice($params)
	{
		// TODO: Implement savedArtice() method.
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
		$article->is_delete = Article::IS_DELETE_YES;
		$article->saveModel();
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