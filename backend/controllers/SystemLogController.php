<?php
/**
 * Desc: 系统日志
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-23
 * Time: 下午4:55
 */

namespace backend\controllers;


use backend\services\systemLog\SystemLogService;
use common\models\SystemLog;

class SystemLogController extends AdminLogController
{
	/**
	 * @Desc: 操作日志 页面
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * @Desc: 获取操作日志列表
	 * @return array
	 */
	public function actionGetLogs()
	{
		$ret = SystemLogService::instance()->getLogs();
		return self::ajaxSuccess(self::AJAX_MESSAGE_SUCCESS, $ret);
	}
}