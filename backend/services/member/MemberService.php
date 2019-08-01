<?php
/**
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/5/12
 * Time: 20:48
 */

namespace backend\services\member;


use backend\services\BaseService;
use common\exceptions\ParameterException;
use common\models\AdminUser;
use common\models\UsersRoles;
use Yii;

class MemberService extends BaseService implements MemberServiceInterface
{

	/**
	 * @Desc: 获取用户列表
	 * @return array
	 */
	public function getMemberList()
	{
		// 查询字段
		$userTableName = AdminUser::tableName();
		$field = array_merge(AdminUser::getListField(), ['GROUP_CONCAT(role.role_id) roles']);

		/**
		 * 构造查询
		 * @see listSql()
		 */
		$query = AdminUser::find();
		list ($count, $page) = self::getPageAndSearch($query);

		$users = $query->select($field)
			->leftJoin(['role' => UsersRoles::tableName()], "$userTableName.id = role.user_id")
			->groupBy($userTableName.'.id')
			->orderBy(["$userTableName.created_at" => SORT_DESC])
			->asArray()
			->all();
		foreach ($users as $k => $user) {
			$users[ $k ]['roles'] = empty($user['roles']) ? [] : explode(',', $user['roles']);
		}

		$result = [
			'total' => $count,
			'list' => $users,
			'page' => $page,
		];

		return $result;
	}

	public function changeStatus(int $id)
	{
		if ($id == Yii::$app->user->id) {
			throw new ParameterException(ParameterException::INVALID, '不能修改自己的角色');
		}
		$user = self::findModel($id);
		$user->scenario = 'change_status';
		$user->status = (int) $user->status === AdminUser::STATUS_DISABLED ? AdminUser::STATUS_ACTIVE : AdminUser::STATUS_DISABLED;
		$user->saveModel();
	}

	/**
	 * @desc 添加用户
	 * @param array $params
	 * @return array
	 * @throws \yii\base\Exception
	 * @throws \yii\db\Exception
	 */
	public function addMember(array $params)
	{
		/**
		 * 插入用户信息
		 */
		$user = new AdminUser();
		$user->scenario = AdminUser::SCENARIO_INSERT;
		$user->load($params, '');
		$user->setPassword($user->password);
		$user->rigister_ip = Yii::$app->request->getUserIP() ?? '';
		$user->login_count = 0;
//		$user->status = AdminUser::STATUS_ACTIVE;
		$user->nickname = empty($user->nickname) ? $user->username : $user->nickname;
		$user->avatar = Yii::$app->params['defaultAvatar'];

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$user->saveModel();
			$roles = $params['roles'] ?? [];
			/**
			 * 插入用户角色
			 */
			if (!empty($roles)) {
				self::addAll($roles, $user->id);
			}

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
		return array_merge($user->toArray(AdminUser::getPureListField()), [
			'roles' => $roles,
		]);
	}


	public function saveMember(array $params)
	{
		$user = self::findModel($params['id'] ?? 0);
		$user->scenario = AdminUser::SCENARIO_UPDATE;
		$password = $user->password;
		$user->load($params, '');
		if (empty($params['password'])) {
			$user->password = $password;
		} else {
			$user->setPassword($params['password']);
		}

		$transaction = Yii::$app->db->beginTransaction();
		try {
			$user->saveModel();
			$newRoles = $params['roles'] ?? [];
			$hasRoles = UsersRoles::find()
				->where(['user_id' => $user->id])
				->asArray()
				->all();
			$oldRoles = array_column($hasRoles, 'role_id');
			// 比较异同，增量插入和删除角色
			$needDeleteRoles = array_diff($oldRoles, $newRoles);
			$needAddRoles = array_diff($newRoles, $oldRoles);
			// 增量删除
			if (!empty($needDeleteRoles)) {
				$where = [
					'role_id' => $needDeleteRoles,
					'user_id' => $user->id,
				];
				UsersRoles::deleteAll($where);
			}
			// 增量插入
			if (!empty($needAddRoles)) {
				self::addAll($needAddRoles, $user->id);
			}

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}

		return array_merge($user->toArray(AdminUser::getPureListField()), [
			'roles' => $newRoles,
		]);
	}

	/**
	 * @Desc: 批量插入用户角色
	 * @param $roles
	 * @param $userId
	 * @return int
	 * @throws \yii\db\Exception
	 */
	protected static function addAll($roles, $userId)
	{
		$data = [];
		$time = time();
		foreach ($roles as $roleId) {
			$data[] = [
				'user_id' => $userId,
				'role_id' => $roleId,
				'created_at' => $time,
				'updated_at' => $time,
			];
		}
		$field = [
			'user_id','role_id','created_at','updated_at',
		];
		$command = Yii::$app->db->createCommand()->batchInsert(UsersRoles::tableName(), $field, $data);
		return $command->execute();
	}

	/**
	 * @Desc: 获取用户Model
	 * @param int $id
	 * @return AdminUser
	 * @throws ParameterException
	 */
	public static function findModel(int $id)
	{
		if ($id === 0) {
			throw new ParameterException(ParameterException::INVALID, '用户不存在');
		}
		$model = AdminUser::findOne($id);
		if (!$model) {
			throw new ParameterException(ParameterException::INVALID, '用户不存在');
		}
		return $model;
	}

	public function deleteMember(int $id)
	{
		/**
		 *  - 删除该用户
		 *  - 删除该用户拥有的角色
		 */
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$user = self::findModel($id);
			$user->deleteModel();
			UsersRoles::deleteAll(['user_id' => $id]);

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}

	/**
	 * This is getMemberList Sql Comments
	 * Only read
	 * @see getUserList()
	 */
	private function listSql()
	{
	/*
		SELECT
			`user`.id,
			`username`,
			`nickname`,
			`email`,
			`login_ip`,
			`login_at`,
			`login_count`,
			`user`.`status`,
			GROUP_CONCAT( role.role_id ) roles
		FROM
			`x_admin_user` `user`
			LEFT JOIN `x_pm_users_roles` `role` ON `user`.id = role.user_id
		GROUP BY
			`user`.id
		ORDER BY
			`user`.`created_at` DESC
			LIMIT 10
	*/
	}
}