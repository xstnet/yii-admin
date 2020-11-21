<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
		'assetManager' => [
			'basePath' => '@webroot/assets',
			'baseUrl' => '@web/frontend/assets',
		],
        'request' => [
            'csrfParam' => '_csrf-avwd',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'frontend-avwd',
        ],
		'log' => [
			// 假如 YII_DEBUG 开启则是3，否则是0。 这意味着，假如 YII_DEBUG 开启，每个日志消息在日志消息被记录的时候， 将被追加最多3个调用堆栈层级；假如 YII_DEBUG 关闭， 那么将没有调用堆栈信息被包含。
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning',],
					'logVars' => ['_GET', '_POST', ],
					'enableRotation' => true, //开启日志文件分段写入，默认每个文件大小为10M
					'maxFileSize' => 10240, // KB
					'maxLogFiles' => 10, // 最多允许分段10个文件 如： frontend-2018-10.1.log, frontend-2018-10.2.log
					'logFile' => sprintf("@frontend/runtime/logs/frontend-%s.log",date('Y-m')),
				],
			],
		],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'urlManager'=>[
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'suffix'=>'.html',
			'rules' => [
				"/" => "/site/index",
				"/gii" => "/index.php?r=gii",
				'article-<id:\d+>' => '/article/index', // 文章详情
				'article/get-comments' => '/article/index', // 获取文章评论
				'/search' => '/site/search', // 搜索
				'/article/search' => '/site/search',  // 搜索， 兼容老版本
				'/about' => '/site/about',  // 关于我
				'/category-<categoryId:\d+>' => '/site/category', // 分类
				'/archive/<year:20\d\d>/<month:(0|1)\d>' => '/archive/list', // 归档文档列表
				'/archive' => '/archive/index', // 归档
				'/note' => '/note/index',  // 笔记
				'/tag/<tag:.*+>' => '/site/tag', // 标签
				'/message/release' => '/message/release', // 发布留言
				// site map
				[
					'pattern' => '/sitemap',
					'route' => '/site-map/index',
					'suffix' => '.xml',
				],
				// rss
				[
					'pattern' => '/rss',
					'route' => '/rss/index',
					'suffix' => '.xml',
				],
				[
					'pattern' => '/counter',
					'route' => '/site/counter',
					'suffix' => '.html',
				],
			],
		],

	],
    'params' => $params,
];
