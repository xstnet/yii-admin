<?php
/**
 * 错误处理
 * User: xstnet.com
 * Date: 17/10/09
 * Time: 上午11:03
 */
namespace common\actions;

use Yii;
use yii\web\Response;
use common\exceptions\BaseException;

class ErrorAction extends BaseAction
{
	const SYSTEM_ERROR = 10000;
    /**
     * Runs the action
     *
     * @return string result content
     */
    public function run()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return '';
        }
        $response = Yii::$app->response;
//		$response->statusCode;
        $response->format = Response::FORMAT_JSON;
        if ($exception instanceof BaseException) {
            $response->data = $exception->getResponse();
        } else {
            $response->data = [
                'code' => $exception->getCode() ? : self::SYSTEM_ERROR,
                'message' => $exception->getMessage(),
            ];
        }
        return $response;
    }
}
