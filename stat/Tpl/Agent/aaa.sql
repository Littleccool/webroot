DROP TABLE IF EXISTS  `mj_userinfo`;
CREATE TABLE `mj_userinfo` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `level` int(11) NOT NULL COMMENT '用户等级',
  `score` int(11) NOT NULL COMMENT '积分 ',
  `card` int(11) NOT NULL COMMENT '房卡',
  `imei` varchar(64) NOT NULL COMMENT 'imei号',
  `user_name` varchar(64) NOT NULL COMMENT '用户名',
  `union_id` varchar(64) NOT NULL COMMENT '公众号id',
  `nick_name` varchar(64) NOT NULL COMMENT '昵称',
  `head_pic` varchar(256) NOT NULL COMMENT '头像地址',
  `last_login_time` varchar(32) NOT NULL COMMENT '最后一次登陆时间',
  `sex` tinyint(4) NOT NULL COMMENT '性别',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=107741 DEFAULT CHARSET=utf8mb4;

DROP PROCEDURE IF EXISTS `sp_user_login`;
DELIMITER $$
CREATE PROCEDURE `sp_user_login`(
		in v_user_name varchar(64) character set utf8,
		in v_union_id varchar(64) character set utf8,
		in v_imei varchar(64),
		in v_version varchar(8),
		in v_nickname varchar(64) character set utf8mb4,
		in v_headpic varchar(256),
		in v_sex INT(11)
)
    COMMENT '登陆'
BEGIN
	#功能:   用户登录
 	#参数    v_user_name 用户名  v_union_id 公众号id v_version 客户端版本   v_imei    v_nickname 昵称    v_headpic 头像   v_sex 性别
	#返回  0 成功 



#返回参数
	DECLARE  ret_user_id INT;
	DECLARE  ret_level INT DEFAULT  0;
	DECLARE  ret_score INT  DEFAULT  0;
	DECLARE  ret_card INT  DEFAULT  0;
	DECLARE  ret_head_pic VARCHAR(256);
	DECLARE  ret_nick_name VARCHAR(64) character set utf8mb4;
	DECLARE  ret_user_name VARCHAR(64) character set utf8;
	DECLARE  ret_union_id VARCHAR(64) character set utf8;
	DECLARE  ret_sex INT DEFAULT  0;

	SELECT  user_id, user_name INTO ret_user_id, ret_user_name FROM `mj_userinfo`  WHERE user_name  = v_user_name  LIMIT  1;
	
	IF (ret_user_id IS NULL) THEN 
		INSERT INTO  `mj_userinfo`(`level` , `score` , `card` ,`imei` , `user_name`, `union_id`, `nick_name`,`head_pic`,`sex`,`last_login_time` ) VALUES(1, 0, 38, v_imei, v_user_name, v_union_id, v_nickname, v_headpic,v_sex, UNIX_TIMESTAMP());
		SET  ret_user_id = LAST_INSERT_ID();
	ELSE 
	    UPDATE  `mj_userinfo`  SET last_login_time = UNIX_TIMESTAMP() WHERE `user_id`  = ret_user_id LIMIT  1;
	END IF;
	SELECT user_id,level,score, card,user_name, union_id, nick_name, head_pic, sex INTO ret_user_id,ret_level,ret_score,ret_card,ret_user_name, ret_union_id, ret_nick_name, ret_head_pic, ret_sex from `mj_userinfo` WHERE  user_name = v_user_name;
 
	SELECT  0 as result, ret_user_id as user_id, ret_level as level, ret_score as score, ret_card as card , ret_head_pic as head_pic, ret_nick_name as nick_name, ret_user_name as user_name, ret_union_id as union_id, ret_sex as sex;

END
$$
DELIMITER ;
