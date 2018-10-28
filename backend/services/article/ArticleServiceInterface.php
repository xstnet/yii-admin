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
	public function getArticeList();

	public function addArtice();
	public function savedArtice();
	public function deleteArtice();

	public function changeCategoryByCategory();
	public function changeStatus();
	public function changeIsAllowComment();

	public function addCategory();
	public function saveCategory();
	public function deleteCategory();
	public function changeCategoryStatus();

}