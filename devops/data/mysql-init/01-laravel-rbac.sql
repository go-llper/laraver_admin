-- noinspection SqlNoDataSourceInspectionForFile
-- noinspection SqlDialectInspectionForFile
-- MySQL dump 10.13  Distrib 5.6.24, for Linux (x86_64)
--
-- Host: 100.115.119.66    Database: sunholding_db_new
-- ------------------------------------------------------
-- Server version	5.6.16-log

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

CREATE DATABASE IF NOT EXISTS laravel_rbac default charset utf8 COLLATE utf8_general_ci;

USE `laravel_rbac`;

DROP TABLE IF EXISTS `admin_password_resets`;

CREATE TABLE `admin_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `admin_password_resets_email_index` (`email`),
  KEY `admin_password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `admin_user_role`;

CREATE TABLE `admin_user_role` (
  `admin_user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`admin_user_id`,`role_id`),
  KEY `admin_user_role_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_super` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否超级管理员',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admin_users` (`id`, `name`, `email`, `password`, `is_super`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'admin','admin@admin.com','$2y$10$GBKiY/ngDVpe1iHwlTem3e0fbNrnv1sRLGcj4wT1isK0gbzY4oQoC',1,'aot2y8pFRyurjUWQs2JiH3QWZJcSTepfsgB1qXPwtXST8inqnjdTwilMSaa4','2016-02-23 02:44:26','2016-02-23 02:44:26');

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `migrations` (`migration`, `batch`)
VALUES
	('2014_10_12_000000_create_users_table',1),
	('2014_10_12_100000_create_password_resets_table',1),
	('2016_01_18_071439_create_admin_users',1),
	('2016_01_18_071720_create_admin_password_resets_table',1),
	('2016_01_23_031442_entrust_base',1),
	('2016_01_23_031518_entrust_pivot_admin_user_role',1);


DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `permission_role_permission_id_role_id_unique` (`permission_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `permission_role` (`permission_id`, `role_id`)
VALUES
	(20,10),
	(20,12),
	(21,10),
	(21,12),
	(22,10),
	(22,12),
	(35,10),
	(35,12),
	(36,10),
	(36,12),
	(37,10),
	(37,12),
	(38,10),
	(39,10),
	(40,10),
	(42,10),
	(43,10),
	(44,10),
	(45,10),
	(46,10),
	(47,10),
	(48,10),
	(49,10),
	(50,10),
	(51,10),
	(52,10),
	(53,10),
	(54,10),
	(55,10),
	(56,10),
	(57,10),
	(58,10);

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单父ID',
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图标class',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_menu` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否作为菜单显示,[1|0]',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `permissions` (`id`, `fid`, `icon`, `name`, `display_name`, `description`, `is_menu`, `sort`, `created_at`, `updated_at`)
VALUES
	(20,0,'edit','#-1456129983','系统设置','',1,100,'2016-02-22 08:33:03','2016-02-22 08:33:03'),
	(21,20,'','admin.admin_user.index','用户权限','查看后台用户列表',1,0,'2016-02-18 07:56:26','2016-02-18 07:56:26'),
	(22,20,'','admin.admin_user.create','创建后台用户','页面',0,0,'2016-02-23 03:48:18','2016-02-23 03:48:18'),
	(35,0,'home','admin.home','Dashboard','后台首页',1,0,'2016-02-22 08:32:40','2016-02-22 08:32:40'),
	(36,0,' fa-laptop','#-1456132007','博客管理','',1,0,'2016-02-22 09:06:47','2016-02-22 09:06:47'),
	(37,36,'','admin.blog.index','博客列表','',1,0,'2016-02-22 09:15:48','2016-02-22 09:15:48'),
	(38,20,'','admin.admin_user.store','保存新建后台用户','操作',0,0,'2016-02-23 03:48:52','2016-02-23 03:48:52'),
	(39,20,'','admin.admin_user.destroy','删除后台用户','操作',0,0,'2016-02-23 03:49:09','2016-02-23 03:49:09'),
	(40,20,'','admin.admin_user.destory.all','批量后台用户删除','操作',0,0,'2016-02-23 04:01:01','2016-02-23 04:01:01'),
	(42,20,'','admin.admin_user.edit','编辑后台用户','页面',0,0,'2016-02-23 03:48:35','2016-02-23 03:48:35'),
	(43,20,'','admin.admin_user.update','保存编辑后台用户','操作',0,0,'2016-02-23 03:50:12','2016-02-23 03:50:12'),
	(44,20,'','admin.permission.index','权限管理','页面',0,0,'2016-02-23 03:51:36','2016-02-23 03:51:36'),
	(45,20,'','admin.permission.create','新建权限','页面',0,0,'2016-02-23 03:52:16','2016-02-23 03:52:16'),
	(46,20,'','admin.permission.store','保存新建权限','操作',0,0,'2016-02-23 03:52:38','2016-02-23 03:52:38'),
	(47,20,'','admin.permission.edit','编辑权限','页面',0,0,'2016-02-23 03:53:29','2016-02-23 03:53:29'),
	(48,20,'','admin.permission.update','保存编辑权限','操作',0,0,'2016-02-23 03:53:56','2016-02-23 03:53:56'),
	(49,20,'','admin.permission.destroy','删除权限','操作',0,0,'2016-02-23 03:54:27','2016-02-23 03:54:27'),
	(50,20,'','admin.permission.destory.all','批量删除权限','操作',0,0,'2016-02-23 03:55:17','2016-02-23 03:55:17'),
	(51,20,'','admin.role.index','角色管理','页面',0,0,'2016-02-23 03:56:07','2016-02-23 03:56:07'),
	(52,20,'','admin.role.create','新建角色','页面',0,0,'2016-02-23 03:56:33','2016-02-23 03:56:33'),
	(53,20,'','admin.role.store','保存新建角色','操作',0,0,'2016-02-23 03:57:26','2016-02-23 03:57:26'),
	(54,20,'','admin.role.edit','编辑角色','页面',0,0,'2016-02-23 03:58:25','2016-02-23 03:58:25'),
	(55,20,'','admin.role.update','保存编辑角色','操作',0,0,'2016-02-23 03:58:50','2016-02-23 03:58:50'),
	(56,20,'','admin.role.permissions','角色权限设置','',0,0,'2016-02-23 03:59:26','2016-02-23 03:59:26'),
	(57,20,'','admin.role.destroy','角色删除','操作',0,0,'2016-02-23 03:59:49','2016-02-23 03:59:49'),
	(58,20,'','admin.role.destory.all','批量删除角色','',0,0,'2016-02-23 04:01:58','2016-02-23 04:01:58');

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `credit_total` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `user_bank_card`;

CREATE TABLE `user_bank_card` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `card_no` varchar(30) NOT NULL DEFAULT '0' COMMENT '银行卡号',
  `card_bank` tinyint(4) NOT NULL DEFAULT '0' COMMENT '所属银行',
  `card_deposit` varchar(100) DEFAULT '0' COMMENT '开户行',
  `is_delete` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_credit_contract`;

CREATE TABLE `user_credit_contract` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `family_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '亲属名字',
  `family_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '亲属联系方式',
  `workmate_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '同事名字',
  `workmate_tel` varchar(20) NOT NULL DEFAULT '0' COMMENT '同事联系方式',
  `friend_name` varchar(20) NOT NULL DEFAULT '' COMMENT '朋友名字',
  `friend_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '朋友联系方式',
  `marital_name` varchar(20) NOT NULL DEFAULT '' COMMENT '配偶名字',
  `marital_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '配偶联系方式',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_credit_phone`;

CREATE TABLE `user_credit_phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户通讯录授信表',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '资料类型',
  `content` text NOT NULL COMMENT '资料内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_credit_photo`;

CREATE TABLE `user_credit_photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '资料类型',
  `image` varchar(100) DEFAULT NULL COMMENT 'OSS地址',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user_info`;

CREATE TABLE `user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `real_name` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户名称',
  `id_card` varchar(18) NOT NULL DEFAULT '0' COMMENT '身份证号',
  `cul_level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '文化程度',
  `marital_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '婚姻状况 1-未婚，2-已婚，3-离异',
  `home_address` varchar(100) NOT NULL DEFAULT '0' COMMENT '家庭住址',
  `service_years` tinyint(4) NOT NULL DEFAULT '1' COMMENT '从业年限',
  `company_name` varchar(100) NOT NULL DEFAULT '0' COMMENT '公司名称',
  `company_start` int(11) NOT NULL DEFAULT '0' COMMENT '公司注册时间',
  `company_address` varchar(100) NOT NULL DEFAULT '0' COMMENT '公司地址',
  `biz_address` varchar(100) NOT NULL DEFAULT '0' COMMENT '公司经营地址',
  `main_biz` tinyint(4) NOT NULL DEFAULT '0' COMMENT '主营业务',
  `employee_number` int(11) NOT NULL DEFAULT '1' COMMENT '雇员人数',
  `money_total` int(11) NOT NULL DEFAULT '0' COMMENT '年收入',
  `profit_total` int(11) NOT NULL DEFAULT '0' COMMENT '年净利润',
  `anchored_car` int(11) NOT NULL DEFAULT '0' COMMENT '挂靠车辆',
  `own_car` int(11) NOT NULL DEFAULT '0' COMMENT '个人车辆',
  `confidence` varchar(100) NOT NULL DEFAULT '' COMMENT 'FaceId识别分数',
  `credit_status` tinyint(2) DEFAULT '0' COMMENT '授信状态',
  `operate_user` varchar(100) DEFAULT NULL COMMENT '操作用户',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_loan`;


CREATE TABLE `user_loan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `order_no` varchar(50) NOT NULL DEFAULT '0' COMMENT '借款流水号',
  `borrow_amount` int(11) NOT NULL DEFAULT '0' COMMENT '借款金额',
  `borrow_real_amount` int(11) NOT NULL DEFAULT '0' COMMENT '实际借款金额',
  `borrow_need_fee` int(11) NOT NULL DEFAULT '0' COMMENT '收取手续费',
  `borrow_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '借款状态',
  `borrow_time` int(11) NOT NULL DEFAULT '0' COMMENT '借款时间',
  `borrow_card_no` varchar(20) NOT NULL DEFAULT '0' COMMENT '借款人银行卡号',
  `borrow_card_bank` tinyint(4) NOT NULL DEFAULT '0' COMMENT '借款人银行',
  `received_time` int(11) NOT NULL DEFAULT '0' COMMENT '收到借款时间',
  `borrow_days` int(11) NOT NULL DEFAULT '0' COMMENT '借款实际天数',
  `current_amount` int(11) NOT NULL DEFAULT '0' COMMENT '当前应还本金（考虑手续费）',
  `borrow_interest` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '产生的利息',
  `current_interest` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '当前应还利息',
  `borrow_late` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '产生的违约金',
  `current_late` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '当前应还违约金',
  `log_info` varchar(1000) NOT NULL DEFAULT '0' COMMENT '物流信息',
  `driver_info` varchar(1000) NOT NULL DEFAULT '0' COMMENT '司机信息',
  `truck_info` varchar(1000) NOT NULL DEFAULT '0' COMMENT '车辆信息',
  `audit_ret` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核结果',
  `audit_time` int(11) NOT NULL DEFAULT '0' COMMENT '审核时间',
  `audit_admin` int(11) NOT NULL DEFAULT '0' COMMENT '审核Admin',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '放款时间',
  `pay_info` varchar(1000) NOT NULL DEFAULT '0' COMMENT '放款信息',
  `repayment_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '最终还清确认时间',
  `repayment_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '借款人已还款',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `user_repayment`;

CREATE TABLE `user_repayment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `borrow_id` int(11) NOT NULL DEFAULT '0',
  `repayment_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `repayment_time` int(11) NOT NULL DEFAULT '0',
  `repayment_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '还款状态',
  `borrower_bank_no` int(11) NOT NULL DEFAULT '0' COMMENT '还款银行卡号',
  `borrower_receipt` varchar(100) NOT NULL DEFAULT '0' COMMENT '还款流水号',
  `audit_ret` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核',
  `audit_commnet` varchar(1000) NOT NULL DEFAULT '-',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user_money_log`;

CREATE TABLE `user_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL COMMENT '借款OR还款',
  `affect_money` decimal(15,2) NOT NULL COMMENT '影响金额',
  `info` varchar(100) NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid_and_type` (`uid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/**用户资金流水记录***/

DROP TABLE IF EXISTS `user_money_record`;

CREATE TABLE `user_money_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '资金流水表',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `borrow_id` int(11) NOT NULL COMMENT '借款id',
  `cash_before` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变更前本金',
  `cash_change` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '变更本金',
  `cash` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变更后本金',
  `late_before` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变更前违约金',
  `late_change` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '变更违约金',
  `late` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变更后违约金',
  `interest_before` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变更前利息',
  `interest_change` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '变更利息',
  `interest` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '变更后利息',
  `type` smallint(6) NOT NULL COMMENT '数据类型-本金、利息、预期利息、违约金',
  `dated_at` int(11) NOT NULL COMMENT '执行日期',
  `note` char(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_money_record_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-11 16:50:53
