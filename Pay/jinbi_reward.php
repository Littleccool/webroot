<?php
require("connect.php");

$id = $_GET['id'];
$sql = "select * from pay_reward_info where order_code=".$id;
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$diamond = 0;
$gold = 0;
if ($row['cate'] == 2){
	$diamond = $row['result_reward'];
}else{
	$gold = $row['result_reward'];
}
$user_id = $row['user_id'];
//$url = get_url($conn);
$sql = "select config_value from config where config_name='NOTICE_IP'";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$url = $row['config_value'];
//$url = "123.59.46.6:9002";
//$url = "113.91.173.211:9002";
//echo $url."<br>"; //exit;
if (!empty($user_id)){
	$para = array();
	$para['order'] = $id;
	$para['userId'] = (int)$user_id;
	$para['goldnum'] = (int)$gold;
	$para['diamond'] = (int)$diamond;
	$para['deposit'] = 0;
	$para['type'] = 1;

	//echo json_encode($para);
	//exit;
	$result = curlPOST2($url, json_encode($para));   
}
   				
?>