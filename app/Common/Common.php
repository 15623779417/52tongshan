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
function upload($savePath="/upload/",$name=""){
	import('ORG.Net.UploadFile');
	$upload = new UploadFile();	// 实例化上传类
	$upload->maxSize  = 3145728 ;	// 设置附件上传大小
	$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');	// 设置附件上传类型
	
	//设置附件上传目录
	$upload->savePath = ".".$savePath;	
		
	//是否自定义上传文件的文件名
	$upload->isDefinedName = $name;
	
	//是否缩略处理
	$upload->thumb = true;
	
	$upload->thumbType = 0;
	$upload->thumbFile = '';  
	$upload->thumbMaxWidth = '200';
	$upload->thumbMaxHeight = '200';
	$upload->thumbPrefix = 's_';	
	$upload->thumbPath = $upload->savePath.'small/';
	
	if(!$upload->upload()) {	// 上传错误提示错误信息
		$info['error'] = $upload->getErrorMsg();
	}else{	// 上传成功 获取上传文件信息
	 	$info['success'] =  $upload->getUploadFileInfo();
	}
	return $info;
}


/**
 * 
 * 无限极分类
 * @param array $arr 二维数组
 * @param string 
 * @param string $pid 父级ID
 * retuen array 二维数组
 */
function format($arr,$field='cate_id',$pid=0,$lev=1,$html='____')
{	
	$options = array();
	foreach ($arr as $val)
	{
		if ($val['pid'] == $pid )
		{
			$val['lev'] = $lev;
			if ($lev) $val['html'] = '|'.str_repeat($html,$lev);
			$options[] = $val; 
			$options = array_merge($options,format($arr,'cate_id',$val[$field],$lev+1,$html));
		}
	}
	return $options;
}

?>