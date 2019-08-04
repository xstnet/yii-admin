<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
	'defaultAvatar' => 'static/images/default-head.jpg',
	'static_file_t' => 15000000015, // 防止静态资源不刷新，需要时每次加一即可
	'spider_user_agent' => [
		'Baiduspider', // 百度爬虫
		'360Spider', // 360爬虫
		'Googlebot', // google爬虫
		'msnbot', // 微软MSN爬虫
		'Spider', //
		'Sogou web spider', // 搜狗爬虫
		'sogou spider', // 搜狗爬虫
		'YodaoBot', // 网易有道爬虫
		'iaskspider', // 新浪爱问爬虫
	],
];
