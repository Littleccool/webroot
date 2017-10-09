<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
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
//需要统计游戏局数
$ju = array(array(0,0),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,10),array(11,20),array(21,30),array(31,50),array(51,10000));
//需要统计的房间
$roomid = array(1,2,3,6,7);

//记录分析数据
$sql = "select count(*) as total from fx_tongji1 where data='$date1' and flag=11 and version='all' and channel='all'";
$res = mysql_query($sql, $conn1);
$row = mysql_fetch_array($res);
if ($row['total'] == 0){
	
	$tongji = array();
	foreach($roomid as $key => $val){
		$tongji[$key][$val] = array();
		foreach($ju as $key1 => $val1){
			$tongji[$key][$val][$key1]['game'] = json_encode($val1);
			$tongji[$key][$val][$key1]['count'] = 0;
		}
		$sql2 = "SELECT COUNT(user_id) as sum01 FROM log_game_".date("Ymd", $time1)." WHERE $sql1 and roomid=$val GROUP BY user_id";
		echo $sql2."<br>";
		$res2 = mysql_query($sql2, $conn1);
		while ($row2 = mysql_fetch_array($res2)){
			foreach($ju as $key1 => $val1){
				
				if ($row2['sum01']>=$val1[0] and $row2['sum01']<=$val1[1]){
					echo $val1[0]."**".$val1[1]."**".$row2['sum01']."<br>";
					$tongji[$key][$val][$key1]['count']++;
				}
			}
		}
	}
	
	print_r($tongji);
}

?>