<?php
/**
 * 
 * 清除缓存
 * @author ChenJian 2014/10/16
 *
 */

//设置编码
header("Content-type: text/html; charset=utf-8");  	

//定义缓存文件的目录
$appRuntime = 'app/Runtime/';  
 
//执行删除文件
if(file_exists($appRuntime)){	
	if(deldir($appRuntime)){
		echo "清理缓存成功！";
	}else{
		echo '清理缓存失败！';
	}
}else{
	echo '暂时没有缓存文件，谢谢！';
}

	
/**
 * 
 * 删除文件函数
 * 
 */
function deldir($dir){
	//先删除目录下的文件：		
	$handle = opendir($dir);
	while (false!==($FolderOrFile = readdir($handle))){
		if($FolderOrFile != "." && $FolderOrFile != ".."){
			if(is_dir("$dir/$FolderOrFile")){
				deldir("$dir/$FolderOrFile");
			}else{
				@unlink("$dir/$FolderOrFile");
			}
		}
	}
	closedir($handle);	
	
	//删除当前文件夹：		
	if(@rmdir($dir)) {
    	return true;
  	} else {
    	return false;
  	}
}