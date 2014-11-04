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
?>