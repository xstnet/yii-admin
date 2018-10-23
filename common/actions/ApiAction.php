<?php
/**
 * Desc: API操作基类
 * User: xstnet.com
 * Date: 17/10/09
 * Time: 上午11:00
 */
namespace common\actions;

use Yii;
use yii\web\Response;

class ApiAction extends BaseAction
{
    const DEFAULT_CODE = 0;
    const DEFAULT_MESSAGE = '操作成功';
    const ADD_MESSAGE = '添加成功';
    const SAVE_MESSAGE = '保存成功';
    const UPDATE_MESSAGE = '更新成功';
    const DELETE_MESSAGE = '删除成功';
    const LOGIN_MESSAGE = '登录成功';

    protected $code;

    protected $message;

    protected $result;

    protected function beforeRun()
    {
        if(!parent::beforeRun()) {
            return false;
        }
        return true;
    }

    protected function afterRun()
    {
        parent::afterRun();
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $ret = [];
        $ret['code'] = $this->code ?? static::DEFAULT_CODE;
        $ret['message'] = $this->message ?? static::DEFAULT_MESSAGE;
        if (isset($this->result)) {
            $ret['result'] = $this->result;
        }
        $response->data = $ret;
    }

}
