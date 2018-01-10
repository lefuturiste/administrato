<?php
return [
	'app_name' => getenv('APP_NAME'),
	'app_debug' => (envOrDefault('APP_DEBUG', 0) ? true : false),
	'env_name' => getenv('APP_ENV_NAME'),
	'log' => [
		'level' => getenv('LOG_LEVEL'),
		'discord' => getenv('LOG_DISCORD'),
		'discord_webhooks' => [
			getenv('DISCORD_WH_URL')
		],
		'path' => getenv('LOG_PATH')
	],
	'twig' => [
		'cache' => getenv('TWIG_CACHE') == 'false' || !getenv('TWIG_CACHE') ? false : getenv('TWIG_CACHE')
	]
];