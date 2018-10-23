<?php
/**
 * Desc: 用户管理
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-22
 * Time: 下午3:56
 */

namespace backend\controllers;

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
	 * @Desc: 用户管理，页面
	 * @return string
	 */
	public function actionProfile()
	{

		return $this->render('profile', [
		]);
	}

	public function actionSaveUserProfile()
	{

	}

}