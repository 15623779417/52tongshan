<?php
/**
 * 
 * 品牌模块
 * @author ChenJian
 *
 */
class BrandAction extends SimpleAction{	
	
	function index()
	{	
		$list = $this->_model->select();
		$this->assign('list',$list);	
		$this->display();
	}
	
	
	/**
	 * 
	 * 添加品牌
	 */
	function add()
	{
		if($this->isPost())
		{
			//pr($_POST);
			$data = $this->_model->create();
			
			$this->_start();
			
			if(!$data || ($id = $this->_model->add($data)) === false)
			{
				$this->_rollback();
				$this->error("添加失败：".$this->_model->getError());
			}
			else
			{
				//更新品牌LOGO
				if($_FILES['logo']['error']==0)
				{
					$savePath = "/upload/brand/";  //设置上传的目录
					
					$info = upload($savePath,$id);
					
					//pr($info);
					if($info['success'])
					{					
						$logo = $savePath."small/s_".$info['success'][0]['savename'];
					}
					else 
					{
						pr($info['error']);
					}
				}	
				
				$where = array($this->_model->getPk() => $id);	
				
				if($this->_model->where($where)->setField('logo',$logo)===false)
				{
					$this->_rollback();
					$this->error("添加失败：".$this->_model->getError());
				}
				else
				{
					$data[$this->_model->getPk()] = $id;
				
					//log_db($this->_user['id'], $this->_model->getTableName(), 'insert', '', $data);				
					$this->_after_add($data);
					$this->assign('jumpUrl', __URL__. '/index');  //页面跳转
					$this->_commit();
					$this->success("添加成功!");
				}
			}
			
		}
		else
		{
			$this->display();
		}
	}	
	
	
	/**
	 * 
	 * 初始化model
	 * 
	 */
	function _model()
	{
		$this->_model = D('GBrand');
	}
}?>