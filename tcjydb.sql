/*
SQLyog Ultimate v12.3.1 (64 bit)
MySQL - 5.7.26 : Database - tcjydb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tcjydb` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `tcjydb`;

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '账号',
  `password` varbinary(50) DEFAULT NULL COMMENT '密码',
  `nick_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `post_id` int(11) NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `form_info` */

DROP TABLE IF EXISTS `form_info`;

CREATE TABLE `form_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '表单名字',
  `content` text COLLATE utf8_unicode_ci COMMENT '内容',
  `tb_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '关联表名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `form_tb_0` */

DROP TABLE IF EXISTS `form_tb_0`;

CREATE TABLE `form_tb_0` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `fie_0` text COLLATE utf8_unicode_ci COMMENT '姓名',
  `fie_1` text COLLATE utf8_unicode_ci COMMENT '学历',
  `fie_2` text COLLATE utf8_unicode_ci COMMENT '是否是全日制',
  `fie_3` text COLLATE utf8_unicode_ci COMMENT '爱好',
  `fie_4` text COLLATE utf8_unicode_ci COMMENT '学习经历',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `form_tb_1` */

DROP TABLE IF EXISTS `form_tb_1`;

CREATE TABLE `form_tb_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `fie_0` text COLLATE utf8_unicode_ci COMMENT '胡涛',
  `fie_1` text COLLATE utf8_unicode_ci COMMENT '下拉框',
  `fie_2` text COLLATE utf8_unicode_ci COMMENT '单选',
  `fie_3` text COLLATE utf8_unicode_ci COMMENT '多选',
  `fie_4` text COLLATE utf8_unicode_ci COMMENT '多行文本',
  `fie_5` text COLLATE utf8_unicode_ci COMMENT '文本',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cardid` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '身份证号码',
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '密码',
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '姓名',
  `phone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '电话号码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `userinfo` */

DROP TABLE IF EXISTS `userinfo`;

CREATE TABLE `userinfo` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `email` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '电子邮件',
  `location` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '所在地区',
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系地址',
  `school` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '毕业院校',
  `major` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '专业名称',
  `education` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '学历',
  `Scan1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '学历证书扫描件',
  `Scan2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '工作证明扫描件（盖单位鲜章）',
  `contactname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '紧急联系人姓名',
  `contactphone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '紧急联系人电话',
  `pwdproblem` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '密码找回问题',
  `pwdanswer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '密码找回答案',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;