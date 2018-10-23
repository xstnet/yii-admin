<?php
/**
 * Desc:
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/9/10
 * Time: 18:26
 */

namespace backend\controllers;
use Yii;

class GoodsController extends AdminLogController
{
	public function actionIndex()
	{
		return $this->render('index');
	}

}