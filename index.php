<?php
header('Content-Type:text/html; charset=utf-8');
//定义一个常量防止非法链接本网站
define("stat",true);
define('APP_DEBUG',True);

//定义 ThinkPHP 框架路径
define( 'THINK_PATH' , './ThinkPHP/' );
//定义项目 名称和路径
define( 'DB_HOST'  , 'http://gdmj.taoqugame.com/'); //设置主机
define( 'APP_NAME' , 'stat' );
define( 'APP_PATH' , './stat/' );
define( 'ROOT_PATH' , dirname($_SERVER['SCRIPT_FILENAME']).'/' );
define( 'CSS_PATH' , DB_HOST.'/Public/css/' );
define( 'JS_PATH' ,  DB_HOST.'/Public/js/' );
//缓存路径
//define( 'APP_PATH' ,  dirname($_SERVER['SCRIPT_FILENAME']).'/WWW/web/' );

date_default_timezone_set("Asia/Shanghai");
if(!isset($_REQUEST["m"])){
	$_REQUEST["m"]="Login";
	$_REQUEST["a"]="login";
	$_GET["m"]="Login";
	$_GET["a"]="login";
	
}
//加载框架入口文件
require './ThinkPHP/ThinkPHP.php';

//?m=Login&a=login

?>
