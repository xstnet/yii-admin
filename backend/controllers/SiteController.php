<?php
namespace backend\controllers;

use backend\services\setting\SettingService;
use backend\services\todo\TodoService;
use common\models\AdminLoginHistory;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends AdminLogController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'welcome', 'no-permission'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    	$this->layout = 'main';
//    	$menus = SettingService::instance()->getMenus();
		////    	print_r($menus);die;
        return $this->render('index');
    }

	/**
	 * @Desc: 欢迎页面
	 * @return string
	 */
    public function actionWelcome()
	{
		$loginHistory = AdminLoginHistory::find()
			->where(['user_id' => Yii::$app->user->id])
			->orderBy('created_at desc')
			->limit(3)
			->asArray()
			->all();

		TodoService::$defaultPageSie = 7;
		$todos = TodoService::instance()->getTodoList();

		return $this->render('welcome', [
			'loginHistory' => $loginHistory,
			'todos' => $todos,
		]);
	}


    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	/**
	 * 无权限展示页面
	 * @return string
	 */
    public function actionNoPermission()
	{
		return $this->render('no-permission');
	}
}
