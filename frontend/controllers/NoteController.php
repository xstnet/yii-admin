<?php
/**
 * Site Map
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 20-11-22
 * Time: 上午00:05
 */

namespace frontend\controllers;


use Yii;
use yii\filters\AccessControl;

class NoteController extends BaseController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
		];
	}
	
	/**
	 * Displays Site Map Xml.
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index', [
            'active_menu' => 'note'
        ]);
	}
}