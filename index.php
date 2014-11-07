<?php
	//设置编码
	header("Content-Type: text/html; charset=UTF-8");
	// 检测PHP环境
	if(version_compare(PHP_VERSION,'5.3.0','<'))  die('PHP版本必须为5.3以上！');
	define('THINK_PATH', "./core/THINKPHP/");	
	define("APP_NAME", "app");
	define("APP_PATH", "./app/");
	define("APP_DEBUG",true);
	require THINK_PATH.'ThinkPHP.php';
?>