<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/1
 * Time: 19:27
 */
namespace backend\services\permission;

use common\exceptions\ParameterException;
use common\helpers\Helpers;
use common\models\Menus;
use common\models\Permissions;
use common\models\Roles;
use common\models\RolesPermissions;
use common\models\UsersRoles;
use backend\services\BaseService;
use Yii;

class PermissionService extends BaseService implements PermissionServiceInterface
{
	protected static $actionKey = '_p'; // Permission
	protected static $menuKey = '_m'; // Menu
	/**
	 * 获取权限列表
	 * @return mixed
	 */
	public function getPermission()
	{
		$query =Permissions::find()
			->select(Permissions::getListField());

		list ($count, $page) = self::getPageAndSearch($query);

		$permissions = $query->asArray()
			->orderBy(['menu_id' => SORT_ASC, 'created_at' => SORT_DESC])
			->all();

		return [
			'total' => $count,
			'list' => $permissions,
			'page' => $page,
		];

	}

	public static function getKey()
	{
		return 'KEY_'. rand(1, 9999);
	}

	/**
	 * 添加权限
	 * @param $params
	 * @return mixed
	 */
	public function addPermission($params)
	{
		$model = new Permissions();
		$model->load($params);
		$model->scenario = Permissions::SCENARIO_INSERT;
		$model->saveModel();
	}

	/**
	 * 删除权限节点
	 * @param $id
	 * @return mixed
	 */
	public function deletePermission(int $id)
	{
		/**
		 *  - 删除该权限
		 *  - 删除角色所拥有的该权限
		 */
		$transaction = Yii::$app->db->beginTransaction();

		try {
			$model = Permissions::findModel($id);
			$model->deleteModel();
			RolesPermissions::deleteAll(['permission_type' => RolesPermissions::PERMISSION_TYPE_ACTION, 'permission_id' => $id]);

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}

	/**
	 * 修改权限
	 * @param $params
	 * @return mixed
	 */
	public function savePermission($params)
	{
		$id = (int) ($params['id'] ?? 0);
		$model = Permissions::findModel($id);
		$model->scenario = Permissions::SCENARIO_UPDATE;
		$model->load($params);
		$model->saveModel();
	}

	/**
	 * @Desc: 更改权限状态
	 * @param int $id
	 * @throws \common\exceptions\DatabaseException
	 */
	public function changePermissionStatus(int $id)
	{
		$model = Permissions::findModel($id);
		$model->status = (int) $model->status === Permissions::STATUS_DISABLED ? Permissions::STATUS_ACTIVE : Permissions::STATUS_DISABLED;
		$model->saveModel();
	}

	/**
	 * 获取角色列表
	 * @return array
	 */
	public function getRoles()
	{
		$query = $roles = Roles::find();

		list ($count, $page) = self::getPageAndSearch($query);

		$list = $query->select(Roles::getListField())
			->orderBy(['sort_value' => SORT_ASC, 'created_at' => SORT_DESC, ])
			->asArray()
			->all();

		return [
			'total' => $count,
			'list' => $list,
			'page' => $page,
		];
	}

	/**
	 * 获取菜单列表
	 * @return mixed
	 */
	public function getMeusBriefList()
	{
		$roles = Menus::find()
			->select(['name', 'id', 'parent_id',])
//			->where(['status' => Roles::STATUS_ACTIVE])
			->orderBy(['sort_value' => SORT_ASC, 'created_at' => SORT_DESC, ])
			->asArray()
			->all();

		$result = [];
		foreach ($roles as $v) {
			$result[ $v['id'] ] = $v;
		}
		$result['0'] = [
			'name' => '其他',
			'id' => '0',
		];

		return $result;
	}

	/**
	 * 获取菜单列表
	 * @return mixed
	 */
	public function getRolesBriefList()
	{
		$roles = Roles::find()
			->select(['name', 'id',])
			//			->where(['status' => Roles::STATUS_ACTIVE])
			->orderBy(['sort_value' => SORT_ASC, 'created_at' => SORT_DESC, ])
			->asArray()
			->all();

		return $roles;
	}

	public function changeRoleStatus(int $id)
	{
		$role = self::getRoleModel($id);
		$role->status = (int) $role->status === Roles::STATUS_ACTIVE ? Roles::STATUS_DISABLED : Roles::STATUS_ACTIVE;
		$role->saveModel();
	}

	/**
	 * 修改角色
	 * @param $params
	 * @return mixed
	 */
	public function saveRoles($params)
	{
		$id = (int) ($params['id'] ?? 0);
		$model = self::getRoleModel($id);
//		$model->scenario = Permissions::SCENARIO_UPDATE;
		$model->load($params, '');
		$model->saveModel();
		return $model->toArray(Roles::getListField());
	}

	/**
	 * 添加角色
	 * @param $params
	 * @return mixed
	 */
	public function addRoles($params)
	{
		$model = new Roles();
		$model->load($params, '');
//		$model->scenario = Permissions::SCENARIO_INSERT;
		$model->saveModel();
		return $model->toArray(Roles::getListField());
	}

	/**
	 * 删除角色
	 * @param $id
	 * @return mixed
	 */
	public function deleteRoles(int $id)
	{
		/**
		 *  - 删除该角色
		 *  - 删除该角色拥有的权限
		 *  - 删除拥有该角色的用户
		 */
		$transaction = Yii::$app->db->beginTransaction();
		try {
			// 删除该角色
			$model = self::getRoleModel($id);
			$model->deleteModel();
			// 删除该角色拥有的权限
			RolesPermissions::deleteAll(['role_id' => $id]);
			UsersRoles::deleteAll(['role_id' => $id]);

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}

	}

	/**
	 * @Desc:
	 * @param int $id
	 * @return Roles
	 * @throws ParameterException
	 */
	public static function getRoleModel(int $id)
	{
		if ($id === 0) {
			throw new ParameterException(ParameterException::INVALID, '角色不存在');
		}

		$role = Roles::findOne($id);
		if (!$role) {
			throw new ParameterException(ParameterException::INVALID, '角色不存在');
		}

		return $role;
	}

	public function getPermissionTree()
	{
		/**
		 * 菜单权限 & 操作权限，放在一起
		 */
		$menus = Menus::find()
			->asArray()
			->all();

		$permissions = Permissions::find()
			->asArray()
			->all();
		$menulist = [];
		foreach ($menus as $menu) {
			$key = $menu['id'] . self::$menuKey;
			$menulist[ $menu['id'] ] = [
				'parent_id' => $menu['parent_id'],
				'label' => $menu['name'],
				'key' => $key,
				'id' => $menu['id'],
				'value' => $key,
			];
		}
		// 所有不属于菜单权限列表中的，全都放在了其他里面
		$other = [
			'key' => 'other',
			'label' => '其他',
			'value' => 'key',
			'children' => [],
		];
		foreach ($permissions as $v) {
			$key = $v['id'] . self::$actionKey;
			if (isset($menulist[ $v['menu_id'] ])) {
				$menulist[ $v['menu_id'] ]['children'][] = [
					'id' => $v['id'],
					'key' => $key,
					'label' => $v['name'],
					'value' => $key,
				];
			} else {
				$other['children'][] = [
					'id' => $v['id'],
					'key' => $key,
					'label' => $v['name'],
					'value' => $key,
				];
			}
		}

		$permissonTree = Helpers::getTree($menulist, 'parent_id', 'children');
		if (!empty($other['children'])) {
			$permissonTree[] = $other;
		}

		return $permissonTree;
	}

	/**
	 * 获取角色所拥有的权限列表
	 * @param int $roleId
	 * @return array
	 */
	public function getRolePermissions(int $roleId)
	{
		$result = [];
		$list = RolesPermissions::find()
			->where(['role_id' => $roleId])
			->asArray()
			->all();
		if (empty($list)) {
			return $result;
		}
		foreach ($list as $value) {
			if ($value['permission_type'] == RolesPermissions::PERMISSION_TYPE_ACTION) {
				$result[] = $value['permission_id'] . self::$actionKey;
			} else {
				$result[] = $value['permission_id'] . self::$menuKey;
			}
		}

		return $result;
	}

	/**
	 * 设置角色权限
	 * @param array $params
	 * @throws ParameterException
	 * @throws \Exception
	 */
	public function setRolePermissions(array $params)
	{
		$permissions = (array) ($params['permissions'] ?? []);
		$roleId = (int) $params['role_id'] ?? 0;
		if ($roleId === 0) {
			throw new ParameterException(ParameterException::INVALID, '角色不存在');
		}
		$transaction = Yii::$app->db->beginTransaction();
		try {
			// 查询角色已拥有的权限
			$hasPermission = RolesPermissions::find()
				->where(['role_id' => $roleId])
				->asArray()
				->all();
			$oldPermissions = [];
			$mapPermissionKeyToId = [];
			foreach ($hasPermission as $item) {
				$key = $item['permission_id'] . ($item['permission_type'] == RolesPermissions::PERMISSION_TYPE_ACTION ? self::$actionKey : self::$menuKey);
				$oldPermissions[] = $key;
				$mapPermissionKeyToId[ $key ] = $item['id'];
			}
			unset($hasPermission);
			// 比较异同，增量插入和删除角色
			$needDeletePermissions = array_diff($oldPermissions, $permissions);
			$needAddPermissions = array_diff($permissions, $oldPermissions);
			// 增量删除
			if (!empty($needDeletePermissions)) {
				$permissionIdArray = [];
				foreach ($needDeletePermissions as $key) {
					$permissionIdArray[] = $mapPermissionKeyToId[ $key ];
				}
				$where = [
					'id' => $permissionIdArray,
				];
				RolesPermissions::deleteAll($where);
			}
			// 增量插入
			if (!empty($needAddPermissions)) {
				$data = [];
				$time = time();
				// 构建增量插入数据
				foreach ($needAddPermissions as $item) {
					$permissionId = (int) $item;
					if ($permissionId === 0) {
						continue;
					}
					if (strpos($item, self::$menuKey) !== false) { // 菜单权限
						$permissionType = RolesPermissions::PERMISSION_TYPE_MENU;
					} elseif (strpos($item, self::$actionKey) !== false) { // 操作权限
						$permissionType = RolesPermissions::PERMISSION_TYPE_ACTION;
					} else {
						continue;
					}
					$data[] = [
						'role_id' => $roleId,
						'permission_id' => $permissionId,
						'permission_type' => $permissionType,
						'created_at' => $time,
						'updated_at' => $time,
					];
				}
				if (!empty($data)) {
					$field = ['role_id', 'permission_id', 'permission_type', 'created_at', 'updated_at'];
					$command = Yii::$app->db->createCommand()->batchInsert(RolesPermissions::tableName(), $field, $data);
					$command->execute();
				}
			}

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}


	}

}