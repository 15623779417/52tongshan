<?php
/**
 * 
 * 栏目管理
 * @author ChenJian
 *
 */
class ColumnAction extends Action
{
	/**
	 * 
	 * 分类列表
	 */
	function index()
	{
		
		$list = M('Column')->select();
		$list = $this->format($list);
		$this->assign('list',$list);
		$this->display();
	}
	
	/**
	 * 
	 * 添加/编辑分类
	 */
	function add()
	{
		$_column_mod = M('Column');
		
		if ($_POST)
		{
			$data = array(
				'column_name' => trim($_POST['column_name']),
				'pid' => trim($_POST['pid']),
				'sort' => trim($_POST['sort']),
				'status' => trim($_POST['status']),
				'addtime' => time(),
				'column_des' => trim($_POST['column_des']),
			);
			
			if ($_column_mod->add($data))
			{
				$this->success('保存成功','__URL__');
			}
			else 
			{
				$this->error('保存失败');
			}
		}
		else
		{
			$options = $_column_mod->field('column_id,column_name,pid')->select();
			$options = $this->format($options);
			$this->assign('options',$options);
			$this->display();
		} 
		
	}
	/**
	 * 
	 * 无限极分类
	 * @param array $arr 二维数组
	 * @param atring type $pid 父级ID
	 * retuen array 二维数组
	 */
	function format($arr,$pid=0,$level=0,$html='__')
	{	
		$options = array();
		foreach ($arr as $val)
		{
			if ($val['pid'] == $pid )
			{
				$val['level'] = $level;
				if ($level) $val['html'] = '|'.str_repeat($html,$level);
				$options[] = $val; 
				$options = array_merge($options,$this->format($arr,$val['column_id'],$level+1,$html));
			}
		}
		return $options;
	}
}
?>