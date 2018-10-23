<?php

/**
 * Created by PhpStorm.
 * Desc: 异常类基类
 * User: xstnet.com
 * Date: 2017/10/9
 * Time: 14:30
 */
namespace common\exceptions;

use yii\base\UserException;

class BaseException extends UserException
{
    public $response;

    public static $messages = [];

    public static $defaultMessage = '未知错误';

    public function __construct($code = 1, $message = null, $params = [], \Exception $previous = null)
    {
        if (empty($message)) {
            $message = isset(static::$messages[$code]) ?
                static::$messages[$code] :
                static::$defaultMessage;
        }
        if (strpos($message,'%') !== false && !empty($params)) {
            $message = vsprintf($message, $params);
        }
        $this->response = [
            'code' => $code,
            'message' => $message,
        ];
        parent::__construct($message, $code, $previous);
    }

    public function getName()
    {
        return 'BaseException';
    }

    public function __toString()
    {
        return json_encode($this->getResponse());
    }

    public function getResponse()
    {
        return $this->response;
    }
}