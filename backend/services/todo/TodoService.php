<?php
/**
 * Created by PhpStorm.
 * User: xstnet
 * Date: 2018/10/23
 * Time: 13:38
 */

namespace backend\services\todo;

use backend\services\BaseService;
use common\exceptions\ParameterException;
use common\models\Todolist;
use Yii;

class TodoService extends BaseService implements TodoServiceInterface
{
	/**
	 * 获取待办事项列表
	 * @return array
	 */
	public function getTodoList()
	{
		$userId = Yii::$app->user->id;
		$query = Todolist::find()
			->select(['id', 'name', 'status'])
			->where(['user_id' => $userId])
			->andWhere(['<>', 'status', Todolist::STATUS_DELETE]);

		list ($count, $page) = self::getPageAndSearch($query);

		$todoList = $query->orderBy(['created_at' => SORT_DESC])
			->asArray()
			->all();

		return [
			'total' => $count,
			'list' => $todoList,
			'page' => $page,
		];
	}

	/**
	 * 添加todo
	 * @param $todo
	 * @return mixed
	 */
	public function addTodo($todo)
	{
		$todoModel = new Todolist();
		$todo['status'] = Todolist::STATUS_UNDERWAY;
		$todoModel->load($todo, '');
		$todoModel->user_id = Yii::$app->user->getId() ?? 0;
		$todoModel->saveModel();
		return [
			'id' => $todoModel->id,
		];
	}

	/**
	 * 改变状态
	 * @param $id
	 * @return mixed
	 * @throws ParameterException
	 */
	public function changeTodo($id)
	{
		$todo = self::findModel($id);
		$todo->status = $todo->status == Todolist::STATUS_COMPLETED ? Todolist::STATUS_UNDERWAY : Todolist::STATUS_COMPLETED;
		$todo->saveModel();
	}

	/**
	 * 删除todo
	 * @param $id
	 * @return mixed
	 */
	public function deleteTodo($id)
	{
		$todo = self::findModel($id);
		$todo->status = Todolist::STATUS_DELETE;
		$todo->saveModel();
	}

	/**
	 * 获取 Todolist Model
	 * @param $id
	 * @return array|Todolist|null|\yii\db\ActiveRecord
	 * @throws ParameterException
	 */
	protected static function findModel($id)
	{
		$todo = Todolist::find()
			->where(['id'=>$id,])
			->andWhere(['<>', 'status', Todolist::STATUS_DELETE])
			->andWhere(['user_id' => Yii::$app->user->id])
			->one();
		if (!$todo) {
			throw new ParameterException(ParameterException::INVALID, '事项不存在');
		}

		return $todo;
	}

}