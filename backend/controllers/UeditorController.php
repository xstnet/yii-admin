<?php
/**
 * Desc: 百度编辑器上传文件的控制器
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-11-1
 * Time: 下午12:13
 */

namespace backend\controllers;


use backend\services\ueditor\UeditorService;

class UeditorController extends AdminLogController
{

	public $enableCsrfValidation = false;
	/**
	 * @Desc:
	 * @return array|mixed
	 */
	public function actionIndex()
	{
		return UeditorService::instance()->action();
	}
}