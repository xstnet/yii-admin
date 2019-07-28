<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning',],
					'logVars' => [],
					'enableRotation' => true, //开启日志文件分段写入，默认每个文件大小为10M
					'maxFileSize' => 10240, // KB
					'maxLogFiles' => 10, // 最多允许分段10个文件 如： frontend-2018-10.1.log, frontend-2018-10.2.log
					'logFile' => sprintf("@console/runtime/logs/console-%s.log",date('Y-m')),
                ],
            ],
        ],
    ],
    'params' => $params,
];
