<?php
/**
 * Created by PhpStorm
 * Author: Shantong Xu <shantongxu@qq.com>
 * Date: 2020/7/27
 * Time: 12:31 上午
 */

namespace common\helpers;


use yii\helpers\Html;

class MyHtml extends Html
{
    public static function encode($content, $doubleEncode = true)
    {
        $str = parent::encode($content, $doubleEncode);

        return str_replace('[br]', '<br/>', $str);
    }
}