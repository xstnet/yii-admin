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
	 */
	public function addArtice($params);

	/**
	 * @Desc: 更新文章
	 * @param $params
	 */
	public function saveArtice($params);

	/**
	 * @Desc: 更新文章简要信息
	 * @param $params
	 * @return mixed
	 */
	public function saveArticeBrief($params);

	/**
	 * @Desc: 删除文章
	 * @param $articleId
	 * @return mixed
	 */
	public function deleteArtice($articleId);

	/**
	 * @Desc: 删除文章 批量
	 * @param $articleIds
	 * @return mixed
	 */
	public function deleteArtices($articleIds);

	/**
	 * @Desc: 移动某分类下的文章到某分类
	 * @param $categoryId 被移动的分类ID
	 * @param $toCategoryId 移动到的分类ID
	 * @return mixed
	 */
	public function changeCategoryByCategory($categoryId, $toCategoryId);

	/**
	 * @Desc: 更新文章是否展示
	 * @param $articleId
	 * @return mixed
	 */
	public function changeIsShow($articleId);

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