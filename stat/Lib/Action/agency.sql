-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: sg_recharge
-- ------------------------------------------------------
-- Server version	5.1.73

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agency`
--

DROP TABLE IF EXISTS `agency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency` (
  `game_id` int(10) unsigned NOT NULL COMMENT 'æ¸¸æˆç¼–å·ï¼Œè‡ªå®šä¹‰',
  `union_id` char(32) NOT NULL COMMENT 'çŽ©å®¶unionId',
  `game_uid` int(10) unsigned NOT NULL COMMENT 'ç”¨æˆ·åœ¨æŸä¸ªæ¸¸æˆé‡Œçš„idï¼Œå‘æ¸¸æˆæœåŠ¡å™¨èŽ·å–',
  `upper_id` int(10) unsigned NOT NULL COMMENT 'ä¸Šçº§ä»£ç†agency_id',
  `level` tinyint(3) unsigned DEFAULT '0' COMMENT 'ä»£ç†ç­‰çº§',
  `bind_ts` int(10) unsigned NOT NULL COMMENT 'æˆåŠŸç”³è¯·åˆ°ä»£ç†çš„æ—¶é—´',
  `stat` smallint(3) DEFAULT '-1' COMMENT 'ç”³è¯·ä»£ç†çš„çŠ¶æ€ 1--ç”³è¯·ä¸­; 0--æˆåŠŸ; <= -100 --ç”³è¯·å¤±è´¥é”™è¯¯ç ',
  `is_ditui` smallint(3) DEFAULT '0' COMMENT 'æ˜¯å¦æ˜¯åœ°æŽ¨æ ‡è¯†ï¼Œ1--æ˜¯ 0--ä¸æ˜¯',
  `name` varchar(128) DEFAULT NULL COMMENT 'å§“å',
  `tele_phone` char(18) DEFAULT NULL COMMENT 'æ‰‹æœºå·',
  `wx_name` varchar(64) DEFAULT NULL COMMENT 'å¾®ä¿¡åç§°',
  `refuse_reason` varchar(600) DEFAULT NULL COMMENT 'ä»£ç†ç”³è¯·,è¢«æ‹’ç»çš„åŽŸå› ',
  PRIMARY KEY (`game_id`,`union_id`),
  KEY `game_id` (`game_id`,`game_uid`),
  KEY `upper_id` (`upper_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-09-07 18:35:04
