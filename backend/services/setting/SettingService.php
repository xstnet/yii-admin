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
use common\models\Menus;
use common\models\RolesPermissions;
use Yii;

class SettingService extends BaseService implements SettingServiceInterface
{
	/**
	 * @Desc: 获取菜单列表
	 * @param $onlyActive // 是否只获取启用菜单
	 * @return array|mixed
	 */
	public function getMenus($onlyActive = false)
	{
		$result = [];
		$query = Menus::find()
			->select(Menus::getList());
		if ($onlyActive) {
			$query->andWhere(['status' => Menus::STATUS_ACTIVE]);
		}

		$menus = $query->orderBy(['sort_value' => SORT_ASC])
			->asArray()
			->all();
		if (empty($menus)) {
			return $result;
		}
		$mapUrl = []; $mapId = [];
		$urlPreFix = Yii::$app->urlManager->createUrl('');
		foreach ($menus as $k => &$v) {
			$v['router'] = $v['url'];
			$v['url'] = $urlPreFix . $v['url'];
			$v['href'] = $urlPreFix . $v['href'];

			$mapUrl[ $v['url'] ] = $v;
			$mapId[ $v['id'] ] = $v;
		}
		unset($menus);

//		$result['mapUrl'] = $mapUrl;
		$result = Helpers::getTree($mapId);

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

}