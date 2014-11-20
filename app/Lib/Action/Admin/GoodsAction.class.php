<?php
/**
 * 
 * 商品模块
 * @author ChenJian
 *
 */
class GoodsAction extends SimpleAction{
	
	function index()
	{
		$list = $this->_model->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	function add()
	{
		if(IS_POST)
		{
			$data = $this->_model->create();
			$data['add_time'] = time();	
					
			$cate_id = array_unique($_POST['cate_id']);   //商品分类
			
			$this->_start();
			if(!$data || ($id = $this->_model->add($data)) === false )
			{
				$this->_rollback();
				$this->error("添加商品失败：".$this->_model->getError());
			}else
			{
				//添加分类
				$_goods_cate_mod = D('GGoodsCate');
				$cate_data = array();
				foreach ($cate_id as $key => $val)
				{
					$cate_data[$key]['good_id'] = $id;
					$cate_data[$key]['cate_id'] = $val;
				}
				//pr($cate_data);
				if(!$cate_data || $_goods_cate_mod->addAll($cate_data) === false )
				{
					$this->_rollback();
					$this->error("添加分类失败：".$this->_model->getError());
				}
				else 
				{
					$this->_commit();
					$this->assign('jumpUrl', __URL__. '/index');  //页面跳转
					$this->success("添加成功!");
				}
			}
		}
		else 
		{
			
			//商品品牌
			$_brand_mod = D("GBrand");
			$brand_list = $_brand_mod->select();
			$this->assign('brand_list',$brand_list);
			
			
			//商品分类
			$_cate_mod = D("GCate");
			$cate_list = $_cate_mod->getField('cate_id,cate_name,level,pid');
			$cate_list = format($cate_list);
			$this->assign('cate_list',$cate_list);
			
			$this->display();
		}
		
	}
	
	
	function _model()
	{
		return $this->_model = D('GGoods');
	}
}?> 