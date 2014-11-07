<?php
/**
 * 
 * 文章管理
 * @author ChenJian 2014/10/16
 *
 */
class ArticleAction extends Action{
	
	/**
	 * 
	 * 文章列表
	 */
	function index()
	{
		$list = M('Article')->where(array('status'=>0))->select();
		
		$article_attr_mod = M('ArticleAttr');
		$attr_mod = M('Attr');
		foreach ($list as $key => $val)
		{
			$attr_ids = $article_attr_mod->where(array('article_id'=>$val['article_id']))->getField('attr_id',true);
			
			$attr = array();
			
			foreach ($attr_ids as $v)
			{
				$attr[] = $attr_mod->where(array('attr_id'=>$v))->getField('attr_name');
			}
			$list[$key]['attr_name'] = $attr ;
		}	
		$this->assign('list',$list);		
		$this->display();
	}
	
	/**
	 * 
	 * 添加文章
	 */
	function add()
	{
		if(!$_POST)
		{
			$this->assign('column',format($this->get_column(),'column_id')); //分类
			$this->assign('attr',$this->get_attr()); 					//文章属性	
			$this->display();	
		}
		else
		{
			//插入文章和属性
			$article_data = array(
				'title' => trim($_POST['title']),
				'column_id' => trim($_POST['column_id']),
				'author' => trim($_POST['author']),
				'title' => trim($_POST['title']),				
				'sort' =>trim($_POST['sort']),
				'short_des' => trim($_POST['short_des']),
				'content' => trim($_POST['content']),
				'add_time' => time()
			);
			
			
			//更新缩略图
			if($_FILES['short_img']['error']==0)
			{
				$savePath = "/upload/image/".date("Ymd")."/";  //设置上传的目录
				
				$info = upload($savePath);
				
				if($info['success'])
				{					
					$article_data['short_img'] = $savePath.'small/s_'.$info['success'][0]['savename'];
				}
				else 
				{
					pr($info['error']);
				}
			}
			
			if ($article_id = M('Article')->add($article_data))
			{
				$attr_data = array();
				
				if(!empty($_POST['attr_id']))
				{
					foreach ($_POST['attr_id'] as $key => $val)
					{
						$attr_data[$key]['attr_id'] = $val;
						$attr_data[$key]['article_id'] = $article_id;
					}
					if (M('ArticleAttr')->addAll($attr_data))
				    {
				    	$this->success('添加文章成功!','__URL__');			    	
				    }
				    else{
				    	$this->error('添加属性失败！');
				    }
				}else{
					$this->success('添加文章成功!','__URL__');					
				}
			}
			else{
				$this->error('添加文章失败！');
			}			
		}		
	}
	
	/**
	 * 
	 * 获取文章分类
	 */
	function get_column()
	{
		$column = M('Column')->select();
		return $column;
	}
	
	
	/**
	 * 
	 * 获取文章属性
	 */
	function get_attr( $id = 0)
	{
		if($id > 0)
		{
			
			$attr = M('Attr')->select();
			$attr_ids = M('ArticleAttr')->where(array('article_id'=>$id))->getField('attr_id',true);
			
			//显示属性(如果拥有属性，向数组中追加数据has_attr = 1)
			foreach ($attr as $key=>$val)
			{
				if (in_array($val['attr_id'],$attr_ids)) $attr[$key]['has_attr'] = "1";
			}
		}
		else {
			$attr = M('Attr')->select();
		}
		
		return $attr;
	}
	
	
	/**
	 * 
	 * 编辑文章
	 */
	function edit()
	{
		if(!($id = $_GET['id'])) $this->error('非法操作！');
		
		if (!$_POST)
		{
			$info = M('Article')->find($id);
			$this->assign('info',$info);			
			$this->assign('attr',$this->get_attr($id));
			$this->assign('column',format($this->get_column(),'column_id'));
			$this->display();
		}
		else 
		{
			//更新文章
			$article_data = array(
				'article_id' => $id,
				'title' => trim($_POST['title']),
				'column_id' => trim($_POST['column_id']),
				'author' => trim($_POST['author']),
				'title' => trim($_POST['title']),				
				'sort' =>trim($_POST['sort']),
				'short_des' => trim($_POST['short_des']),
				'content' => trim($_POST['content']),
				'update_time' => time(),
			);
			
			//更新缩略图
			if($_FILES['short_img']['error']==0)
			{
				$savePath = "/upload/image/".date("Ymd")."/";  //设置上传的目录
				
				$info = upload($savePath);
				
				if($info['success'])
				{					
					$article_data['short_img'] = $savePath.'small/s_'.$info['success'][0]['savename'];
				}
				else 
				{
					pr($info['error']);
				}
			}
			
			if (M('Article')->save($article_data))
			{					
				//更新文章的属性
				$article_attr_mod = M('ArticleAttr');
				$article_attr_mod->where(array("article_id"=>$id))->delete();  //删除原有属性
				if ($_POST['attr_id'])
				{
					//属性值 与文章关联
					foreach ($_POST['attr_id'] as $val){
						$attr_data[] = array(
							'article_id' => $id,
							'attr_id'	=> $val
						);
					}
					
					//批量插入属性
					if ($article_attr_mod->addAll($attr_data))
					{
						$this->success('编辑成功！',"__URL__",1);
					}
					else 
					{						
						$this->error('编辑失败');
					}
				}else{
					$this->success('编辑成功！',"__URL__",1);
				}
			}
			else 
			{
				$this->error('编辑失败');
			}
			
		}
	}
	
	
	/**
	 * 
	 * 文章加入回收站
	 */
	function addRecover()
	{
		if(!$_GET['id']) $this->error('非法操作！');
		
		$article_id = $_GET['id'];
		
		if (M('Article')->where(array('article_id'=>$article_id))->setField('status','1'))
		{
			$this->success('删除成功！','__URL__');	
		}
		else
		{
			$this->error('删除失败！');
		}
	}
	
	
	/**
	 * 
	 * 还原文章
	 */
	function back()
	{
		if(!$_GET['id']) $this->error('非法操作！');
		
		$article_id = $_GET['id'];
		
		if (M('Article')->where(array('article_id'=>$article_id))->setField('status','0'))
		{
			$this->success('还原成功！','__URL__/recover');	
		}
		else
		{
			$this->error('还原失败！');
		}
	}
	
	
	/**
	 * 
	 * 彻底删除文章
	 */
	function del()
	{
		if(!$_GET['id']) $this->error('非法操作！');
		
		$article_id = $_GET['id'];
		
		if (M('Article')->where(array('article_id'=>$article_id))->delete())
		{
			$this->success('删除成功！','__URL__/recover');	
		}
		else
		{
			$this->error('删除失败！');
		}
	}

	
	/**
	 * 
	 * 文章回收站
	 */
	function recover()
	{
		$list = M('Article')->where(array('status'=>1))->select();		
		$article_attr_mod = M('ArticleAttr');
		$attr_mod = M('Attr');
		foreach ($list as $key => $val)
		{
			$attr_ids = $article_attr_mod->where(array('article_id'=>$val['article_id']))->getField('attr_id',true);
			
			$attr = array();
			
			foreach ($attr_ids as $v)
			{
				$attr[] = $attr_mod->where(array('attr_id'=>$v))->getField('attr_name');
			}
			$list[$key]['attr_name'] = $attr ;
		}			
		$this->assign('list',$list);
		$this->display();
		
	}
}
?>