<?php
/**
 * Created by PhpStorm.
 * User: aaron
 * Date: 2017/9/13
 * Time: 15:22
 */
$post_str = file_get_contents("php://input");
$post=json_decode($post_str,JSON_UNESCAPED_UNICODE);

// 代理id    代理昵称     申请时间             金额        发款时间              操作
// 1058778   扮猪吃老虎   2017/09/12/15:30      200         2017/09/12/18:30    确认提现（已提）
$trade_no=$post["trande_no"];
$game_id=$post["game_id"];
$agency_id = $post["agency_id"];
$name = $post["name"];
$wx_no = $post["wx"];
$money=$post["amount"];
$stat = 1;
$ts = $post["ts"];
$result = array();

$sql= sprintf("insert into withdraw_money_record(trade_no,game_id,agency_id,name,wx_no,amount,stat,apply_ts,deal_ts) values ('%s',%u,%u,'%s','%s',%d,%d,%u,%u)",$trade_no,$game_id,$agency_id,$name,$wx_no,$money,$stat,$ts,0);
file_put_contents("result.txt", $sql . "\n" ,FILE_APPEND);

$db=new mysqli("10.135.72.229","root","1234","stat");
mysqli_query($db,"SET NAMES utf8");
if ($db->query($sql) == TRUE) {
    $result["ret"] = 0;
    $result["msg"] = "success";
} else {
    $result["ret"] = -1;
    $result["msg"] = "failed";
}
$db->close();
echo json_encode($result);
?>
