<?php
/**
 * Desc: 用户管理
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-22
 * Time: 下午3:56
 */

namespace backend\controllers;

use common\exceptions\ParameterException;
use common\models\AdminUser;
use yii\filters\VerbFilter;
use Yii;

class UserController extends AdminLogController
{
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'profile' => ['get'],
						'save-user-profile' => ['post'],
					],
				],
			]
		);
	}

	public function actions()
	{
		return [

		];
	}

	/**
	 * @Desc: 个人信息，页面
	 * @return string
	 */
	public function actionProfile()
	{

		return $this->render('profile', [
		]);
	}

	/**
	 * @Desc: 更新个人信息
	 */
	public function actionSaveUserProfile()
	{
		$params = Yii::$app->request->post();
		$user = AdminUser::findOne(Yii::$app->user->id);
		if (empty($user)) {
			throw new ParameterException(ParameterException::INVALID, '用户不存在');
		}
		$user->avatar = $params['avatar'];
		if (empty($params['nickname'])) {
			throw new ParameterException(ParameterException::INVALID, '昵称不能为空');
		}
		if (!empty($params['password'])) {
			$user->setPassword($params['password']);
		}
		$user->email = $params['email'];
		$user->nickname = $params['nickname'];
		$user->saveModel();

		return self::ajaxSuccess('更新信息成功');
	}

}