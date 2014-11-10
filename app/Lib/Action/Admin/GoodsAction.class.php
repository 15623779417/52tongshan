<?php
/**
 * 
 * 商品模块
 * @author ChenJian
 *
 */
class GoodsAction extends Action{
	
	function index()
	{
		
		
		$this->display();
	}
	
	function add()
	{
		//商品品牌
		$_brand_mod = D("GBrand");
		$brand_list = $_brand_mod->select();
		$this->assign('brand_list',$brand_list);
		
		if(IS_POST)
		{
			pr($_POST);	
		}
		else 
		{
			$this->display();
		}
		
	}
	
}?> 