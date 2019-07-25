<?php
namespace frontend\controllers;

use common\models\Article;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
    	$data = $this->getArticleList();
    	
		return $this->render('index', $data);
	}
	
	/**
	 * 分类文章列表
	 * @param int $categoryId
	 * @return string
	 */
	public function actionCategory(int $categoryId)
	{
		$data = $this->getArticleList(['category_id' => $categoryId]);
		
		$breadcrumb = [
			[
				'name' => '首页',
				'href' => '/',
			],
			[
				'name' => Yii::$app->userCache->getArticleCategoryNameById($categoryId),
				'href' => "/category-$categoryId.html",
			],
		];
		
		$data['breadcrumb'] = $breadcrumb;
		
		return $this->render('index', $data);
	}
	
	/**
	 * 搜索
	 * @return string
	 */
	public function actionSearch()
	{
		$keyword = trim(Yii::$app->request->get('s', ''));
		if (empty($keyword)) {
			$keyword = trim(Yii::$app->request->get('keyword', ''));
		}
		$where = [];
		if (!empty($keyword)) {
			$where = ['like', 'title', $keyword];
		}
		
		$data = $this->getArticleList($where);
		
		$breadcrumb = [
			[
				'name' => '首页',
				'href' => '/',
			],
			[
				'name' => "搜索： $keyword ",
				'href' => false,
			],
			[
				'name' => "共搜索到 <strong>{$data['count']}</strong> 条数据",
				'href' => false,
			]
		];
		
		$data['breadcrumb'] = $breadcrumb;
		
		foreach ($data['articleList'] as $key => $item) {
			$data['articleList'][$key]['title'] = str_replace($keyword, "<span style='color: #d62929'>$keyword</span>", $item['title']);
		}
		
		return $this->render('index', $data);
	}
	
	public function actionTag($tag)
	{
		$where = "find_in_set('$tag', keyword)";
		$data = $this->getArticleList($where);
		
		$breadcrumb = [
			[
				'name' => '首页',
				'href' => '/',
			],
			[
				'name' => $tag,
				'href' => "/tag/$tag.html",
			],
			[
				'name' => "共查找到 <strong>{$data['count']}</strong> 条数据",
				'href' => false,
			]
		];
		
		$data['breadcrumb'] = $breadcrumb;
		
		return $this->render('index', $data);
	}
  
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	
	/**
	 * 获取文章列表
	 * @param $where
	 * @return array
	 */
    private function getArticleList($where = []) : array
	{
		$query = Article::find()
			->where(['is_show' => Article::IS_SHOW_YES])
			->andWhere($where);
		list ($count, $pages) = $this->getPage($query);
		$articleList = $query->orderBy(['created_at' => SORT_DESC])
			->asArray()
			->all();
		
		return [
			'articleList' => $articleList,
			'pages' => $pages,
			'count' => $count,
		];
	}
}
