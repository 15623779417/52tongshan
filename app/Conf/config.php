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
	
	'WebUrl' => 'http://tp23.local',   //这里不加最后面的斜线     为以后做图片服务器准备
	
	//自定义标签
	'TAGLIB_LOAD'=> true,
	'APP_AUTOLOAD_PATH'         =>'@.TagLib',
	'TAGLIB_BUILD_IN'           =>'Cx,Lists',
);
?>
