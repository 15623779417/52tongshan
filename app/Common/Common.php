<?php
/**
 * 
 * 自定义_打印数组
 * @param $arr array
 */
function pr($arr)
{
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
	die();
}

/**
 * 
 * 文件上传
 */
function upload($savePath="/upload/"){
	import('ORG.Net.UploadFile');
	$upload = new UploadFile();	// 实例化上传类
	$upload->maxSize  = 3145728 ;	// 设置附件上传大小
	$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');	// 设置附件上传类型
	$upload->savePath = ".".$savePath;	// 设置附件上传目录
	if(!$upload->upload()) {	// 上传错误提示错误信息
		$info['error'] = $upload->getErrorMsg();
	}else{	// 上传成功 获取上传文件信息
	 	$info['success'] =  $upload->getUploadFileInfo();
	}
	return $info;
}
?>