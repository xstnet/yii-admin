<?php
/**
 * Desc: 权限管理 RBAC 控制器
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/06/25
 * Time: 15:30
 */

namespace backend\controllers;


use backend\services\permission\PermissionService;
use backend\services\setting\SettingService;
use common\helpers\Helpers;
use yii\filters\VerbFilter;
use Yii;

class PermissionController extends AdminLogController
{
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'get-permissions' => ['get', ],
						'change-status' => ['post', ],
						'role-brief-list' => ['get', ],
						'get-roles' => ['get', ],
						'change-role-status' => ['post', ],
						'add-roles' => ['post', ],
						'save-roles' => ['post', ],
						'delete-roles' => ['post', ],
						'get-permission-tree' => ['get', ],
						'get-role-permissions' => ['get', ],
						'save-role-permissions' => ['post', ],
					],
				],
			]
		);
	}

	public function actions()
	{
		return [
			'role-brief-list' => [
				'class' => 'api\modules\v1\actions\permission\GeRoleBriefListAction', // 获取角色简要信息列表
			],
			'get-permission-tree' => [
				'class' => 'api\modules\v1\actions\permission\GetPermissionTreeAction', //
			],
		];
	}

	/* *************************   角色管理  *********************××****************************************************/

	/**
	 * @Desc: 获取角色列表
	 * @return array
	 */
	public function actionGetRoles()
	{
		$result = PermissionService::instance()->getRoles();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $result);
	}

	/**
	 * @Desc: 角色管理 页面
	 * @return string
	 */
	public function actionRoles()
	{
		$rolesTree = PermissionService::instance()->getPermissionTree();
		return $this->render('role', [
			'roles_tree' => $rolesTree
		]);
	}

	/**
	 * @Desc: 获取角色所拥有的权限
	 * @param $role_id
	 * @return array
	 */
	public function actionGetRolePermissions($role_id)
	{
		$ret = PermissionService::instance()->getRolePermissions($role_id);
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $ret);
	}

	/**
	 * @Desc: 保存角色权限
	 * @return array
	 */
	public function actionSaveRolePermissions()
	{
		$request = Yii::$app->request;
		$roleId = $request->post('role_id', 0);
		$permissions = $request->post('permissions', []);
		PermissionService::instance()->setRolePermissions(['permissions' => $permissions, 'role_id' => $roleId]);

		return self::ajaxSuccess('保存成功');
	}

	/**
	 * @Desc: 删除角色
	 * @return array
	 */
	public function actionDeleteRoles()
	{
		$roleId = Yii::$app->request->post('role_id', 0);
		$ret = PermissionService::instance()->deleteRoles($roleId);

		return self::ajaxSuccess('删除成功');
	}

	/**
	 * @Desc: 更新角色状态
	 * @return array
	 */
	public function actionChangeRoleStatus()
	{
		$roleId = Yii::$app->request->post('role_id', 0);
		PermissionService::instance()->changeRoleStatus($roleId);

		return self::ajaxSuccess('操作成功');
	}

	/**
	 * @Desc: 修改角色
	 * @return array
	 */
	public function actionSaveRoles()
	{
		$params = Yii::$app->request->post();
		PermissionService::instance()->saveRoles($params);
		return self::ajaxSuccess('保存成功');
	}

	/**
	 * @Desc: 添加角色
	 * @return array
	 */
	public function actionAddRoles()
	{
		$params = Yii::$app->request->post();
		PermissionService::instance()->addRoles($params);

		return self::ajaxSuccess('添加成功');
	}

	/* *************************   权限管理  *********************××****************************************************/

	/**
	 * @Desc: 权限管理 页面
	 * @return string
	 */
	public function actionPermissions()
	{
		$menus = PermissionService::instance()->getMeusBriefList();
		$menusTree = SettingService::instance()->getMenus();
		$treeSelect = Helpers::getTreeSelect($menusTree);
		return $this->render('permission', [
			'menus' => $menus,
			'treeSelect' => $treeSelect,
		]);
	}

	/**
	 * @Desc: 获取权限列表
	 * @return array
	 */
	public function actionGetPermissions()
	{
		$permissions = PermissionService::instance()->getPermission();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $permissions);
	}

	/**
	 * @Desc: 删除权限
	 * @return array
	 */
	public function actionDeletePermission()
	{
		$id = Yii::$app->request->post('id', 0);
		$ret = PermissionService::instance()->deletePermission($id);

		return self::ajaxSuccess('删除成功');
	}

	/**
	 * @Desc: 更新权限状态
	 * @return array
	 */
	public function actionChangeStatus()
	{
		$id = Yii::$app->request->post('id', 0);
		PermissionService::instance()->changePermissionStatus($id);

		return self::ajaxSuccess('操作成功');
	}

	/**
	 * @Desc: 修改权限
	 * @return array
	 */
	public function actionSavePermission()
	{
		$params = Yii::$app->request->post();
		PermissionService::instance()->savePermission($params);
		return self::ajaxSuccess('保存成功');
	}

	/**
	 * @Desc: 添加权限
	 * @return array
	 */
	public function actionAddPermission()
	{
		$params = Yii::$app->request->post();
		PermissionService::instance()->addPermission($params);

		return self::ajaxSuccess('添加成功');
	}

}