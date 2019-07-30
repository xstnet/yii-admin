<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
	'defaultRoute' => 'site/index',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
		'assetManager' => [
			'basePath' => '@webroot/assets',
			'baseUrl' => '@web/assets',
		],
        'request' => [
            'csrfParam' => '_csrf_token_backend_xstnet',
        ],
        'user' => [
            'identityClass' => 'common\models\AdminUser',
            'enableAutoLogin' => true,
			'loginUrl' => 'login/index',
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
					'maxLogFiles' => 10, // 最多允许分段10个文件 如： backend-2018-10.1.log, backend-2018-10.2.log
					'logFile' => sprintf("@backend/runtime/logs/backend-%s.log",date('Y-m')),
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'error/error',
		],

/*        'urlManager' => [
			'enablePrettyUrl' => true, //启用 url 美化
			'enableStrictParsing' => true,
//			'suffix'=>'.html', //后缀
			'showScriptName' => false, //隐藏index.php
//			'rules' => require(__DIR__. '/url.php'), // 自定义URL路由规则
			//'cache' => 'fileCache', // 使用文件缓存
        ],*/
		'response' => [
			'class' => 'yii\web\Response',
			'on beforeSend' => function ($event) {
				// 返回的错误信息只显示code和message
				$response = $event->sender;
				// 业务逻辑错误
				if (isset($response->data['code']) && $response->data['code'] !== 0) {
					$response->data = [
						'code' => $response->data['code'],
						'message' => $response->data['message'],
					];
				}
				// HTTP错误
//				if (isset($response->data['status']) && $response->data['status'] !== 200) {
//					$response->data = [
//						'code' => $response->data['status'],
//						'message' => $response->data['message'],
//					];
//				}
				$response->statusCode = 200; // 错误信息返回码同样200
			},
		],

    ],
    'params' => $params,
];
