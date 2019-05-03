/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50725
Source Host           : localhost:3306
Source Database       : cocosgame

Target Server Type    : MYSQL
Target Server Version : 50725
File Encoding         : 65001

Date: 2019-05-03 10:03:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cg_user`
-- ----------------------------
DROP TABLE IF EXISTS `cg_user`;
CREATE TABLE `cg_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `access_token` varchar(43) NOT NULL COMMENT '请求token',
  `password_hash` varchar(60) NOT NULL COMMENT '密码hash',
  `avatar` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '头像ID',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户等级',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '登录状态 1正常 2禁止',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '性别 1男 2女 3保密',
  `diamond` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '钻石',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `login_ip` int(11) NOT NULL DEFAULT '0' COMMENT '登录IP',
  `login_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录总数',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `allowance` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'restful剩余允许的请求数',
  `allowance_updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'restful最后更新',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of cg_user
-- ----------------------------
