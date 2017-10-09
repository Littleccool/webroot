<?php
class AgentAction extends BaseAction {
	public function displayAgentApply(){
		$agentApplyTable = M('agency');
		$condition['stat'] = 1;
		$list = $agentApplyTable->where($condition)->select();
		
		foreach($list as $key => $val){
			$list[$key]["regDate"]= date("Y-m-d H:i:s",$list[$key]["createTs"]);	
		}
		
		$this->assign('left_css',"1");
		$this->assign('list',$list);
		$this->display();
	}

	public function displayAgent(){
		$this->displayAgentOrDitui(0);
	}

	public function displayDitui(){
		$this->displayAgentOrDitui(1);
	}

	private function displayAgentOrDitui($isDitui){
		$agency = M('agency');
		$condition['stat'] = 0;
		$condition['is_ditui'] = $isDitui;
		$condition['level'] = array('gt', 0);

		//$list = $agency->where($condition)->order('level desc')->order('game_uid')->select();
		$list = $agency->where($condition)->order('bind_ts desc')->select();
		foreach($list as $key => $val){
			$where = array();
			$where["upper_id"] = $val['game_uid'];
			$where["level"] = array('gt', 0);
			$where["stat"] = 0;
			$downAgency = $agency->where($where)->select();
			$list[$key]["downAgencyCount"] = count($downAgency);

			$where = array();
			$where["upper_id"] = $val['game_uid'];
			$where["level"] = 0;
			$downUser = $agency->where($where)->select();
			$list[$key]["downUserCount"] = count($downUser);

			$list[$key]["createDate"]= date("Y-m-d H:i:s",$list[$key]["bind_ts"]);	
		}
		$this->assign('left_css',"1");
		$this->assign('list',$list);
		$this->display();
	}

	public function downAgency($upper_id)
	{
		$this->showDownAgencyOrUser($upper_id, 1);
	}

	public function downUser($upper_id)
	{
		$this->showDownAgencyOrUser($upper_id, 0);
	}

	private function showDownAgencyOrUser($upper_id, $showAgency)
	{
		$agency = M('agency');
		$where['game_uid'] = $upper_id;
		$list = $agency->where($where)->select();
		$upper_name = $list[0]['name'];

		$condition['upper_id'] = $upper_id;
		$condition['level'] = $showAgency ? array('gt', 0) : array('eq', 0);
		if ($showAgency)
		{
			$condition['stat'] = 0;
		}
		
		$list = $agency->where($condition)->order('level desc')->order('game_uid')->select();
		foreach($list as $key => $val){
			$list[$key]["createDate"]= date("Y-m-d H:i:s",$list[$key]["bind_ts"]);
		}

		$this->assign('left_css',"1");
		$this->assign('list',$list);
		$this->assign('upper_name',$upper_name);
		$this->display();
	}
	

	public function apply(){
		$row1 = M('game_list');
		import('ORG.Util.Page');
		$Page = new Page($count,15);//实例化分页类传入总记录数和每页显示的记录数		
		$show = $Page->show();// 分页显示输出
		
		$gameList = $row1->where('appId <> 100000')->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
		
		$agent=M('agent');
		if(!empty($_POST)){
		
			if(empty($_POST['appId'])){
				$this->error('游戏名字不能为空');
				exit;
			}
			
			if(empty($_POST['leaderId'])){
				$this->error('上级代理id不能为空');
				exit;
			}
			
			
			if(empty($_POST['level'])){
				$this->error('代理等级空');
				exit;
			}
			
			$leaderId = $_POST['leaderId'];
			$level = $_POST['level'];
			
			$where=array();
			$where["appid"] = $_POST['appId'];
			$where["agentid"] = $_POST['leaderId'];
			$where["status"] = 0;
			print_r($where);
			$result = $agent->where($where)->select();
		
			if(count($result) != 1){
				$this->error('上级代理不存在');
				exit;
			}
			
			if($result[0]["agentLevel"] > $level){
				$this->error('申请等级不能比上级代理等级高');
				exit;
			}
			
			if(empty($_POST['uid'])){
				$this->error('uid 不能为空');
				exit;
			}
			
			if(empty($_POST['phoneNum'])
				||	!is_numeric($_POST['phoneNum']) 
				||  strlen($_POST['phoneNum']) != 11){
				$this->error('电话号码错误');
				exit;
			}
			
			if(empty($_POST['weixin'])){
				$this->error('微信号不能为空');
				exit;
			}
			
			if(empty($_POST['username'])){
				$this->error('真实姓名不能为空');
				exit;
			}
			
			$data=array();
			$data['username']   = htmlspecialchars(trim($_POST['username']));
			$data['uid']   = I('uid');
			$data['appid'] =  I('appId');
			$data['agentLevel'] =  I('level');;
			$data['phoneNum']   = I('phoneNum');
			$data['weChat']     = I('weixin');
			$data['superAgentid']     = I('leaderId');
			$data['status']     = 1;
			$data['createTs']     = time();

			
			$result=$agent->add($data);
			$this->success('申请成功');
		}else{
			$this->assign('left_css',"1");
			$this->assign('list',$list);
			$this->assign('gamelist',$gameList);

			$this->display();	
		}			
	}
	
	
	public function bind(){
		$row1 = M('game_list');
		import('ORG.Util.Page');
		$Page = new Page($count,15);//实例化分页类传入总记录数和每页显示的记录数		
		$show = $Page->show();// 分页显示输出
		
		$gameList = $row1->where('appId <> 100000')->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
		
		$agent=M('agent');
		if(!empty($_POST)){		
			if(empty($_POST['appId'])){
				$this->error('游戏名字不能为空');
				exit;
			}
			
			if(empty($_POST['leaderId'])){
				$this->error('上级代理id不能为空');
				exit;
			}
						
			$leaderId = $_POST['leaderId'];
			
			$where=array();
			$where["appid"] = $_POST['appId'];
			$where["agentid"] = $_POST['leaderId'];
			$where["status"] = 0;
			$result = $agent->where($where)->select();
		
			if(count($result) != 1){
				$this->error('上级代理不存在');
				exit;
			}
			
			if(empty($_POST['uid'])){
				$this->error('uid 不能为空');
				exit;
			}
			
			if(empty($_POST['phoneNum'])
				||	!is_numeric($_POST['phoneNum']) 
				||  strlen($_POST['phoneNum']) != 11){
				$this->error('电话号码错误');
				exit;
			}
			
			if(empty($_POST['weixin'])){
				$this->error('微信号不能为空');
				exit;
			}
					
			$data=array();
			$data['leaderId']   = $_POST['leaderId'];;
			$game_user = M('game_user');
			$where['appId'] = $_POST['appId'];
			$where['uid'] = $_POST['uid'];
			$result=$game_user->where($where)->save($data);
			if($result){
				$this->success('绑定成功');
			}else{
				$this->error('绑定失败');
			}
		}else{
			$this->assign('left_css',"1");
			$this->assign('list',$list);
			$this->assign('gamelist',$gameList);

			$this->display();	
		}			
	}
	
	public function dituiAdd(){
		$row1 = M('game_list');
		import('ORG.Util.Page');
		$Page = new Page($count,15);//实例化分页类传入总记录数和每页显示的记录数		
		$show = $Page->show();// 分页显示输出
		
		$gameList = $row1->where('appId <> 100000 and appId <> 10000')->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
		
		$agent=M('agent');
		if(!empty($_POST)){
			$appId = $_POST['appId'];
			$agencyId = $_POST['agencyId'];
			if(empty($appId) || $appId == 10000){
				$this->error('游戏名字不能为空');
				exit;
			}
			if(empty($agencyId)
				|| !is_numeric($agencyId) 
				|| strlen($agencyId) != 6){
				$this->error('代理号错误');
				exit;
			}
			
			$this->updateDiTui($appId, $agencyId, 1);
		}else{
			$this->assign('left_css',"1");
			$this->assign('list',$list);
			$this->assign('gamelist',$gameList);

			$this->display();	
		}			
	}
	
	private function updateDiTui($gameId, $agencyId, $isDiTui)
	{
		$url = "http://tllmj.dawx.com/report/UpdateDiTui.php";
		$para = array();
		$para['gameId'] = (int)$gameId;
		$para['agencyId'] =(int)$agencyId;
		$para['isDiTui'] = $isDiTui;
		$result = curlPOST2($url, json_encode($para));
		print $result;
		if (empty($result))
		{
			$this->error('操作失败');
			exit;
		}
		$data = json_decode($result, true);
			
		if ($data['ret'] != 0)
		{
			$this->error($data['msg']);
			exit;
		}
		else
		{
			$this->success('操作成功');
		}
	}

	public function drawQuery(){
		$drawRecord=M('withdraw_money_record');
		$nowTs = time();
		$drawList = $drawRecord->where('apply_ts > ' . $nowTs - 86400* 10 )->order('apply_ts desc')->select();
		
		foreach($drawList as $key => $val){
			$drawList[$key]["apply_date"]= date("Y-m-d H:i:s",$drawList[$key]["apply_ts"]);
			$drawList[$key]["deal_date"]= $drawList[$key]["deal_ts"] > 0 ? date("Y-m-d H:i:s",$drawList[$key]["deal_ts"]):'';			
		}
		$this->assign('left_css',"1");
		$this->assign('list',$drawList);
		$this->display();	
	}
	
	 public function http_post_json($url, $postData)
    {
        $data_string = json_encode($postData,JSON_UNESCAPED_UNICODE);
        //初始化curl
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $header = array();
        $header[] = 'Accept:application/json';
        $header[] = 'Content-Type:application/json;charset=utf-8';
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);

        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode(trim($result), true);
    }
	
	public function drawDealReport($game_id,$agency_id , $trade_no){
		$url = "http://tllmj.dawx.com/impl/drawMoneyReport.php?trade_no=" .$trade_no . "&game_id=" . $game_id . "&agency_id=". $agency_id ;
		$post_data = array();
		$post_data["trade_no"]=$trade_no;
		$res = $this->http_post_json($url,$data);
		return $res["ret"] == 0;
	}
	public function drawDeal(){
		
		$drawRecord=M('withdraw_money_record');
		$nowTs = time();

		$data=array();
		$data['stat']   = 0;
		$data['deal_ts']   = time();

		$trade_no = $_GET['trade_no'];
		$agency_id = $_GET['agency_id'];
		$game_id = $_GET['game_id'];
		
		$where=array();
		$where['trade_no'] = $trade_no;
		$where['agency_id'] = $agency_id;
		$where['game_id'] = $game_id;
		
		if(!$this->drawDealReport($game_id,$agency_id,$trade_no)){
			$this->error("信息同步失败");
			return;
		}
		$result=$drawRecord->where($where)->save($data);
		if(!$result){
			$this->error("失败");
			return;
		}
		
		$this->success("成功");
	}
	
	public function lvl_update(){
		
		$row1 = M('game_list');		
		$gameList = $row1->where('appId <> 10000')->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
		
		$agency_id = $_POST['agencyId'];
		$lvl = $_POST['lvl'];
		$game_id = $_POST['appId'];
		if(empty($agency_id) || empty($lvl)){
			$this->assign('gamelist',$gameList);
			$this->assign('left_css',"1");
		//	$this->assign('list',$list);
			$this->display();
			return ;
		}
		
		$url = "http://tllmj.dawx.com/impl/agency_lvl_update.php?&agency_id=" .$agency_id . "&game_id=" . $game_id . "&lvl=". $lvl ;
		$data=array();
		$res = $this->http_post_json($url,$data);
		if($res["ret"] == 0){
			$this->success();
		}else{
			$this->error("失败");
		}
		
		
	}
	
	
	
	public function auditing(){
		$row1 = M('game_list');
		import('ORG.Util.Page');
		$Page = new Page($count,15);//实例化分页类传入总记录数和每页显示的记录数		
		$show = $Page->show();// 分页显示输出
		
		$gameList = $row1->where('appId <> 100000')->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
		
		$agent=M('agent');
		if(!empty($_POST)){
		
			if(empty($_POST['appId'])){
				$this->error('游戏名字不能为空');
				exit;
			}
			
			if(empty($_POST['agentId'])){
				$this->error('代理id不能为空');
				exit;
			}
					
			$data=array();
			$data['username'] = htmlspecialchars(trim($_POST['username']));
			$data['uid']   = I('uid');
			$data['appid'] =  I('appId');
			$data['agentLevel'] =  0;
			$data['phoneNum']   = I('phoneNum');
			$data['weChat']     = I('weixin');
			$data['superAgentid']     = 0;
			$data['status']     = 0;
			$data['createTs']     = time();
			$data['isDitui']     = 1;
	
			$result=$agent->add($data);
			$this->success('申请成功');
		}else{
						
			$agentApplyTable = M('agent');
			$condition['status'] = 1;
			$list = $agentApplyTable->where($condition)->select();
		
			foreach($list as $key => $val){
				$list[$key]["regDate"]= date("Y-m-d H:i:s",$list[$key]["createTs"]);	
			}
		
			$this->assign('left_css',"1");
			$this->assign('list',$list);
			$this->display('Agent:displayAgentApply');
		
			/*
			$this->assign('left_css',"1");
			$this->assign('list',$list);
			$this->assign('gamelist',$gameList);

			$this->display();	*/
		}			
	}	
}
