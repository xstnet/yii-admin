<?php
return [
    'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=127.0.0.1;dbname=xstnet',
			'username' => 'xstnet_blog',
			'password' => 'asd#%3GNldjgeHFVv(*&',
			'charset' => 'utf8',
			'tablePrefix' => 'x_',
		],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
