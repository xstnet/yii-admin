<?php
/**
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/5/12
 * Time: 20:48
 */

namespace backend\services\setting;


use backend\services\BaseService;
use common\exceptions\ParameterException;
use common\helpers\Helpers;
use common\models\Config;
use common\models\ConfigCategory;
use common\models\Menus;
use common\models\Roles;
use common\models\RolesPermissions;
use common\models\UsersRoles;
use Yii;

class SettingService extends BaseService implements SettingServiceInterface
{
	/**
	 * @Desc: 获取菜单列表
	 * @return array
	 */
	public function getMenus()
	{
		$menus = Menus::find()
			->select(Menus::getList())
			->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();
		if (empty($menus)) {
			return [];
		}

		$data = self::getMenuTreeByData($menus);

		$result = Helpers::getTree($data);

		return $result;
	}

	protected static function getMenuTreeByData($menus)
	{
		$mapId = [];
		$urlPreFix = Yii::$app->urlManager->createUrl('');
		foreach ($menus as $k => &$v) {
			$v['router'] = $v['url'];
			$v['url'] = $urlPreFix . $v['url'];
			$v['href'] = $urlPreFix . $v['href'];

			$mapId[ $v['id'] ] = $v;
		}

		return $mapId;
	}

	public function getActiveMenus()
	{
		$result = [
			//			[
			//				'href' => Yii::$app->urlManager->createUrl('site/welcome'),
			//				'icon' => 'layui-icon-home',
			//				'label' => '控制台',
			//				'parent_id' => '0',
			//				'sort_value' => '1',
			//				'url' => Yii::$app->urlManager->createUrl('site/welcome'),
			//			]
		];
		$menusPermission = UsersRoles::find()
			->select(['role_permission.permission_id'])
			->alias('user_role')
			->leftJoin(['role' => Roles::tableName()], 'role.id = user_role.role_id')
			->leftJoin(['role_permission' => RolesPermissions::tableName()], 'user_role.role_id = role_permission.role_id')
			->where(['user_role.user_id' => Yii::$app->user->id])
			->andWhere(['role.status' => Roles::STATUS_ACTIVE])
			->andWhere(['role_permission.permission_type' => RolesPermissions::PERMISSION_TYPE_MENU])
			->asArray()
			->all();
		if (empty($menusPermission)) {
			return [];
		}
		$menuIdList = [];
		foreach ($menusPermission as $item) {
			$menuIdList[ $item['permission_id'] ] = $item['permission_id'];
		}
		$menuIdList = array_values($menuIdList);
		$menus = Menus::find()
			->select(Menus::getList())
			->where(['status' => Menus::STATUS_ACTIVE])
			->andWhere(['id' => $menuIdList])
			->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();
		if (empty($menus)) {
			return [];
		}

		$data = self::getMenuTreeByData($menus);

		$result = array_merge($result, Helpers::getTree($data));

		return $result;
	}

	/**
	 * @Desc: 更新菜单
	 * @param $params
	 * @return mixed
	 */
	public function saveMenus($params)
	{
		$id = (int) ($params['id'] ?? 0);
		$menu = self::findModel($id);
		$menu->scenario = Menus::SCENARIO_UPDATE;
		$menu->load($params, '');
		$menu->saveModel();
	}

	/**
	 * @Desc: 添加菜单
	 * @param $params
	 * @return mixed
	 */
	public function addMenus($params)
	{
		$menu = new Menus();
		$menu->load($params, '');
		$menu->scenario = Menus::SCENARIO_INSERT;
		$menu->saveModel();
	}

	/**
	 * @Desc: 删除菜单
	 * @param $id
	 * @throws \Exception
	 */
	public function deleteMenus($id)
	{
		/**
		 *  - 删除该菜单
		 *  - 删除角色所拥有的该菜单权限
		 */
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$menu = self::findModel((int) $id);
			$menu->deleteModel();
			RolesPermissions::deleteAll(['permission_id' => $id, 'permission_type' => RolesPermissions::PERMISSION_TYPE_MENU]);

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
		}
	}

	/**
	 * @Desc: Get Model
	 * @param int $id
	 * @return Menus
	 * @throws ParameterException
	 */
	protected static function findModel(int $id)
	{
		if ($id === 0) {
			throw new ParameterException(ParameterException::INVALID, '菜单不存在');
		}
		$menu = Menus::findOne($id);
		if (!$menu) {
			throw new ParameterException(ParameterException::INVALID, '菜单不存在');
		}

		return $menu;
	}

	/**
	 * 获取系统设置和分类
	 * @return array
	 */
	public function getSystemConfigs()
	{
		$categories = ConfigCategory::find()
			->where(['status' => ConfigCategory::STATUS_ACTIVE])
			->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();
		$mapIdToCategory = []; $systemConfigs = [];
		foreach ($categories as $category) {
			$mapIdToCategory[ $category['id'] ] = $category;
			$systemConfigs[ $category['id'] ] = [];
		}

		$configs = Config::find()
			->where(['status' => Config::STATUS_ACTIVE])
			->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();
		foreach ($configs as $item) {
			// 没有对应的分类，不显示该设置
			if (!isset($mapIdToCategory[ $item['category_id'] ])) {
				continue;
			}
			$systemConfigs[ $item['category_id'] ][] = $item;
		}
		unset($categories, $configs);

		return [ $mapIdToCategory, $systemConfigs ];
	}

	/**
	 * @Desc: 保存系统设置
	 * @param $params
	 */
	public function saveSetting($params)
	{
		$settings = $params['setting'];
		foreach ($settings as $id => $item) {
			$set = Config::findOne($id);
			if (!isset($item['value'])) {
				$item['value'] = ''; // 复选框都未选中时，会没有value传过来
			}
			if (empty($set)) {
				continue;
			}
			$set->code = $item['code'];
			// 复选框的值，为数组
			if (is_array($item['value'])) {
				$set->value = join(',', $item['value']);
			} else {
				$set->value = $item['value'];
			}

			$set->saveModel();
		}
		Yii::$app->userCache->refresh('setting');
	}

	/**
	 * @Desc 添加配置项
	 * @param $params
	 */
	public function addSetting($params)
	{
		$setting = new Config();
		$setting->load($params, '');
		$setting->saveModel();
		Yii::$app->userCache->refresh('setting');
	}

	/**
	 * 获取系统设置数据 存储缓存
	 * @return array
	 */
	public function getSettingDataToCache()
	{
		$categories = ConfigCategory::find()
			->where(['status' => ConfigCategory::STATUS_ACTIVE])
			->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();

		$configs = Config::find()
			->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();

		$mapIdToCategory = []; $systemConfigs = [];
		foreach ($categories as $category) {
			$mapIdToCategory[ $category['id'] ] = $category;
		}


		foreach ($configs as $item) {
			// 没有对应的分类，不显示该设置
			if (!isset($mapIdToCategory[ $item['category_id'] ])) {
				continue;
			}
			$systemConfigs[ $mapIdToCategory[ $item['category_id'] ]['code'] ][ $item['code'] ] = $item;
		}

		unset($categories, $configs);

		return $systemConfigs;
	}
}