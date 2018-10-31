<?php
/**
 * Desc:
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/9/10
 * Time: 18:26
 */

namespace backend\controllers;


use common\exceptions\BaseException;
use yii\filters\RateLimiter;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class BaseController extends Controller
{
	public static $userInfo;

	const AJAX_STATUS_FAILED = 1;

	const AJAX_STATUS_SUCCESS = 0;

	const AJAX_MESSAGE_FAILED = '请求失败';

	const AJAX_MESSAGE_SUCCESS = 'ok';

	const AJAX_MESSAGE_NO_PERMISSION = '你没有权限操作';

	const DEFAULT_AJAX_DATA = null;

	public $layout = false;

	public function behaviors()
	{
		return [
			/**
			 * 调用速率限制
			 */
			'rateLimiter' => [
				'class' => RateLimiter::className(),
				'except' => [
					'error',
				],
			],
		];
	}

	public static function ajaxReturn($msg = self::AJAX_MESSAGE_FAILED, $status = self::AJAX_STATUS_FAILED, $data = self::DEFAULT_AJAX_DATA)
	{
		//如果提示信息为成功，且未传递成功标识，置之为成功
		if ($msg == self::AJAX_MESSAGE_SUCCESS && $status == self::AJAX_STATUS_FAILED) {
			$status = self::AJAX_STATUS_SUCCESS;
		}
		$result = [
			'code' => $status,
			'message'    => $msg,
			'data'   => $data,
		];
		Yii::$app->response->format = Response::FORMAT_JSON;
		Yii::$app->response->statusCode = 200;

		return $result;
	}

	public static function ajaxSuccess($msg = self::AJAX_MESSAGE_SUCCESS, $data = [], $code = self::AJAX_STATUS_SUCCESS)
	{
		return self::ajaxReturn($msg, $code, $data);
	}

	public static function formatHtml()
	{
		Yii::$app->response->format = Response::FORMAT_HTML;
		Yii::$app->response->statusCode = 200;
	}

	/**
	 * @Desc:
	 * @param string $id
	 * @param array $params
	 * @return mixed
	 * @throws BaseException
	 */
	public function runAction($id, $params = [])
	{
		try {
			return parent::runAction($id, $params); // 捕获所有action方法异常，格式化返回
		} catch (\Exception $e) {
			Yii::error($e->getMessage());
			Yii::error($e->getTraceAsString());
			throw new BaseException(10001, $e->getMessage());
		}
	}

	/**
	 * @Desc: 获取Get参数
	 * @param string $key
	 * @param string $defaultValue
	 * @return array|mixed
	 */
	public static function getParams($key = '', $defaultValue = '')
	{
		if (empty($key)) {
			return Yii::$app->request->get();
		}

		return Yii::$app->request->get($key, $defaultValue);
	}

	/**
	 * @Desc: 获取post参数
	 * @param string $key
	 * @param string $defaultValue
	 * @return array|mixed
	 */
	public static function postParams($key = '', $defaultValue = '')
	{
		if (empty($key)) {
			return Yii::$app->request->post();
		}

		return Yii::$app->request->post($key, $defaultValue);
	}
}