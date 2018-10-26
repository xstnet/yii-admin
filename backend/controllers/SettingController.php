<?php
/**
 * Desc:
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/9/11
 * Time: 20:02
 */

namespace backend\controllers;


use backend\services\setting\SettingService;
use common\helpers\Helpers;
use Yii;

class SettingController extends AdminLogController
{
	public function actionMenus()
	{
		$menusTree  = SettingService::instance()->getMenus();
		$treeSelect = Helpers::getTreeSelect($menusTree);
		return $this->render('menus', [
			'treeSelect' => $treeSelect,
		]);
	}

	/**
	 * @Desc: 获取菜单列表
	 * @return array
	 */
	public function actionGetMenus()
	{
		$menus = SettingService::instance()->getMenus();

		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $menus);
	}

	/**
	 * @Desc: 获取活动菜单
	 * @return array
	 */
	public function actionGetActiveMenus()
	{
		$menus = SettingService::instance()->getActiveMenus();

		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $menus);
	}

	/**
	 * @Desc: 删除菜单
	 * @return array
	 */
	public function actionDeleteMenu()
	{
		$id = Yii::$app->request->post('id', 0);
		SettingService::instance()->deleteMenus($id);

		return self::ajaxSuccess('删除成功');
	}

	/**
	 * @Desc: 修改菜单
	 * @return array
	 */
	public function actionSaveMenu()
	{
		$params = Yii::$app->request->post();
		SettingService::instance()->saveMenus($params);
		return self::ajaxSuccess('更新成功');
	}

	/**
	 * @Desc: 添加菜单
	 * @return array
	 */
	public function actionAddMenu()
	{
		$params = Yii::$app->request->post();
		SettingService::instance()->addMenus($params);

		return self::ajaxSuccess('添加成功');
	}

	/**
	 * @Desc: 访问系统设置页面
	 */
	public function actionSetting()
	{
		list ($cagegories, $systemConfigs) = SettingService::instance()->getSystemConfigs();
		return $this->render('setting', [
			'categories' => $cagegories,
			'systemConfigs' => $systemConfigs,
		]);
	}

	/**
	 * @Desc: 保存系统设置
	 * @return array
	 */
	public function actionSaveSetting()
	{
		$params = Yii::$app->request->post();
		SettingService::instance()->saveSetting($params);
		return self::ajaxSuccess('保存成功');
	}

	public function actionAddSetting()
	{
		$params = Yii::$app->request->post();
		SettingService::instance()->addSetting($params);
		return self::ajaxSuccess('添加成功');
	}
}