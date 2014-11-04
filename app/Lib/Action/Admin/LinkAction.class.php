<?php
/**
 * 
 * 友情链接
 * @author ChenJian 2014/10/16
 *
 */
class LinkAction extends Action
{
	
	function index()
	{
		$list = M('Link')->order('sort')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	
	/**
	 * 
	 * 添加
	 */
	function add()
	{
		if(!$_POST)
		{
			$this->display();
		}
		else
		{
			$link_data = array(
				'name' => trim($_POST['name']),
				'url' => trim($_POST['url']),
				'mark' => trim($_POST['mark']),
				'sort' => trim($_POST['sort']),
				'img' => '111',
				'addtme' => time()
			);
			if (M('Link')->add($link_data))
			{
				$this->success('添加成功！','__URL__');
			}
			else 
			{
				$this->error('添加失败！');
			}
		}
	}
}
?>