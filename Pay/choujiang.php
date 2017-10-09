<?php
$type = !empty($_POST['type']) ? $_POST['type'] : $_GET['type'];
$uid = !empty($_POST['uid']) ? $_POST['uid'] : $_GET['uid'];
if (empty($type)) $type = "1";
$HOST = "114.119.37.179";
$LOGIN = "root";
$PWD = "dj_zjh_2015";
$NAMES = "kingflower";

$HOST = "192.168.1.102:3307";
$LOGIN = "root";
$PWD = "dj2015";
$NAMES = "kingflower";
$conn = mysql_connect($HOST, $LOGIN, $PWD);
mysql_query("SET NAMES utf8");
mysql_select_db($NAMES);

	$sql = "select * from user_lotterydraw_record order by wingold desc limit 3";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	echo json_encode($row, JSON_UNESCAPED_UNICODE);
	//print_r($user);
	//print_r($user);




?>