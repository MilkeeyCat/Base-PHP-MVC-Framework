<?php 

	define('WWW', __DIR__);
	define('CORE', dirname(__DIR__) . '/vendor/core');
	define('ROOT', dirname(__DIR__));
	define('APP', dirname(__DIR__) . '/app');

	require_once '../vendor/core/router.php';
	require_once '../vendor/libs/dump.php';

	spl_autoload_register(function($class) {
		$file = APP . "/controllers/$class.php";
		if(is_file($file)) {
			require_once $file;
		}
	});

	Router::add('(?P<controller>test)/(add)', ['action' => 'add']);

	// defaults

	Router::add('^$', [
		'controller' => 'Main',
		'action' => 'test'
	]);
	Router::add('(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?');

	Router::dispatch($_SERVER['QUERY_STRING']);