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
			
			if(!$data || ($id = $this->_model->add($data)) === false )
			{
				$this->_rollback();
				$this->error("添加失败：".$this->_model->getError());
			}else
			{
				//echo $this -> _model -> getLastSql();
				$data[$this->_model->getPk()] = $id;
				//log_db($this->_user['id'], $this->_model->getTableName(), 'insert', '', $data);				
				$this->_after_add($data);
				$this->assign('jumpUrl', __URL__. '/index');  //页面跳转
				$this->success("添加成功!");
			}
		}
		else 
		{
			
			//商品品牌
			$_brand_mod = D("GBrand");
			$brand_list = $_brand_mod->select();
			$this->assign('brand_list',$brand_list);
			
			$this->display();
		}
		
	}
	
	
	function _model()
	{
		return $this->_model = D('GGoods');
	}
}?> 