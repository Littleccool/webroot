<?php
session_start();
header("Content-Type:text/html;charset=utf-8");

$conn1 = mysql_connect("localhost", "root","root","kingflower"); 
mysql_select_db("kingflower", $conn1); 
$conn2 = mysql_connect("huangjiatest.mysql.rds.aliyuncs.com", "hj_test","doojaa2016","zjhmysql"); 
mysql_select_db("zjhmysql", $conn2); 
//本地
$conn1 = mysql_connect("192.168.1.102:3307", "root","dj2015","fenxi_ben"); 
mysql_select_db("fenxi_ben", $conn1); 
$conn2 = mysql_connect("192.168.1.102:3307", "root","dj2015","kingflower"); 
mysql_select_db("kingflower", $conn2); 

$sql = "select * from config";
$res = mysql_query($sql, $conn1);
while ($row = mysql_fetch_array($res)){
	define($row['config_name'], $row['config_value']);
}
$sql1 = "  !((user_id>=".ROBERT1_BEGIN." and user_id<".ROBERT1_END.") or (user_id>=".ROBERT2_BEGIN." and user_id<=".ROBERT2_END."))";

$date2 = !empty($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
$time2 = strtotime($date2);
$time1 = $time2 - 60 * 60 * 24 * 1;
$date1 = date("Y-m-d", $time1);

$table1 = "log_gold_".date("Ymd", $time1);

$sql = "select count(*) as total from fx_tongji1 where data='$date1' and flag=11";
$res = mysql_query($sql, $conn1);
$row = mysql_fetch_array($res);
if ($row['total']==0){
	
	//玩家金币总和
	$sql = "select sum(gold) as sumgold,sum(deposit) as sumposit from user_info where 1 $sql1";
	$res = mysql_query($sql, $conn2);
	$row = mysql_fetch_array($res);
	$sum_jb_play = $row['sumgold']+$row['sumposit'];
	
	//新增玩家
	$sql = "select tongji from fx_tongji6 where data='$date1' and flag=7 and version='all' and channel='all'";
	$res = mysql_query($sql, $conn1);
	$row = mysql_fetch_array($res);
	if (empty($row['tongji'])){
		$sql4 =  "";
		$count1 = 0;
	}else{
		$sql4 = " and user_id in (".$row['tongji'].")";
		$temp = explode(",", $row['tongji']);
		$count1 = count($temp);
	}
	
	//新增玩家统计初始化
	$sum1 = array(0,0,0,0,0,0,0);
	//活跃玩家统计初始化
	$sum2 = array(0,0,0,0,0,0);
	
	if (!empty($sql4)){
		//新用户领取1,2,3次破产次数
		$sql11 = "SELECT COUNT(user_id) AS total FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum1[0] = $row11['total'];
		
		//新用户领取1,2,3次破产人数
		$sql11 = "SELECT COUNT(distinct user_id) AS total FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum1[1] = $row11['total'];
		
		//新用户破产率
		$sum1[2] = ($count1==0) ? 0 : round($sum1[1]/$count1, 3);
		
		//新用户领取2,3次破产的次数
		$sql11 = "SELECT sum(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums>=2";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum1[3] = $row11['total'];
		
		//新用户领取2,3次破产的人数
		$sql11 = "SELECT COUNT(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums>=2";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum1[4] = $row11['total'];
		
		//新用户领取3次破产的次数
		$sql11 = "SELECT sum(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums=3";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum1[5] = $row11['total'];
		
		//新用户领取3次破产的人数
		$sql11 = "SELECT COUNT(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums=3";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum1[6] = $row11['total'];
	}
	
	//活跃玩家
	$table1 = "log_login_".date("Ymd", $time1);
	$sql = "select distinct user_id from $table1 where 1 $sql1";
	$sql4 = "";
	$res = mysql_query($sql, $conn1);
	while ($row = mysql_fetch_array($res)){
		$sql4 .= (empty($sql4)) ? $row['user_id'] : ",".$row['user_id'];
	}
	if (!empty($sql4)) $sql4 = " and user_id in ($sql4)";
	
	if (!empty($sql4)){
		//活跃玩家领取1,2,3次破产次数
		$sql11 = "SELECT COUNT(user_id) AS total FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum2[0] = $row11['total'];
		
		//活跃玩家领取1,2,3次破产人数
		$sql11 = "SELECT COUNT(distinct user_id) AS total FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum2[1] = $row11['total'];
		
		//活跃玩家领取2,3次破产的次数
		$sql11 = "SELECT sum(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums>=2";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum2[2] = $row11['total'];
		
		//活跃玩家领取2,3次破产的人数
		$sql11 = "SELECT COUNT(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums>=2";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum2[3] = $row11['total'];
		
		//活跃玩家领取3次破产的次数
		$sql11 = "SELECT sum(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums=3";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum2[4] = $row11['total'];
		
		//活跃玩家领取3次破产的人数
		$sql11 = "SELECT COUNT(nums) AS total FROM (SELECT COUNT(user_id) AS nums FROM $table1 WHERE module=4 and (curtime>=$time1 AND curtime<$time2) $sql4 GROUP BY user_id ) AS t1 WHERE nums=3";
		$res11 = mysql_query($sql11, $conn1);
		$row11 = mysql_fetch_array($res11);
		$sum2[5] = $row11['total'];
	}
	

	
	//print_r($sum2);
	//循环结束
	$tongji = array('date' => $date1,
					'sum_jb_play' => $sum_jb_play,
					'sum1' => $sum1,
					'sum2' => $sum2
					);
	//print_r($tongji);		
	//更新到数据库
	$sql13 = "insert into fx_tongji1 (data, tongji, addtime, flag) values ('$date1', '".json_encode($tongji)."', '".time()."', '11')";	
	mysql_query($sql13, $conn1);
	
	echo "1";
}else{
	echo "0";
}
?>