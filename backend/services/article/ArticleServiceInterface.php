<?php
/**
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/10/27
 * Time: 13:48
 */

namespace backend\services\article;


interface ArticleServiceInterface
{
	/**
	 * @Desc: 获取文章列表
	 * @return array
	 */
	public function getArticeList();

	/**
	 * @Desc: 添加文章
	 * @param $params
	 * @return mixed
	 */
	public function addArtice($params);

	/**
	 * @Desc: 更新文章
	 * @param $params
	 * @return mixed
	 */
	public function savedArtice($params);

	/**
	 * @Desc: 删除文章
	 * @param $articleId
	 * @return mixed
	 */
	public function deleteArtice($articleId);

	/**
	 * @Desc: 移动某分类下的文章到某分类
	 * @return mixed
	 */
	public function changeCategoryByCategory();

	/**
	 * @Desc: 更新文章状态
	 * @param $articleId
	 * @return mixed
	 */
	public function changeStatus($articleId);

	/**
	 * @Desc: 修改文章是否允许评论
	 * @param $articleId
	 * @return mixed
	 */
	public function changeIsAllowComment($articleId);

	/**
	 * @Desc: 添加分类
	 * @param $params
	 */
	public function addCategory($params);

	/**
	 * @Desc: 更新分类信息
	 * @param $params
	 * @return mixed
	 */
	public function saveCategory($params);

	/**
	 * @Desc: 删除分类
	 * @param $categoryId
	 * @param $moveArticle 是否移动文章
	 * @param $deleteArticle 是否删除文章
	 * @param $moveToCategoryId 移动到分类的Id
	 * @return mixed
	 */
	public function deleteCategory($categoryId, $moveArticle, $deleteArticle, $moveToCategoryId);


}