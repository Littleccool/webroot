<?php
header("Content-Type: text/html;charset=utf-8");

$conn1 = mysql_connect("192.168.1.252:3307", "root","dj2015","fenxi_ben"); 
mysql_select_db("fenxi_ben", $conn1); 
mysql_query("SET NAMES utf8");
$conn2 = mysql_connect("192.168.1.252:3307", "root","dj2015","kingflower"); 
mysql_select_db("kingflower", $conn2); 
mysql_query("SET NAMES utf8");

/*
$conn1 = mysql_connect("localhost", "root","xiaofuyong","kingflower"); 
mysql_select_db("kingflower", $conn1); 
mysql_query("SET NAMES utf8");
$conn2 = mysql_connect("huangjiatest.mysql.rds.aliyuncs.com", "huangjia","doojaa2016","zjhmysql"); 
mysql_select_db("zjhmysql", $conn2); 
mysql_query("SET NAMES utf8");
*/

$sql = "select group_concat(user_id) as all_user_id from pay_reward where status=1";
$res = mysql_query($sql, $conn1);
$row = mysql_fetch_array($res);
$all_user_id = $row['all_user_id'];

$time1 = strtotime(date("Y-m-d"));
$time2 = $time1 + 86400;

$sql = "select group_concat(order_code) as all_order_code from pay_reward_info where (addtime>=$time1 and addtime<$time2)";
$res = mysql_query($sql, $conn1);
$row = mysql_fetch_array($res);
$all_order_code = $row['all_order_code'];

$sql1 = "";
if (!empty($all_user_id)) $sql1 .= " and user_id in ($all_user_id)";
if (!empty($all_order_code)) $sql1 .= " and order_code not in ($all_order_code)";
$sql = "select * from pay_now_config.zjh_order where (order_create_time>=$time1 and order_create_time<$time2) and payment_status in (1,-2) ".$sql1;
//echo $sql."<br>";
$res = mysql_query($sql, $conn1);
while ($row = mysql_fetch_array($res)){
	
	$sql1 = "select * from pay_reward where user_id=".$row['user_id'];
	$res1 = mysql_query($sql1, $conn1);
	$row1 = mysql_fetch_array($res1);
	
	$result_reward = $row['result_money'] / 100 * $row1['bili'];
	
	$sql0 = "insert into pay_reward_info (reward_id, user_id, bili, cate, order_code, result_money, result_reward, addtime) values ('".$row1['id']."', '".$row['user_id']."','".$row1['bili']."','".$row1['cate']."','".$row['order_code']."','".$row['result_money']."','".$result_reward."', '".time()."')";
	$result = mysql_query($sql0, $conn1);
	if ($result > 0){
		//通知服务器
		$url = "http://".$_SERVER['HTTP_HOST']."/Pay/jinbi_reward.php?id=".$row['order_code'];
		//echo $url."<br>"; 
		$jinbi_result = curlGET($url);
		//echo $jinbi_result; //exit; 
		$len = strlen($jinbi_result)-3;
		$notify_status = substr($jinbi_result,$len,1);
		//echo $notify_status; exit; 
		//修改通知状态  notify_status=1,notify_times=notify_times+1,notify_date=".time()."
		if ($notify_status == "1"){
			$sql0 = "update pay_reward_info set notify_status=1,notify_date=".time().",notify_times=notify_times+1 where order_code=".$row['order_code']." limit 1";
			//echo $sql0."<br>"; 
			mysql_query($sql0, $conn1);
		}
	}
}
echo "OK";
/**
 * curl POST
 */
function curlPOST($url, $para) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl,CURLOPT_POST,true); // post传输数据
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 超时时间
    $para = http_build_query($para);
    curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
    $responseJson = curl_exec($curl);
    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);

    return $responseJson;
}

/**
 * curl POST
 */
function curlPOST2($url, $para) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl,CURLOPT_POST,true); // post传输数据
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 超时时间
    //$para = http_build_query($para);
    curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
    $responseJson = curl_exec($curl);
    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);

    return $responseJson;
}

/**
 * curl GET
 */
function curlGET($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 超时时间
    $responseText = curl_exec($curl);
    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);

    return $responseText;
}
?>