<?php
/**
 * Created by PhpStorm.
 * Desc: 数据库异常
 * User: xstnet.com
 * Date: 2017/10/9
 * Time: 14:41
 */

namespace common\exceptions;


class DatabaseException extends BaseException
{
    const UNKNOWN = 20000;
    const INSERT_ERROR = 20001;
    const UPDATE_ERROR = 20002;

    public static $messages = [
    ];

    public function getName()
    {
        return 'DatabaseException';
    }
}