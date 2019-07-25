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
			'basePath' => '@webroot/frontend/web/assets',
			'baseUrl' => '@web/frontend/web/assets',
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
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning', ],
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
				'article-<id:\d+>' => '/article/index',
				'article/search' => '/site/search',
				'category-<categoryId:\d+>' => '/site/category',
				'/search' => '/site/search',
				'/tag/<tag:.*+>' => '/site/tag',
			],
		],

	],
    'params' => $params,
];
