<?php
// 其它文件

class SaveerrorAction extends Action {

	public function index(){
		
		$ip = get_client_ip();
		$addtime = time();
		$mulu = APP_PATH."Logclient/".date("Ymd");
		if (!file_exists($mulu)) mkdir($mulu,0777);
		
		//判断写入频率
		$logs_file = $mulu."/".$ip."_".date("Ymd").".txt";	
		if (file_exists($logs_file)){
			
			$temp = file_get_contents($logs_file);
			$user_record = json_decode($temp, true);
			if ($addtime - $user_record['addtime'] < 10){
				return $this->answerResquest('-1','时间间隔太密，请稍等再试');
			}
			
			$user_record['addtime'] = $addtime;
			$user_record['num'] = $user_record['num'] + 1;
		}else{
			$user_record = array('addtime'=> $addtime, 'num' => 1);
		}	
		file_put_contents($logs_file, json_encode($user_record));
		
		//记录错误
		$uid = I("uid");
		$ver = I("ver");
		$type = I("type");
		$err = I("err");
		$client_file = $mulu."/client_".date("Ymd").".txt";	
		$record = "\n addtime:".date("Y-m-d H:i:s")."; uid:".$uid."; ver:".$ver."; type:".$type."; err:".$err;
		file_put_contents($client_file, $record, FILE_APPEND);
		return $this->answerResquest('1','错误已级记录写入');
	}
	
	
	//空方法
	 public function _empty() {  
        $this->display("Index/index");
    }
	
	//显示输出
	public function answerResquest($status, $mesage, $data='', $type='json'){
		$msg = array('status' => (int)$status, 'desc' => $mesage);
		if (!empty($data)) $msg = array_merge($msg, $data);
		if ($type == "json"){
			echo json_encode($msg, JSON_UNESCAPED_UNICODE);
		}else{
			echo $msg;
		}
		exit;
	}
}