<?php
/**
 * Desc: 待办事项
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-22
 * Time: 下午3:56
 */

namespace backend\controllers;

use backend\services\todo\TodoService;
use yii\filters\VerbFilter;
use Yii;

class TodoController extends AdminLogController
{
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'get-todos' => ['get'],
						'change-status' => ['post'],
						'delete-todo' => ['post'],
						'add-todo' => ['post'],
					],
				],
			]
		);
	}

	/**
	 * @Desc: 获取待办事项列表
	 */
	public function actionGetTodos()
	{
		$result = TodoService::instance()->getTodoList();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $result);
	}

	/**
	 * @Desc: 更新事项状态
	 */
	public function actionChangeStatus()
	{
		$id = Yii::$app->request->post('id', 0);
		TodoService::instance()->changeTodo($id);
		return self::ajaxSuccess('更新成功');
	}

	/**
	 * @Desc: 删除事项
	 */
	public function actionDeleteTodo()
	{
		$id = Yii::$app->request->post('id', 0);
		TodoService::instance()->deleteTodo($id);
		return self::ajaxSuccess('删除成功');
	}

	/**
	 * @Desc: 添加事项
	 */
	public function actionAddTodo()
	{
		$todo = Yii::$app->request->post();
		$ret = TodoService::instance()->addTodo($todo);
		return self::ajaxSuccess('添加成功', $ret);
	}

}