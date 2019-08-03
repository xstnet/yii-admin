<?php
namespace backend\controllers;

use backend\services\setting\SettingService;
use backend\services\todo\TodoService;
use common\models\AdminLoginHistory;
use common\models\Article;
use common\models\CountIp;
use common\models\CountRecord;
use common\models\CountTotal;
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
		
		$summary = [
			'total_count' => CountTotal::find()->max('total_count'),
			'article_count' => Article::find()->where(['is_show' => 1, 'is_delete' => 0])->count(),
			'article_count_7' => Article::find()->where(['is_show' => 1, 'is_delete' => 0])
				->andWhere(['>', 'created_at', strtotime(date('Y-m-d', strtotime('-7 day')))])
				->count(),
			'article_count_30' => Article::find()->where(['is_show' => 1, 'is_delete' => 0])
				->andWhere(['>', 'created_at', strtotime(date('Y-m-d', strtotime('-30 day')))])
				->count(),
		];
		
		$sql = "select c.category_name, c.id, COUNT(*) count from x_article a LEFT JOIN x_article_category c on a.category_id =  c.id where c.status = 0 GROUP BY c.id";
		$category = Yii::$app->db->createCommand($sql)->queryAll();
		$chartCategory = [];
		foreach ($category as $item) {
			$chartCategory['legendData'][] = $item['category_name'];
			$chartCategory['selected'][$item['category_name']] = true;
			$chartCategory['seriesData'][] = [
				'name' => $item['category_name'],
				'value' => $item['count'],
			];
		}
		
		$dayCount = CountRecord::find()
			->limit(7)
			->orderBy(['id' => SORT_DESC])
			->asArray()
			->all();
		$ipCount = CountIp::find()
			->limit(7)
			->orderBy(['id' => SORT_DESC])
			->asArray()
			->all();
		$chartDayCount = [];
		foreach ($dayCount as $item) {
			$chartDayCount['day'][] = $item['count'];
			$chartDayCount['date'][] = date('m/d', $item['date_at']);
		}
		$chartDayCount['ip'] = array_column($ipCount, 'count');

		return $this->render('welcome', [
			'loginHistory' => $loginHistory,
			'todos' => $todos,
			'summary' => $summary,
			'chartCategory' => $chartCategory,
			'chartDayCount' => $chartDayCount,
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
