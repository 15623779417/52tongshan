<?php
/**
 * 简单操作控制器基类，主要针对简单操作
 * 
 * @author ChenJian
 *
 */
class SimpleAction extends Action{
	
	protected $_model;
	private $__isTrans = false;
	
	
	/**
	 * 
	 * 初始化
	 */
	public function _initialize()
	{		
		$this->_model();   //调用之前需要实例化模型
	}
	
	/**
	 * 生成Model
	 * 
	 */
	protected function _model()
	{
		if(!$this->_model)
		{
			$name  = $this->getActionName();
			$this->_model = D($name);
		}		
		return $this->_model;
	}
	
	/**
	 * 显示列表页
	 * 
	 */
	public function index()
	{
		$this->display();
	}
	
	/**
	 * 添加
	 * 
	 */
	public function add()
	{
		
	    $this->_before_add();
		
		if($this->isPost())
		{		
			$data = $this->_model->create();
			
			if(!$data || ($id = $this->_model->add($data)) === false )
			{
				$this->_rollback();
				$this->error("添加失败：".$this->_model->getError());
			}else
			{
				//echo $this -> _model -> getLastSql();
				$data[$this->_model->getPk()] = $id;
				log_db($this->_user['id'], $this->_model->getTableName(), 'insert', '', $data);				
				$this->_after_add($data);
				$this->assign('jumpUrl', __URL__. '/index');  //页面跳转
				$this->success("添加成功!");
			}
			return;	
		}else 
		{
			$this->display();
		}		
	}
	
	/**
	 * 添加数据扩展，需要自己实现
	 * 
	 * @param array $data 主表模型create后的数据
	 */

	protected function _before_add()
	{
		return true;
	}
	protected function _after_add(&$data)
	{
		return true;
	}
	
	/**
	 * 编辑
	 * 
	 */
	public function edit()
	{
		$this->_before_edit();
		
		$id    = $_REQUEST[$this->_model->getPk()];
		if(empty($id)) $this->error("非法请求!");
		$where = array($this->_model->getPk() => $id);
		
		if($this->isPost())
		{
			$data = $this->_model->create();
			if(!$data || $this->_model->where($where)->save($data) === false)
			{
				$this->_rollback();
				$this->error("保存数据失败：".$this->_model->getError());	
			}else
			{	
				log_db($this->_user['id'], $this->_model->getTableName(), 'update', $id, $data);
				//echo $this -> _model -> getLastSql();
				$this->_after_edit($data);
				$this->assign('jumpUrl', __URL__. '/index');
				$this->success("保存数据成功!");
			}
			return ;
		}else 
		{
			$vo = $this->_model->where($where)->find();
			$this->assign('vo', $vo);
			$this->display();
		}		
		
	}
	
	/**
	 * 修改数据扩展，需要自己实现
	 * 
	 * @param array $data 主表模型create后的数据
	 */
	protected function _after_edit(&$data)
	{
		return true;
	}
	protected function _before_edit()
	{		
		$id   = $_REQUEST[$this->_model->getPk()];
		if(empty($id)) $this->error("非法请求!");
			
		return true;
	}
	
	
	/**
	 * 删除
	 * 
	 */
	public function del()
	{		
		$this->_before_del();
				
		$ids    = $_REQUEST[$this->_model->getPk()];
		if(empty($ids)) $this->error("非法请求!");
		$where = array($this->_model->getPk() => array('in', $ids));
		
		if($this->_model->where($where)->delete() === false)
		{
			$this->_rollback();
			$this->error("删除数据失败：".$this->_model->getError());
		}else
		{   
			log_db($this->_user['id'], $this->_model->getTableName(), 'delete', $ids, $this->_model->getLastSql());
			$this->_after_del();
			$this->assign('jumpUrl', __URL__. '/index');
			$this->success("删除数据成功!");
		}
	}
	
	/**
	 * 删除数据扩展，需要自己实现
	 * 
	 */
	protected function _after_del()
	{
		return true;
	}
	protected function _before_del()
	{
		$ids    = $_REQUEST[$this->_model->getPk()];
		if(empty($ids)) $this->error("非法请求!");
		
		return true;
	}
	
	
	/**
	 * 更新字段
	 * 
	 */
	public function update()
	{
		$this->_before_update();
		
		$id  = $_REQUEST[$this->_model->getPk()];
		if(empty($id)) $this->error("非法请求!");
		$where = array($this->_model->getPk() => array('in', $id));
		$data  = array($_REQUEST['field'] => $_REQUEST['val']);
		
		if($this->_model->where($where)->save($data) === false)
		{
			$this->_rollback();
			$this->error("操作失败：".$this->_model->getError());	
		}else
		{
			log_db($this->_user['id'], $this->_model->getTableName(), 'batch', $id, $this->_model->getLastSql());
			$this->_after_update();
			$url = $_REQUEST['url'] ? $_REQUEST['url'] : '/index';	
			$this->assign('jumpUrl', __URL__. $url);
			$this->success("操作成功!");
		}
	}
	
	/**
	 * 
	 * 更新数据字段扩展，需要自己实现
	 */
	protected function _after_update()
	{
		return true;
	}
	protected function _before_update()
	{
		$id   = $_REQUEST[$this->_model->getPk()];
		if(empty($id)) $this->error("非法请求!");
		return true;
	}
	
	
	/**
	 * 查询结果的格式化
	 * 
	 */
	protected function _json_format($data)
	{
		if(!$data) return array();
		
		return $data;
	}		
	
	/**
	 * 开启事务
	 * 
	 */
	protected function _start()
	{
		if(!$this->__isTrans)
		{
			$this->_model->startTrans();
			$this->__isTrans = true;
		}		
	}
	
	/**
	 * 提交事务
	 * 
	 */
	protected function _commit()
	{
		if(!$this->__isTrans) return;
		
		$this->_model->commit();
		$this->__isTrans = false;
	}
	
	/**
	 * 回滚事务
	 * 
	 */
	protected function _rollback()
	{
		if(!$this->__isTrans) return;
		
		$this->_model->rollback();
		$this->__isTrans = false;
	}
}
?>