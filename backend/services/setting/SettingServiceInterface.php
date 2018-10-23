<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/12
 * Time: 20:49
 */

namespace backend\services\setting;


interface SettingServiceInterface
{

	/**
	 * @Desc: 获取菜单
	 * @return mixed
	 */
	public function getMenus();

	/**
	 * @Desc: 更新菜单
	 * @param $params
	 * @return mixed
	 */
	public function saveMenus($params);

	/**
	 * @Desc: 添加菜单
	 * @param $params
	 * @return mixed
	 */
	public function addMenus($params);
}