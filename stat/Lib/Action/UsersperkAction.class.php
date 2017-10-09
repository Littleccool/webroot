<?php
// 客服聊天文件

class UsersperkAction extends Action {

	public function index(){
		
		$table = M("user_sperk");
		
		Log::write(json_encode($_POST),'INFO');
		
		$post_str = json_decode($_POST['params'], true);
		
		$message = $post_str["message"]; 
		$uid = $post_str["uid"];
		$ver = $post_str["ver"];
		
		/*
		if ($ver!="1.0.8"){
			$post_data = iconv("GBK", "UTF-8", $_POST['params']);
			$post_str = json_decode($post_data, true);
			$message = $post_str["message"]; 
		}
		*/
		
		//$savedata = $_POST['params']."***".$post_data."***".time();
		//$put_file = APP_PATH."Logs/sperk_".time().".txt";
		//file_put_contents($put_file, $savedata);
		
		if (empty($message)){
			echo -1; 
			exit;
		}
		
		//$put_data = $PostData;  
		
		
		$tsnow = time();
		$data = array();
		$data['uid'] = $uid;
		$data['ver'] = $ver;
		$data['message'] = $message; 
		$data['addtime'] = $tsnow;
		$result = $table->add($data);
		//dump($table->_sql());
		if ($result){
			/*
			$show = array();
			$list = $table->where("uid=".$_POST['uid']." and ver=".$_POST['ver'])->order('addtime,id')->limit(0,20)->select();
			foreach($list as $key => $val){

				$showuid = ($val['pant_id']==0) ? (int)$val['uid'] : 1;
				$show[$key] = array($showuid,(int)$val['addtime'],$val['message']);
			}
			$pubtext = array('msg' => $show,
							 'ts' => $tsnow);
			$showlist = json_encode($pubtext, JSON_UNESCAPED_UNICODE);
			echo $showlist; */
			//如果是当天第1条，系统自动回复
			//判断是否是当天第1条
			$today = strtotime(date("Y-m-d"));
			$tomrrow = $today + 86400;
			$count = $table->where("uid=$uid and pant_id=0 and (addtime>=$today and addtime<$tomrrow)")->count();
			Log::write($table->_sql()."count:$count",'INFO');
			if ($count == 1){
				/*$row = M("config");
				$list = $row->select();
				foreach ($list as $key => $val){
					define($val['config_name'], $val['config_value']);
				}
				
				$data = array();
				$data['uid'] = $uid;
				$data['message'] = CUSTOMER_AUTO_RECALL;
				$data['pant_id'] = 1;
				$data['ver'] = $ver;
				$data['addtime'] = time();
				$result = $table->add($data);
				
				Log::write(json_encode($data),'INFO');*/
			}
			
			
			echo 1;
		}else{
			echo -1;
		}
		exit;
		
	}
	
	public function myrecord(){
		
		$table = M("user_sperk");
		
		Log::write(json_encode($_POST),'INFO');
		
		//$_POST['params'] = '{"ver":"2.2","uid":10377432,"ts":1473229442}';
		$post_str = json_decode($_POST['params'], true);
		if (!empty($post_str)){
			$uid = $post_str["uid"];
			$ver = $post_str["ver"];
			$ts = $post_str["ts"];
		}else{
			$uid = I('uid');
			$ver = I('ver');
			$ts = I('ts');
		}
		
		$ts = empty($ts) ? 0 : $ts;
		
		if (!empty($uid)){
			if (!empty($ts)) $sql = " and pant_id!=0 and addtime>$ts"; else $sql = "";
			$tsnow = 0;
			$list = $table->where("uid=".$uid." and ver='".$ver."'".$sql)->order('addtime,id')->limit(0,20)->select();
			//dump($table->_sql());
			$show = array();
			foreach($list as $key => $val){

				if ($tsnow < $val['addtime']) $tsnow = $val['addtime'];
				$showuid = ($val['pant_id']==0) ? (int)$val['uid'] : 1;
				$show[$key] = array($showuid,(int)$val['addtime'],$val['message']);
			}
			
			if ($tsnow >= $ts){
				$pubtext = array('msg' => $show,
								 'ts' => (int)$tsnow);
				$showlist = json_encode($pubtext, JSON_UNESCAPED_UNICODE);
				echo $showlist; 
			}else{
				echo -1;
			}

		}else{
			echo -1;
		}
		exit;
		
	}
	
	
	
	//空方法
	 public function _empty() {  
        $this->display("Index/index");
    }
}