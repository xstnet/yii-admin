<?php
/**
 * Desc:
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/9/10
 * Time: 18:26
 */

namespace backend\controllers;
use common\models\Permissions;
use common\models\Roles;
use common\models\RolesPermissions;
use common\models\SystemLog;
use common\models\UsersRoles;
use Yii;

class AdminLogController extends BaseController
{
	public static $logTitle = '';

	public function init()
	{
		if (Yii::$app->user->isGuest) {
			if (Yii::$app->request->isAjax) {
				exit(json_encode(self::ajaxReturn('登录已过期, 请重新登录')));
			}
			$url = Yii::$app->urlManager->createUrl('login/index');
			header("Location: $url");
			die;
		}
	}

	public function beforeAction($action)
	{
		parent::beforeAction($action); // TODO: Change the autogenerated stub
		// 检查权限
		if (!self::checkPermission()) {
			if (Yii::$app->request->isAjax) {
				$ret = self::ajaxReturn(self::AJAX_MESSAGE_NO_PERMISSION, 1);
				Yii::$app->response->data = $ret;
				Yii::$app->response->send();
				return false;
			}
			$this->redirect(['site/no-permission']);
			return false;
		}
		// 保存操作日志
		self::saveAdminLog();

		return true;
	}

	/**
	 * @Desc: 检查操作权限
	 * @return bool
	 */
	public static function checkPermission()
	{
		$currentRoute = Yii::$app->request->get('r');
		if (empty($currentRoute)) {
			return true;
		}
		// 查询是否有此权限设置
		$permisson = Permissions::find()
			->where(['url' => $currentRoute, 'status' => Permissions::STATUS_ACTIVE])
			->asArray()
			->one();
		if (empty($permisson)) {
			return true;
		}
		// 操作记录的title
		self::$logTitle = $permisson['description'];
		//验证当前用户是否有此权限
		$hasPermission = UsersRoles::find()
			->select(['user_role.id'])
			->alias('user_role')
			->leftJoin(['role' => Roles::tableName()], 'role.id = user_role.role_id')
			->leftJoin(['role_permission' => RolesPermissions::tableName()], 'user_role.role_id = role_permission.role_id')
			->where(['user_role.user_id' => Yii::$app->user->id])
			->andWhere(['role.status' => Roles::STATUS_ACTIVE])
			->andWhere(['role_permission.permission_type' => RolesPermissions::PERMISSION_TYPE_ACTION])
			->andWhere(['role_permission.permission_id' => $permisson['id']])
			->exists();
		if ($hasPermission) {
			unset($hasPermission, $permisson, $currentRoute);
			return true;
		}
		return false;
	}

	/**
	 * @Desc: 保存操作日志
	 * @param array $data
	 * @return bool
	 */
	public static function saveAdminLog($data = [])
	{
		$request = Yii::$app->request;

		$moduleId = Yii::$app->controller->module->id;
		$route = strtolower(sprintf('%s/%s', Yii::$app->controller->id, Yii::$app->controller->action->id));
		if (empty(self::$logTitle)) {
			$title = Yii::$app->params['actionTitle'][ $moduleId ][ $route ] ?? '';
			if (empty($title)) {
				return true;
			}
		} else {
			$title = self::$logTitle;
		}

		$params = [
			'GET' => $request->get(),
			'POST' => $request->post(),
		];
		unset($params['GET']['_csrf_token_backend_xstnet']);
		// 密码不显示出来
		if (isset($params['POST']['password']) && !empty($params['POST']['password'])) {
			$params['POST']['password'] = '**********';
		}
		$data = array_merge([
			'user_id' => (int) Yii::$app->user->id,
			'nickname' => (string) Yii::$app->user->identity->nickname,
			'title' => $title,
			'route' => $route,
			'url' => $request->getUrl(),
			'params' => json_encode($params),
			'ip' => $request->getUserIP(),
			'request_method' => $_SERVER['REQUEST_METHOD'],
		], $data);

		$log = new SystemLog();
		$log->load($data, '');

		try {
			$log->saveModel();
			unset($log, $data, $params, $request, $title, $route, $moduleId);
		} catch (\Exception $e) {
			Yii::error('保存日志错误，错误原因'. $e->getMessage());
			Yii::error('日志数据: ');
			Yii::error($data);
		}
	}

}