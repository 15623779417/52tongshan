<?php
return array(
	//配置数据库
	'DB_HOST' => '127.0.0.1',
    'DB_USER' => 'root',
    'DB_PWD' => '',
    'DB_NAME' => 'tp',
    'DB_PREFIX' => 'ts_',

	//显示Trance信息
	'SHOW_PAGE_TRACE'=>true, 

	//公共文件
	'TMPL_PARSE_STRING'=>array(
		'__PUBLIC__'=>__ROOT__.'/'.APP_NAME.'/Tpl/Public'           
	),

	//独立分组
	'APP_GROUP_LIST'  =>'home,admin',
	'DEFAULT_GROUP'   =>'home',
);
?>
