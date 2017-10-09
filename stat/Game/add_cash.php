<?php 

 /**
     * @param $url
     * @param $postData
     * @return mixed
     */
    function http_post_json($url, $postData)
    {
        $data_string = json_encode($postData,JSON_UNESCAPED_UNICODE);
        //初始化curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
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
        return json_decode($result);
    }
	
	function success(){
		$result=array();
		$result["code"] = 0;
		$result["msg"] = "success";
		echo json_encode($result);
		exit;
	}
	
	function failed($code,$msg){
		$result=array();
		$result["code"] = $code;
		$result["msg"] = $msg;
		echo json_encode($result);
		exit;
	}

	function busilog($line){
		$dateStr=date('Ymd H:i:s');
		$ss_log_filename = '/data/tmp/busilog/admin_cash.' . date('Ymd') . '.log';
		$line =  $dateStr ."|". $line;
		file_put_contents($ss_log_filename, $line. "\n", FILE_APPEND);
	}	
	function checkSign($userId,$cash,$ts,$sign){
		$signKey="dawx_gdmj";
		return md5($userId . $cash . $ts .$signKey) == $sign;
	}
	
	$post_str = file_get_contents("php://input");
	$post=json_decode($post_str,JSON_UNESCAPED_UNICODE);
	$userId = $post["uid"];
	$cash = $post["cash"];
	$ts = $post["ts"];
	$sign = $post["sign"];
	if(!checkSign($userId,$cash,$ts,$sign)){
		failed(1,"sign error " );
	}
	
	$para = array();
	$para['userId'] = (int)$userId;
	$para['cardnum'] = (int)$cash;
	$para['type'] = 1;
	$url = "http://10.135.72.229:6021/";
	$result = http_post_json($url, $para);
	$line = $userId . "|" . $cash;
	busilog($line);	
	if($result["ret"] == 0){
		success();
	}else{
		failed(2,"add cash failed");
	}
?>
