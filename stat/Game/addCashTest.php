<?php 
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
	print $data_string . "\n";
        $result = curl_exec($ch);
	print $result . "\n";
        curl_close($ch);
        return json_decode(trim($result));
    }
	
	function sign($uid,$cash,$ts){
		$signKey="dawx_gdmj";
		return md5($uid . $cash . $ts .$signKey);
	}
	
	$postData=array();
	$post["uid"] = 109974;
	$post["cash"] = 100;
	$post["ts"] = time();
	$sign = sign($post["uid"],$post["cash"],$post["ts"]);
	$post["sign"] = $sign;
	$url = "http://gdmj.taoqugame.com/stat/Game/add_cash.php";
	$result = http_post_json($url, $post);
	print_r($result);
?>
