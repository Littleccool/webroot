<?php
// 在线分析文件
header('Content-Type:text/html; charset=utf-8');
class StatAction extends BaseAction {

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
	
	
	public function returnRate(){
		$returnRateTable = M('return_rate');
		$row1 = M('game_list');
		$appId=I('appId');
		
		//$list =  = $returnRateTable->where('appId = $appId')->select();
		if(!empty($appId)){
			$list = $returnRateTable->where('appId=' . $appId)->order('date DESC')->select();
		}
		$gameList = $row1->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
				
		foreach($list as $key => $val){
			$id = $val["appId"];
			$list[$key]["name"]= $gameMap[$appId]; 						
			for($index = 1 ;$index < 31;++$index){
				$day = "d". $index;
				$list[$key][$day]= $list[$key][$day]*100 . "%"; 						
			}
		}
		
		$this->assign('left_css',"1");
		$this->assign('list',$list);
		$this->assign('gamelist',$gameList);

		$this->display('Stat:returnRate');
	}
	
	public function keyData(){
		$row = M('key_data');
		$appId=I('appId') ? I('appId') : '10000';
		$row1 = M('game_list');

		import('ORG.Util.Page');
		$count = $row->where(array('appId'=>$appId))->count();
		$Page = new Page($count,15);//实例化分页类传入总记录数和每页显示的记录数		
		$show = $Page->show();// 分页显示输出
		//$list =  = $returnRateTable->where('appId = $appId')->select();
		if(!empty($appId)){
			$list = $row->where('appId=' . $appId)->order('date DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		//$list = $row->select();
		$gameList = $row1->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
		
		foreach($list as $key => $val){
			$id = $val["appId"];
			$list[$key]["name"] = $gameMap[$appId];
			$list[$key]['fee']=$list[$key]['fee']/100;
			$list[$key]["arpu"] =round($list[$key]['feeCount']>0 ?$list[$key]['fee']/$list[$key]['feeCount'] : 0,2);
			$list[$key]['feeRate'] = $list[$key]['dau'] > 0 ? round($list[$key]['feeCount'] / $list[$key]['dau'] * 100,2) : 0;		
		}
		
		$this->assign('left_css',"1");
		$this->assign('list',$list);
		$this->assign('pageshow',$show);
		$this->assign('gamelist',$gameList);

		$this->display('Stat:keyData');
	}
	
	public function chargeDis(){
		$row = M('charge_distribute');
		$row1 = M('game_list');
		$appId=I('appId');
		$appId = $appId==0 ? 0 :$appId;
		import('ORG.Util.Page');
		$count = $row->count('appId');
		$Page = new Page($count,15);//实例化分页类传入总记录数和每页显示的记录数		
		$show = $Page->show();// 分页显示输出
		
		$gameList = $row1->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
			
		if (!(empty($appId))){	
			$list = $row->where('appId = ' . $appId)->select();
			$fields = array();
			$index = 0;
			$listCharge=array();
			foreach($list as $key => $val){
				$id = $val["appId"];
				$list[$key]["name"]= $gameMap[$appId]; 
				foreach($val as $key1=>$value1) {
					if($key1 != 'appId'){
						$list[$key]["count"] += $value1;
					}
				}
				
				$index++;
			}
		}
		
		$this->assign('fields',$fields);
		$this->assign('gamelist',$gameList);
		$this->assign('left_css',"1");
		$this->assign('list',$list);
		$this->assign('pageshow',$show);

		$this->display('Stat:chargeDis');
	}
	
	public function balanceDis(){
		$row = M('balance_distribute');
		$row1 = M('game_list');
		$appId=I('appId');
		$appId = $appId==0 ? 0 :$appId;
		import('ORG.Util.Page');
		$count = $row->count('appId');
		$Page = new Page($count,15);//实例化分页类传入总记录数和每页显示的记录数		
		$show = $Page->show();// 分页显示输出
		
		$gameList = $row1->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
			
		if (!(empty($appId))){			
			$list = $row->where('appId = ' . $appId)->select();
			
			$fields = array();
			$index = 0;
			$listCharge=array();
			foreach($list as $key => $val){
				$id = $val["appId"];
				$list[$key]["name"]= $gameMap[$appId]; 
				
				foreach($val as $key1=>$value1) {
					if($key1 != 'appId'){
						$list[$key]["count"] += $value1;
					}
				}
				
				$index++;
			}
		}
		
		$this->assign('fields',$fields);
		$this->assign('gamelist',$gameList);
		$this->assign('left_css',"1");
		$this->assign('list',$list);
		$this->assign('pageshow',$show);

		$this->display('Stat:balanceDis');
	}
	
	public function payStat(){		
		$row1 = M('game_list');
		$gameList = $row1->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$gameMap[$val["appId"]]=$val["name"];
		}
		
		$appId=I('appId');
		$appId = $appId==0 ? 0 :$appId;
		
		for($index = 0; $index < 50; ++$index){
			$array[$index]=$index+1;
		}
		
		$count = count($array);
		$total_money = 0;
		$total_arr = array("person" => array(), "money" => array());
		$date = `date -d '1 days ago' +%Y%m%d`;
		$date = rtrim($date);
		$file = "/usr/local/script/data/npop/newuser_pay_$appId_$date";
		$handle = fopen($file, "r");
		$index = 0;
		while(!feof($handle)) { // 循环显示每一个行
		$str = fgets($handle);
		$list = explode(",", $str);
		$size = count($list);
		if($size < 3) {	// 数据文件格式有误
			break;
		}

	//if(intval($list[0]) < $day_start_int || intval($list[0]) > $day_end_int) { // 该行不在查询范围内
	//	continue;
	//}
	
		foreach($array as $day) { // 计算最后一行的 Total 数据
			if($day <= $size -2) {
				$total_arr["person"][$day - 1] += $list[1];
				$total_arr["money"][$day - 1] += $list[$day + 1];
			}
		}
		$total_money += $list[$size -1]; 
		$payInfo[$index]["name"] = $gameMap[$appId];
		$payInfo[$index]["date"] = $list[0];
		$payInfo[$index]["dnu"] = $list[1];
		$payInfo[$index]["total"] = $list[$size -1] / 100;
		$payInfo[$index]["avg"] = round($list[1] ? $list[$size -1] / 100 / $list[1] : 0,2);
		$index1 = 0;
		for($i = 0; $i < $count; ++$i) {
			if($i <= $size - 3) {
				$payInfo[$index]["total" . $i] = $list[$i+2] / 100;
				$payInfo[$index]["avg" . $i] = round($list[1] ? $list[$i+2] / 100 / $list[1] : 0,2);
				
				$payInfo[$index]["days"][$index1++] = $list[$i+2] / 100;
				$payInfo[$index]["days"][$index1++] = round($list[1] ? $list[$i+2] / 100 / $list[1] : 0,2);
			} else {
				$payInfo[$index]["total" . $i] = '-';
				$payInfo[$index]["avg" . $i] = '-';
				
				$payInfo[$index]["days"][$index1++] = '-';
				$payInfo[$index]["days"][$index1++] = '-';
			}
		}
		++$index;
	}
	fclose($handle);

// 显示最后一行的 Total 数据
	$total_person = $total_arr["person"][0] ? $total_arr["person"][0] : 0;
	$totalArray[0]["person"] = $total_person;
	$totalArray[0]["total"] = $total_money / 100;
	$totalArray[0]["avg"] = round($total_person ? $total_money / 100 / $total_person : 0,2);

	$index = 0;
	for($i = 0; $i < $count; ++$i) {
		if($total_arr["person"][$i]) {
			$totalArray[0]["total" . $i ] = $total_arr["money"][$i] / 100;
			$totalArray[0]["avg" . $i ] = round($total_arr["person"][$i] ? $total_arr["money"][$i] / 100 / $total_arr["person"][$i] : 0,2);
			
			$totalArray[0]["days"][$index++] = $total_arr["money"][$i] / 100;
			$totalArray[0]["days"][$index++] = round($total_arr["person"][$i] ? $total_arr["money"][$i] / 100 / $total_arr["person"][$i] : 0,2);

		} else {
			$totalArray[0]["total" . $i ] = '-';
			$totalArray[0]["avg" . $i ] = '-';
			
			$totalArray[0]["days"][$index++] = '-';
			$totalArray[0]["days"][$index++] = '-';

		}
	}
			

			$this->assign('payList',$payInfo);
			$this->assign('totalList',$totalArray);

			$this->assign('dayList',$array);
			$this->assign('gamelist',$gameList);
			$this->display('Stat:payStat');
}

	public function userInfo(){

		$appId=I('appId');
		$uid = I('uid');		
		$row = M('game_user');
		$row1 = M('game_list');
		$rowPay = M('pay_history');
		

			
		$gameList = $row1->where("appId <> 10000")->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$id = $val["appId"];
			if($id != 0){
				$gameMap[$id]=$val["name"];
			}
		}
			
		if (!empty($uid) && !(empty($appId))){

			$where["appId"]=$appId;
			$where["uid"]=$uid;
			$payList = $rowPay->where($where)->select();
			$totalPay = 0;
			foreach($payList as $key => $val){
				$totalPay += $val["rmb"];				
			}	
			
			$list = $row->where($where)->select();	
			foreach($list as $key => $val){
				$list[$key]["gameName"]= $gameMap[$appId]; 
				$list[$key]["wxId"]= "无"; 
				$list[$key]["name"]= $list[$key]["nickName"]; 
				$list[$key]["rmb"]= round($totalPay/100,2);
				$list[$key]["regDate"]= date("Y-m-d H:i:s",$list[$key]["regTs"]);	
			}			
		}
		

				
		$this->assign('gamelist',$gameList);
		$this->assign('left_css',"1");
		$this->assign('list',$list);

		$this->display('Stat:userInfo');
	}
	
	
	public function userInfoList(){
		
	//	header("Content-Type: text/html;charset=utf-8"); 
		$appId=I('appId');
		$row = M('game_user');
		$row1 = M('game_list');	
		$gameList = $row1->where("appId <> 10000")->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$id = $val["appId"];
			if($id != 0){
				$gameMap[$id]=$val["name"];
			}
		}
			
		if (!(empty($appId))){
			

		
			if($appId > 10000){
				$where["appId"]=$appId;			
			}

			$list = $row->where($where)->select();	
			$index = 0;
			foreach($list as $key => $val){
				$list[$key]["index"]= ++$index; 
				$list[$key]["gameName"]= $gameMap[$appId]; 
				//$list[$key]["wxId"]= "无"; 
				$list[$key]["regDate"]= date("Y-m-d H:i:s",$list[$key]["regTs"]);	
			}


			$xlsName  = "玩家基本信息-" . $gameMap[$appId];
			$xlsCell  = array(
			array('index','序号'),
			array('gameName','游戏名字'),
			array('uid','uid'),
			array('nickName','用户名字'),
			array('agentId','代理id'),
			array('leaderId','上级代理'),
			array('rmb','充值金额'),
			array('balance','余额'),
			array('regDate','注册时间'),  
			);
			$xlsData = array();
			foreach ($list as $k => $v)
			{
				$xlsData[$k]['index'] = $k + 1;
				$xlsData[$k]['uid'] = $v['uid'];
				//echo $v['nickName'] . "</br>";
				$xlsData[$k]["gameName"]= $gameMap[$v['appId']]; 
				$xlsData[$k]['nickName'] = $v['nickName'];
				$xlsData[$k]['regDate'] = $v['regDate'];
				$xlsData[$k]['rmb'] = $v['rmb']/100;
				$xlsData[$k]['balance'] = $v['balance'];
				$xlsData[$k]['leaderId'] = $v['leaderId'];
				$xlsData[$k]['agentId'] = $v['agentId'];
				//$xlsData[$k]['contact'] = $v['contact'];
			}

			exportExcel($xlsName,$xlsCell,$xlsData);	
		}
		
		
		
				
		$this->assign('gamelist',$gameList);
		$this->assign('left_css',"1");
		$this->assign('list',$list);

		$this->display('Stat:userList');
	}
	
	
	public function chargeDetail(){
		$appId=I('appId');
		$row1 = M('game_list');	
		$gameList = $row1->where("appId <> 10000")->select();
		$gameMap = hash();
		foreach($gameList as $key => $val){
			$id = $val["appId"];
			if($id != 10000){
				$gameMap[$id]=$val["name"];
			}
		}
		
		$totalInfo=array();
		$totalInfo[0]["rmb"] = 0;
		$totalInfo[0]["cash"] = 0;
		$totalInfo[0]["times"] = 0;
		$totalInfo[0]["person"] = 0;
				
		if (!(empty($appId))){
			if($appId > 10000){
				$where["appId"]=$appId;			
			}
			$start_time=I('start_time');
			$timeStamp = empty($start_time) ? time(NULL) : strtotime($start_time);

		//	$list = $row->where($where)->select();	
			$Model = new Model();
			$sql = "select A.uid ,A.nickName ,B.rmb ,B.cash,from_unixtime(B.chargeTs) ts from game_user A,pay_history B where B.chargeTs > $timeStamp  and B.chargeTs < $timeStamp + 86400 and A.appId=B.appId and A.uid=B.uid  and B.appId = $appId order by ts desc";
		
			$voList = $Model->query($sql);
			$uids=array();	
			foreach($voList as $key => $val){
				if($voList[$key]["rmb"] <= 0){
					continue;
				}
				$voList[$key]["rmb"]= $voList[$key]["rmb"]/100;
				$uid = $voList[$key]["uid"];
				
				if(!in_array($uid, $uids)){
					$totalInfo[0]["person"] ++;
					
				}
					
				array_push($uids,$uid);
				$totalInfo[0]["rmb"] += $voList[$key]["rmb"];
				$totalInfo[0]["cash"] += $voList[$key]["cash"];
				$totalInfo[0]["times"]+=1;

			}
		}
		
		
		
				
		$this->assign('gamelist',$gameList);
		$this->assign('left_css',"1");
		$this->assign('list',$voList);
		$this->assign('totalInfo',$totalInfo);

		$this->display();
	}
	
}
