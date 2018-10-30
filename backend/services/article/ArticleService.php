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
		// TODO: Implement getArticeList() method.
	}

	/**
	 * @Desc: 添加文章
	 * @param $params
	 * @return mixed
	 */
	public function addArtice($params)
	{
		// TODO: Implement addArtice() method.
	}

	/**
	 * @Desc: 更新文章
	 * @param $params
	 * @return mixed
	 */
	public function savedArtice($params)
	{
		// TODO: Implement savedArtice() method.
	}

	/**
	 * @Desc: 删除文章
	 * @param $articleId
	 * @return mixed
	 */
	public function deleteArtice($articleId)
	{
		// TODO: Implement deleteArtice() method.
	}

	/**
	 * @Desc: 移动某分类下的文章到某分类
	 * @return mixed
	 */
	public function changeCategoryByCategory()
	{
		// TODO: Implement changeCategoryByCategory() method.
	}

	/**
	 * @Desc: 更新文章状态
	 * @param $articleId
	 * @return mixed
	 */
	public function changeStatus($articleId)
	{
		// TODO: Implement changeStatus() method.
	}

	/**
	 * @Desc: 修改文章是否允许评论
	 * @param $articleId
	 * @return mixed
	 */
	public function changeIsAllowComment($articleId)
	{
		// TODO: Implement changeIsAllowComment() method.
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
				Article::updateAll(['category_id' => $moveToCategoryId], "FIND_IN_SET(category_id, '{$category->parents}')");
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

	protected static function findCategoryModel($id)
	{
		$category = ArticleCategory::findOne($id);
		if (empty($category)) {
			throw new ParameterException(ParameterException::INVALID, '分类不存在');
		}

		return $category;
	}


}