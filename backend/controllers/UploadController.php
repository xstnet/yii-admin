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

class UploadController extends AdminLogController
{
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'image' => ['post'],
					],
				],
			]
		);
	}


	public function actionImage()
	{
		echo 1;
	}

}