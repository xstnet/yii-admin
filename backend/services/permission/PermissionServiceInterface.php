<?php
/**
 * Desc: 权限管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/1
 * Time: 19:28
 */

namespace backend\services\permission;


interface PermissionServiceInterface
{

	/** 权限管理 */

	/**
	 * 获取权限列表
	 * @return mixed
	 */
	public function getPermission();

	/**
	 * 添加权限
	 * @param $params
	 * @return mixed
	 */
	public function addPermission($params);

	/**
	 * 删除权限节点
	 * @param $id
	 * @return mixed
	 */
	public function deletePermission(int $id);

	/**
	 * 修改权限
	 * @param $params
	 * @return mixed
	 */
	public function savePermission($params);

	/**
	 * 更改权限状态
	 * @param $id
	 * @return mixed
	 */
	public function changePermissionStatus(int $id);


	/** 角色管理 */

	/**
	 * 获取角色列表
	 * @return array
	 */
	public function getRoles();

	/**
	 * 修改角色
	 * @param $params
	 * @return mixed
	 */
	public function saveRoles($params);

	/**
	 * 添加角色
	 * @param $params
	 * @return mixed
	 */
	public function addRoles($params);

	/**
	 * 删除角色
	 * @param $id
	 * @return mixed
	 */
	public function deleteRoles(int $id);

}