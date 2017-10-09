<?php
// 在线分析文件

class OnlineAction extends BaseAction {

	protected $By_tpl = 'Online'; 
	
	//显示在线人数
	public function tongji1(){
		$usertable = M('user_online');
		$where['id']=1002;
		$user = $usertable->where($where)->find();
		$this->assign('left_css',"1");
		$this->assign('user',$user);
		
		$this->display('Online:tongji1');
	}
}