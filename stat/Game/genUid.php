<?php
/**
 * Created by PhpStorm.
 * User: aaron
 * Date: 2017/9/12
 * Time: 14:59
 */
$post_str = file_get_contents("php://input");
$post=json_decode($post_str,JSON_UNESCAPED_UNICODE);
$open_id = $post["openid"];
$unionId = $post["unionid"];
$sex = $post["sex"];
$nickName=$post["nickname"];

$sql =  sprintf("call sp_user_login('%s', '%s', '', '', '%s', '', %d, '')",
			$open_id,
           		 $unionId,
			$nickName,
			$sex);

file_put_contents("result.txt",$unionId . " " . $open_id . $sql. "\n" ,FILE_APPEND);
$db=new mysqli("10.135.72.229","root","1234","gdmj");
mysqli_query($db,"SET NAMES utf8");
$result=$db->query($sql);
$uid = 0;
while( $row = $result->fetch_array(MYSQLI_ASSOC)) //完成从返回结果集中取出一行
{
    $uid = $row["user_id"];
    break;
}

$result = array();
$result["ret"] = 0;
$result["uid"] = $uid;
echo json_encode($result);
?>
