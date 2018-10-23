<?php
/**
 * Created by PhpStorm.
 * Desc: 参数异常
 * User: xstnet.com
 * Date: 2017/10/9
 * Time: 14:44
 */

namespace common\exceptions;


class ParameterException extends BaseException
{
    const UNKNOWN = 30000;
    const NOT_EMPTY = 30001;
    const INVALID = 30002;
    const NEED_LOGIN = 99;

    public static $messages = [
        ParameterException::UNKNOWN => '参数错误',
        ParameterException::NOT_EMPTY => '参数不能为空',
        ParameterException::INVALID => '参数无效',
    ];

    public function getName()
    {
        return 'ParameterException';
    }
}