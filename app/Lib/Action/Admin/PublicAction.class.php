<?php
class PublicAction extends Action
{
	
	public function top() 
	{
		$this->display();
	}
	
	public function main() 
	{
		$this->display();
	}
	
	
	public function menu() 
	{
		$tag=$_GET['tag'];
		if ($tag == '1')
		{			
			$this->assign('menuTitle','模块管理');
		}
		else if ($tag == '2') 
		{
			$this->assign('menuTitle','手机APP');
		}
		else if ($tag == '3') 
		{
			$this->assign('menuTitle','系统管理');
		}
		else if ($tag == '4') 
		{
			$this->assign('menuTitle','扩展功能');
		}
		else{
			$this->assign('menuTitle','内容管理');
		}	
 
		$menu = array(
			array(
				'url'=>'Setting/index',
				'title'  => '站点设置'
			),
			array(
				'url'=>'Setting/verify',
				'title'  => '验 证 码'
			)
		);
		$this->assign('menu',$menu);
		$this->display();
	}
	
	public function drag() 
	{
		$this->display();
	}
	
	public function bottom() 
	{
		$this->display();
	}
}?>