<?php
/**
 * ueditor ueditor 上传操作
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/5/12
 * Time: 20:48
 */

namespace backend\services\ueditor;


use backend\services\BaseService;
use Yii;

class UeditorService extends BaseService
{
	/**
	 * @Desc: ueditor 操作
	 * @return array|mixed
	 */
	public function action()
	{
		$includePath = dirname(dirname(__FILE__)) . '/ueditor/php/';
		include $includePath . 'controller.php';
	}


}