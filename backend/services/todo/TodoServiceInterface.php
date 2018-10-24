<?php
/**
 * Created by PhpStorm.
 * User: xstnet
 * Date: 2018/10/23
 * Time: 13:38
 */

namespace backend\services\todo;


interface TodoServiceInterface
{
	/**
	 * 获取待办事项列表
	 * @return mixed
	 */
	public function getTodoList();

	/**
	 * 添加todo
	 * @param $todo
	 * @return mixed
	 */
	public function addTodo($todo);

	/**
	 * 改变状态
	 * @param $id
	 * @return mixed
	 */
	public function changeTodo($id);

	/**
	 * 删除todo
	 * @param $id
	 * @return mixed
	 */
	public function deleteTodo($id);
}