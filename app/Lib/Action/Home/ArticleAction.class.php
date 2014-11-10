<?php
/**
 * 
 * 文章控制器
 * @author ChenJian
 *
 */
class ArticleAction extends Action
{
	/**
	 * 
	 * 查看文章
	 */
	function index()
	{
		if(!($id = $_REQUEST['id'])) $this->error('非法操作！');
		$article_mod = D('Article');
		$list = $article_mod->where(array('status'=>0,'article_id'=>$id))->find();
		$this->assign('list',$list);
		$this->display();
	}
}
?>