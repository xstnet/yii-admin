<?php
/**
 * Created by PhpStorm.
 * User: xstnet
 * Date: 2018/11/3
 * Time: 19:58
 */

namespace common\helpers;

use backend\services\setting\SettingService;
use common\models\Article;
use common\models\ArticleCategory;
use Yii;


class Cache
{
	public function get($name)
	{
		$ret = Yii::$app->cache->get($name);
		if ($ret === false) {
			$funName = 'get' . ucfirst($name);
			if (method_exists ($this, $funName)) {
				$ret = $this->$funName();
			}
		}

		return $ret;
	}

	public function set($key, $value)
	{
		Yii::$app->cache->set($key, $value);
	}

	public function refresh($name)
	{
		Yii::$app->cache->delete($name);
	}
	
	/**
	 * 获取系统设置
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 */
	public function getSetting()
	{
		$setting = SettingService::instance()->getSettingDataToCache();
		$this->set('setting', $setting);
		return $setting;
	}
	
	/**
	 * 获取文章分类
	 * @return array
	 */
	public function getArticleCategory() : array
	{
		$categoryList = ArticleCategory::find()
			->select(['id', 'category_name', 'parent_id'])
			->orderBy(['sort_value' => SORT_ASC])
			->indexBy('id')
			->asArray()
			->all();
		
		$this->set('articleCategory', $categoryList);
		
		return $categoryList;
	}
	
	/**
	 * 获取文章分类名字
	 * @param int $id
	 * @return string
	 */
	public function getArticleCategoryNameById(int $id) : string
	{
		static $categoryList = [];
		if (empty($categoryList)) {
			$categoryList = $this->get('articleCategory');
		}
		if (isset($categoryList[$id])) {
			return $categoryList[$id]['category_name'];
		}
		
		return '';
	}
	
	/**
	 * 获取分类树
	 * @return array
	 */
	public function getArticleCategoryTree() : array
	{
		$categoryList = $this->get('articleCategory');
		
		$categoryListTree = Helpers::getTree($categoryList);
		
		$this->set('ArticleCategoryTree', $categoryListTree);
		
		return $categoryListTree;
	}
	
	/**
	 * 获取最新文章
	 * @param int $num
	 * @return array
	 */
	public function getLatestArticle(int $num = 5) : array
	{
		$articleList = Article::find()
			->select(['id', 'title'])
			->orderBy(['created_at' => SORT_DESC])
			->limit($num)
			->asArray()
			->all();
		
		$this->set('latestArticle', $articleList);
		
		return $articleList;
	}

}