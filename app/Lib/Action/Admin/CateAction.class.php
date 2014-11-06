<?php
/**
 * 
 * 商品分类模块
 * @author ChenJian
 *
 */
class CateAction extends SimpleAction{
	
	
	
	/**
	 * 
	 * 初始化model
	 * 
	 */
	function _model()
	{
		$this->_model = D('GCate');
	}
	
	/**
	 * 添加分类
	 * 
	 */
	function add()
	{
		if($this->isPost())
		{
		
			$data = $this->_model->create();
				
			$tmp = explode('_', $data['pid']);
			
			$data['level'] = $tmp[0]+1;
			$data['pid'] = $tmp[1];
			$data['add_time'] = time();	
			
			$this->_start();
			
			if(!$data || ($id = $this->_model->add($data)) === false)
			{
				$this->_rollback();
				$this->error("添加失败：".$this->_model->getError());
			}
			else
			{
				//更新分类图标
				if($_FILES['thumb_img']['error']==0)
				{
					$savePath = "/upload/cate/";  //设置上传的目录
					
					$info = upload($savePath,$id);
					
					if($info['success'])
					{					
						$thumb_img = $savePath."small/s_".$info['success'][0]['savename'];
					}
					else 
					{
						pr($info['error']);
					}
				}	
				
				$where = array($this->_model->getPk() => $id);	
				
				if($this->_model->where($where)->setField('thumb_img',$thumb_img)===false)
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
			//分类
			$cate = $this->_model->getField('cate_id,cate_name,level,pid');	
			$cate = format($cate);
			$this->assign('cate',$cate);
			$this->display();
		}
	}	
	
}?>