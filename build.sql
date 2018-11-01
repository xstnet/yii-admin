/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50718
Source Host           : 127.0.0.1:3306
Source Database       : yii-admin

Target Server Type    : MYSQL
Target Server Version : 50718
File Encoding         : 65001

Date: 2018-11-01 22:35:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for x_admin_login_history
-- ----------------------------
DROP TABLE IF EXISTS `x_admin_login_history`;
CREATE TABLE `x_admin_login_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `login_at` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '登录IP',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='用户登录历史';

-- ----------------------------
-- Records of x_admin_login_history
-- ----------------------------
INSERT INTO `x_admin_login_history` VALUES ('1', '1', 'xstnet', '1540284277', '127.0.0.1', '1540284277', '1540284277');
INSERT INTO `x_admin_login_history` VALUES ('2', '1', 'xstnet', '1540298921', '127.0.0.1', '1540298921', '1540298921');
INSERT INTO `x_admin_login_history` VALUES ('3', '1', 'xstnet12', '1540308822', '127.0.0.1', '1540308822', '1540308822');
INSERT INTO `x_admin_login_history` VALUES ('4', '1', 'xstnet12', '1540351712', '127.0.0.1', '1540351712', '1540351712');
INSERT INTO `x_admin_login_history` VALUES ('5', '1', '醉丶春风', '1540358191', '127.0.0.1', '1540358191', '1540358191');
INSERT INTO `x_admin_login_history` VALUES ('6', '20', 'guest', '1540370707', '127.0.0.1', '1540370707', '1540370707');
INSERT INTO `x_admin_login_history` VALUES ('7', '1', '醉丶春风', '1540372346', '127.0.0.1', '1540372346', '1540372346');
INSERT INTO `x_admin_login_history` VALUES ('8', '1', '醉丶春风', '1540434271', '127.0.0.1', '1540434271', '1540434271');
INSERT INTO `x_admin_login_history` VALUES ('9', '1', '醉丶春风', '1540460721', '127.0.0.1', '1540460721', '1540460721');
INSERT INTO `x_admin_login_history` VALUES ('10', '1', '醉丶春风', '1540537334', '127.0.0.1', '1540537334', '1540537334');
INSERT INTO `x_admin_login_history` VALUES ('11', '1', '醉丶春风', '1540879384', '127.0.0.1', '1540879384', '1540879384');
INSERT INTO `x_admin_login_history` VALUES ('12', '1', '醉丶春风', '1540889948', '127.0.0.1', '1540889948', '1540889948');
INSERT INTO `x_admin_login_history` VALUES ('13', '1', '醉丶春风', '1540953128', '127.0.0.1', '1540953128', '1540953128');
INSERT INTO `x_admin_login_history` VALUES ('14', '1', '醉丶春风', '1540956322', '127.0.0.1', '1540956322', '1540956322');
INSERT INTO `x_admin_login_history` VALUES ('15', '1', '醉丶春风', '1540966153', '127.0.0.1', '1540966153', '1540966153');
INSERT INTO `x_admin_login_history` VALUES ('16', '1', '醉丶春风', '1540968248', '127.0.0.1', '1540968248', '1540968248');
INSERT INTO `x_admin_login_history` VALUES ('17', '1', '醉丶春风', '1540977861', '127.0.0.1', '1540977861', '1540977861');
INSERT INTO `x_admin_login_history` VALUES ('18', '1', '醉丶春风', '1540992787', '127.0.0.1', '1540992787', '1540992787');
INSERT INTO `x_admin_login_history` VALUES ('19', '1', '醉丶春风', '1541043684', '127.0.0.1', '1541043684', '1541043684');
INSERT INTO `x_admin_login_history` VALUES ('20', '1', '醉丶春风', '1541046264', '127.0.0.1', '1541046264', '1541046264');
INSERT INTO `x_admin_login_history` VALUES ('21', '1', '醉丶春风', '1541051402', '127.0.0.1', '1541051402', '1541051402');
INSERT INTO `x_admin_login_history` VALUES ('22', '1', '醉丶春风', '1541067888', '49.90.140.79', '1541067888', '1541067888');
INSERT INTO `x_admin_login_history` VALUES ('23', '1', '醉丶春风', '1541081241', '101.224.40.239', '1541081241', '1541081241');
INSERT INTO `x_admin_login_history` VALUES ('24', '20', 'guest', '1541082882', '101.224.40.239', '1541082882', '1541082882');

-- ----------------------------
-- Table structure for x_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `x_admin_user`;
CREATE TABLE `x_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '头像，图片地址',
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '密码，存储hash格式',
  `password_reset_token` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '密码重置token',
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'email',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员，具有所有权限，1是，0 不是',
  `rigister_ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '注册IP',
  `login_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '本次登录IP',
  `last_login_ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '上次登录IP',
  `login_at` int(10) NOT NULL DEFAULT '0' COMMENT '本次登录时间',
  `last_login_at` int(10) NOT NULL DEFAULT '0' COMMENT '上一次登录时间',
  `login_count` mediumint(5) NOT NULL DEFAULT '0' COMMENT '登录统计',
  `status` tinyint(2) NOT NULL DEFAULT '10' COMMENT '10正常，1:禁用',
  `token` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '授权登录token',
  `openid` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='后台用户表';

-- ----------------------------
-- Records of x_admin_user
-- ----------------------------
INSERT INTO `x_admin_user` VALUES ('1', 'xstnet', '醉丶春风', '13333333333', '', '$2y$13$8aPgOIUPtqZQBawLAcua..D48y5lLZjdQgRGQZd271h97ql2PqiWW', '', '123456', '0', '', '101.224.40.239', '49.90.140.79', '1541081241', '1541067888', '70', '10', '', '', '0', '1541081241');
INSERT INTO `x_admin_user` VALUES ('20', 'guest', 'guest', '', '', '$2y$13$20o9L0UrmIG2N4nirz2Gueg.svhv6hlB9fp5z2zseuJcR328AdoWK', '', 'guest@admin.com', '0', '127.0.0.1', '101.224.40.239', '127.0.0.1', '1541082882', '1540370707', '11', '10', '', '', '1530959998', '1541082882');
INSERT INTO `x_admin_user` VALUES ('23', '测试1', 'aaa', '', '', '$2y$13$1pVxMshPgI2ORhlt9GiRgO9J/dG0BoNc9CrQl/H2R3ppGQlvwc2cC', '', '3333', '0', '127.0.0.1', '', '', '0', '0', '0', '1', '', '', '1540213902', '1540213975');

-- ----------------------------
-- Table structure for x_article
-- ----------------------------
DROP TABLE IF EXISTS `x_article`;
CREATE TABLE `x_article` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user_id` int(5) NOT NULL DEFAULT '0' COMMENT '发布人用户id',
  `category_id` int(5) NOT NULL DEFAULT '0' COMMENT '分类id,关联分类表article_category  id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `title_style` varchar(200) NOT NULL DEFAULT '' COMMENT '标题样式，简单样式',
  `title_image` varchar(200) NOT NULL DEFAULT '' COMMENT '标题图片',
  `author` varchar(20) NOT NULL DEFAULT '' COMMENT '作者',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  `hits` int(10) NOT NULL DEFAULT '0' COMMENT '点击数',
  `comment_count` int(5) NOT NULL DEFAULT '0' COMMENT '评论数',
  `is_allow_comment` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许评论，1：是，0：否',
  `top` int(5) NOT NULL DEFAULT '0' COMMENT '赞，顶数量',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否展示，1：是，0否',
  `bad` int(5) NOT NULL DEFAULT '0' COMMENT '踩一下，不好，数量',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:逻辑删除，0，正常',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:是热门，0：不是',
  `sort_value` int(5) NOT NULL DEFAULT '100' COMMENT '排序，越小越靠前',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字 以英文, 分割',
  `source` varchar(30) NOT NULL DEFAULT '' COMMENT '来源',
  `from_platform` enum('其他','安卓','IOS','微信端','手机端','PC端') NOT NULL DEFAULT 'PC端' COMMENT '发布平台来源',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='文章主表';

-- ----------------------------
-- Records of x_article
-- ----------------------------
INSERT INTO `x_article` VALUES ('3', '0', '6', '第一篇文章', 'color:#ff7800;font-weight:bold;font-size:14px;text-decoration:underline;', 'uploads/images/2018-11/4a53834802485f59d8fe6adf6959f2c1.gif', '醉丶春风', '文章内容1', '0', '0', '1', '0', '1', '0', '0', '0', '100', '44', '本站', 'PC端', '1541059376', '1541081319');
INSERT INTO `x_article` VALUES ('4', '1', '3', 'ffffffffffffffffffff', 'font-weight:bold;font-size:16px;text-decoration:underline;', 'uploads/images/2018-11/b9d996fd3efb02282efffbb5fbf29569.jpg', '醉丶春风', '22', '0', '0', '1', '0', '1', '0', '0', '0', '100', '', '本站', 'PC端', '1541063536', '1541081310');

-- ----------------------------
-- Table structure for x_article_category
-- ----------------------------
DROP TABLE IF EXISTS `x_article_category`;
CREATE TABLE `x_article_category` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：正常，1：禁用',
  `parent_id` int(5) NOT NULL DEFAULT '0',
  `parents` varchar(200) NOT NULL DEFAULT '' COMMENT '所有的父级ID，包含自身',
  `sort_value` mediumint(5) NOT NULL DEFAULT '100' COMMENT '排序值，升序',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='文章分类表';

-- ----------------------------
-- Records of x_article_category
-- ----------------------------
INSERT INTO `x_article_category` VALUES ('3', '第一个分类', '0', '0', '3', '100', '1540883284', '1540883284');
INSERT INTO `x_article_category` VALUES ('4', '第二个分类', '0', '0', '4', '100', '1540883415', '1540883415');
INSERT INTO `x_article_category` VALUES ('5', '第三个分类', '0', '0', '5', '100', '1540883422', '1540883422');
INSERT INTO `x_article_category` VALUES ('6', '第四个分类', '0', '0', '6', '100', '1540883432', '1540886624');
INSERT INTO `x_article_category` VALUES ('7', '二级分类1', '0', '4', '7,4', '100', '1540883442', '1540883768');
INSERT INTO `x_article_category` VALUES ('8', '二级分类2', '0', '3', '8,3', '100', '1540883455', '1540883455');
INSERT INTO `x_article_category` VALUES ('9', '三级分类1', '0', '7', '9,7,3', '100', '1540883469', '1540883469');

-- ----------------------------
-- Table structure for x_article_contents
-- ----------------------------
DROP TABLE IF EXISTS `x_article_contents`;
CREATE TABLE `x_article_contents` (
  `id` int(10) NOT NULL DEFAULT '0' COMMENT 'id，关联article表id',
  `content` mediumtext COMMENT '文章内容',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章内容表';

-- ----------------------------
-- Records of x_article_contents
-- ----------------------------
INSERT INTO `x_article_contents` VALUES ('3', '<p><em><strong>文章内容22</strong></em></p>', '1541059376', '1541063498');
INSERT INTO `x_article_contents` VALUES ('4', '<p>asdfasfsadf</p><pre class=\"brush:php;toolbar:false\">public&nbsp;function&nbsp;actionSaveArticle()\n{\n&nbsp;&nbsp;&nbsp;$params&nbsp;=&nbsp;self::postParams();\n&nbsp;&nbsp;&nbsp;ArticleService::instance()-&gt;saveArtice($params);\n&nbsp;&nbsp;&nbsp;return&nbsp;self::ajaxSuccess(&#39;更新成功&#39;);\n}</pre><p><br/></p>', '1541063536', '1541063536');

-- ----------------------------
-- Table structure for x_config
-- ----------------------------
DROP TABLE IF EXISTS `x_config`;
CREATE TABLE `x_config` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `category_id` int(5) NOT NULL DEFAULT '0' COMMENT '配置分类ID,关联config_category表id',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '调用代码，程序中使用',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称，显示用',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '配置值，根据type的不同，存储不同的字符串',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：启用，0：禁用',
  `type` enum('checkbox','imagefile','radio','textarea','text') NOT NULL DEFAULT 'text' COMMENT '属性类型',
  `attribute` varchar(255) NOT NULL DEFAULT '' COMMENT '属性， json字符串，type为radio 和checkbox时用到',
  `sort_value` int(5) NOT NULL DEFAULT '100' COMMENT '排序值，升序，小的在前',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='系统设置，全局配置表';

-- ----------------------------
-- Records of x_config
-- ----------------------------
INSERT INTO `x_config` VALUES ('1', '1', 'name', '网站名称', ' yii-admin', '', '1', 'text', '', '100', '1540477138', '1540477138');
INSERT INTO `x_config` VALUES ('2', '1', 'host', '网站域名', 'http://yii-admin.com', ' 域名前请加上http或者https', '1', 'text', '', '100', '1540477138', '1540477138');
INSERT INTO `x_config` VALUES ('3', '1', 'logo', '网站logo', 'uploads/images/2018-10/b6f5e2461e684de09ffb6f81c84c3d0a.jpg', '', '1', 'imagefile', '', '100', '1540477138', '1540548858');
INSERT INTO `x_config` VALUES ('4', '3', 'defaultRole', '默认角色', '2', '填写的为角色ID', '1', 'text', '', '100', '1540477138', '1540547256');

-- ----------------------------
-- Table structure for x_config_category
-- ----------------------------
DROP TABLE IF EXISTS `x_config_category`;
CREATE TABLE `x_config_category` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `sort_value` int(5) NOT NULL DEFAULT '100' COMMENT '排序值，升序，小的在前',
  `code` varchar(20) NOT NULL DEFAULT '' COMMENT '调用代码',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1：启用，0：禁用',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='系统设置分类表';

-- ----------------------------
-- Records of x_config_category
-- ----------------------------
INSERT INTO `x_config_category` VALUES ('1', '网站设置', '100', 'site', '1', '1540477138', '1540477138');
INSERT INTO `x_config_category` VALUES ('2', '邮件设置', '100', 'mail', '1', '1540477138', '1540477138');
INSERT INTO `x_config_category` VALUES ('3', '后台用户设置', '101', 'adminUser', '1', '1540477138', '1540477138');
INSERT INTO `x_config_category` VALUES ('4', '其他', '1000', 'other', '1', '1540477138', '1540477138');

-- ----------------------------
-- Table structure for x_menus
-- ----------------------------
DROP TABLE IF EXISTS `x_menus`;
CREATE TABLE `x_menus` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `parent_id` int(5) NOT NULL DEFAULT '0' COMMENT '父栏目ID，1级栏目为0',
  `sort_value` mediumint(5) NOT NULL DEFAULT '100' COMMENT '排序值，升序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：正常，1：禁用',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '路由，真正的url',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标字体class',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='系统栏目表';

-- ----------------------------
-- Records of x_menus
-- ----------------------------
INSERT INTO `x_menus` VALUES ('1', '会员管理', '0', '10', '0', 'Member', 'layui-icon-user', '1529915832', '1540284681');
INSERT INTO `x_menus` VALUES ('2', '会员列表', '1', '100', '0', 'user/index', 'layui-icon-username', '1529915832', '1540284879');
INSERT INTO `x_menus` VALUES ('3', '系统日志', '0', '100', '0', 'log/2323', 'layui-icon-log', '1529915832', '1540284456');
INSERT INTO `x_menus` VALUES ('4', '操作日志', '3', '100', '0', 'system-log/index', 'layui-icon-log', '1529915832', '1540284435');
INSERT INTO `x_menus` VALUES ('5', '系统设置', '0', '100', '0', 'setting', 'layui-icon-set-fill', '1529915832', '1540284496');
INSERT INTO `x_menus` VALUES ('6', '菜单管理', '5', '100', '0', 'setting/menus', 'layui-icon-more', '1529915832', '1540284581');
INSERT INTO `x_menus` VALUES ('7', '权限管理', '0', '100', '0', 'permission', 'layui-icon-password', '1529915832', '1540284607');
INSERT INTO `x_menus` VALUES ('8', '权限管理', '7', '100', '0', 'permission/permissions', 'layui-icon-password', '1529915832', '1540284796');
INSERT INTO `x_menus` VALUES ('9', '角色管理', '7', '100', '0', 'permission/roles', 'layui-icon-group', '1529915832', '1540284812');
INSERT INTO `x_menus` VALUES ('10', '用户管理', '7', '10023', '0', 'member/index', 'layui-icon-friends', '1529915832', '1540284837');
INSERT INTO `x_menus` VALUES ('12', '商品管理', '0', '100', '0', '', 'layui-icon-cart-simple', '1530167897', '1540284753');
INSERT INTO `x_menus` VALUES ('13', '商品管理', '12', '100', '0', 'goods/index', '', '1530167897', '1530167897');
INSERT INTO `x_menus` VALUES ('14', '商品管理1', '13', '100', '0', 'goods/index', '', '1530167897', '1530167897');
INSERT INTO `x_menus` VALUES ('15', '商品管理2', '14', '100', '0', 'goods/index', '', '1530167897', '1530167897');
INSERT INTO `x_menus` VALUES ('16', '商品管理2', '12', '100', '0', '', '', '1540267257', '1540267288');
INSERT INTO `x_menus` VALUES ('17', '商品管理3', '12', '100', '0', 'goods/index1', '', '1540267301', '1540267301');
INSERT INTO `x_menus` VALUES ('18', '测试禁用菜单', '0', '200', '1', '', '', '1540286152', '1540308271');
INSERT INTO `x_menus` VALUES ('19', '系统设置', '5', '100', '0', 'setting/setting', 'layui-icon-setting', '1540434404', '1540434404');
INSERT INTO `x_menus` VALUES ('20', '文章管理', '0', '31', '0', '', 'layui-icon-list', '1540879723', '1541081446');
INSERT INTO `x_menus` VALUES ('21', '分类管理', '20', '30', '0', 'article/category', '', '1540879758', '1540879758');
INSERT INTO `x_menus` VALUES ('22', '文章管理', '20', '30', '0', 'article/index', '', '1540879776', '1540879776');

-- ----------------------------
-- Table structure for x_pm_permissions
-- ----------------------------
DROP TABLE IF EXISTS `x_pm_permissions`;
CREATE TABLE `x_pm_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `description` varchar(150) NOT NULL DEFAULT '' COMMENT '描述',
  `menu_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所属栏目ID',
  `url` varchar(60) NOT NULL DEFAULT '' COMMENT '实际访问url',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0正常，1禁用',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`url`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='权限：权限表';

-- ----------------------------
-- Records of x_pm_permissions
-- ----------------------------
INSERT INTO `x_pm_permissions` VALUES ('32', '查看', '访问菜单管理页面', '6', 'setting/menus', '0', '1540367847', '1540368952');
INSERT INTO `x_pm_permissions` VALUES ('33', '删除', '删除菜单', '6', 'setting/delete-menu', '0', '1540367923', '1540367923');
INSERT INTO `x_pm_permissions` VALUES ('34', '添加', '添加菜单', '6', 'setting/add-menu', '0', '1540367937', '1540367937');
INSERT INTO `x_pm_permissions` VALUES ('35', '修改', '更新菜单信息', '6', 'setting/save-menu', '0', '1540367962', '1540367962');
INSERT INTO `x_pm_permissions` VALUES ('36', '查看', '访问角色管理页面', '9', 'permission/roles', '0', '1540368058', '1540368058');
INSERT INTO `x_pm_permissions` VALUES ('37', '设置权限--查看', '打开设置权限对话框', '9', 'permission/get-role-permissions', '0', '1540368092', '1540368092');
INSERT INTO `x_pm_permissions` VALUES ('38', '设置权限--保存', '保存角色权限', '9', 'permission/save-role-permissions', '0', '1540368119', '1540368119');
INSERT INTO `x_pm_permissions` VALUES ('39', '添加', '添加角色', '9', 'permission/add-roles', '0', '1540368139', '1540368139');
INSERT INTO `x_pm_permissions` VALUES ('40', '删除', '删除角色', '9', 'permission/delete-roles', '0', '1540368153', '1540368153');
INSERT INTO `x_pm_permissions` VALUES ('41', '修改', '更新角色信息', '9', 'permission/save-roles', '0', '1540368170', '1540368170');
INSERT INTO `x_pm_permissions` VALUES ('42', '修改角色状态', '修改角色状态', '9', 'permission/change-role-status', '0', '1540368184', '1540368184');
INSERT INTO `x_pm_permissions` VALUES ('43', '查看', '访问权限管理页面', '8', 'permission/permissions', '0', '1540368210', '1540368210');
INSERT INTO `x_pm_permissions` VALUES ('44', '添加', '添加权限', '8', 'permission/add-permission', '0', '1540368223', '1540368223');
INSERT INTO `x_pm_permissions` VALUES ('45', '修改', '更新权限信息', '8', 'permission/save-permission', '0', '1540368237', '1540368237');
INSERT INTO `x_pm_permissions` VALUES ('46', '删除', '删除权限', '8', 'permission/delete-permission', '0', '1540368249', '1540368249');
INSERT INTO `x_pm_permissions` VALUES ('47', '修改权限状态', '修改权限状态', '8', 'permission/change-status', '0', '1540368259', '1540368259');
INSERT INTO `x_pm_permissions` VALUES ('48', '查看', '访问用户管理页面', '10', 'member/index', '0', '1540368275', '1540368275');
INSERT INTO `x_pm_permissions` VALUES ('49', '添加', '添加用户', '10', 'member/add-member', '0', '1540368286', '1540368286');
INSERT INTO `x_pm_permissions` VALUES ('50', '修改', '更新用户信息', '10', 'member/save-member', '0', '1540368297', '1540368297');
INSERT INTO `x_pm_permissions` VALUES ('51', '删除', '删除用户', '10', 'member/delete-member', '0', '1540368310', '1540368310');
INSERT INTO `x_pm_permissions` VALUES ('52', '修改用户状态', '修改用户状态', '10', 'member/change-status', '0', '1540368334', '1540368334');
INSERT INTO `x_pm_permissions` VALUES ('53', '查看', '访问操作日志页面', '4', 'system-log/index', '0', '1540368357', '1540370687');
INSERT INTO `x_pm_permissions` VALUES ('54', '访问个人信息页面', '访问个人信息页面', '0', 'user/profile', '1', '1540368387', '1540372540');
INSERT INTO `x_pm_permissions` VALUES ('55', '更新个人信息', '更新个人信息', '0', 'user/save-user-profile', '1', '1540368405', '1540368405');
INSERT INTO `x_pm_permissions` VALUES ('56', '查看', '访问文章管理页面', '22', 'article/index', '0', '1540994898', '1540994898');
INSERT INTO `x_pm_permissions` VALUES ('57', '查看', '访问系统设置页面', '19', 'setting/setting', '0', '1541080248', '1541080406');
INSERT INTO `x_pm_permissions` VALUES ('58', '修改', '更新系统设置', '19', 'setting/save-setting', '0', '1541080278', '1541080345');
INSERT INTO `x_pm_permissions` VALUES ('59', '添加', '添加系统设置', '19', 'setting/add-setting', '0', '1541080310', '1541080310');
INSERT INTO `x_pm_permissions` VALUES ('60', '查看', '访问文章分类页面', '21', 'article/category', '0', '1541080396', '1541080396');
INSERT INTO `x_pm_permissions` VALUES ('61', '添加', '添加文章分类', '21', 'article/add-category', '0', '1541080469', '1541080469');
INSERT INTO `x_pm_permissions` VALUES ('62', '删除', '删除文章分类', '21', 'article/delete-category', '0', '1541080501', '1541080501');
INSERT INTO `x_pm_permissions` VALUES ('63', '修改', '更新文章分类信息', '21', 'article/save-category', '0', '1541080501', '1541080501');
INSERT INTO `x_pm_permissions` VALUES ('64', '发布文章-查看', '访问发布文章页面', '22', 'article/add', '0', '1541080726', '1541080726');
INSERT INTO `x_pm_permissions` VALUES ('65', '发布文章-发布', '发布文章', '22', 'article/add-article', '0', '1541080760', '1541080760');
INSERT INTO `x_pm_permissions` VALUES ('66', '编辑文章-查看', '访问编辑文章页面', '22', 'article/edit', '0', '1541080789', '1541080789');
INSERT INTO `x_pm_permissions` VALUES ('67', '编辑文章-保存', '更新文章信息', '22', 'article/save-article', '0', '1541080811', '1541080811');
INSERT INTO `x_pm_permissions` VALUES ('68', '删除', '删除文章', '22', 'article/delete-article', '0', '1541080847', '1541080847');
INSERT INTO `x_pm_permissions` VALUES ('69', '更新-是否展示', '更新是否展示', '22', 'article/change-is-show', '0', '1541080878', '1541080878');
INSERT INTO `x_pm_permissions` VALUES ('70', '更新-是否允许评论', '更新是否允许评论', '22', 'article/change-is-allow-comment', '0', '1541080900', '1541080900');
INSERT INTO `x_pm_permissions` VALUES ('71', '快速编辑', '更新文章简要信息', '22', 'article/save-article-brief', '0', '1541080977', '1541080977');

-- ----------------------------
-- Table structure for x_pm_roles
-- ----------------------------
DROP TABLE IF EXISTS `x_pm_roles`;
CREATE TABLE `x_pm_roles` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `sort_value` mediumint(5) NOT NULL DEFAULT '100' COMMENT '排序值，升序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:正常，1:禁用',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='权限：角色表';

-- ----------------------------
-- Records of x_pm_roles
-- ----------------------------
INSERT INTO `x_pm_roles` VALUES ('1', '管理员', '113', '55', '1', '1555555555', '1540135061');
INSERT INTO `x_pm_roles` VALUES ('7', '编辑', '', '88', '1', '1530780390', '1540135503');
INSERT INTO `x_pm_roles` VALUES ('11', '内部人员', '', '100', '0', '1530782388', '1540135045');
INSERT INTO `x_pm_roles` VALUES ('12', '文章发布', '', '100', '1', '1530782392', '1541082689');
INSERT INTO `x_pm_roles` VALUES ('13', '游客', '', '100', '0', '1530782550', '1530971057');
INSERT INTO `x_pm_roles` VALUES ('14', '测试人员', '', '100', '1', '1530782642', '1541082688');
INSERT INTO `x_pm_roles` VALUES ('17', '超级管理员', '具有所有权限', '2', '0', '1540368468', '1540368468');

-- ----------------------------
-- Table structure for x_pm_roles_permissions
-- ----------------------------
DROP TABLE IF EXISTS `x_pm_roles_permissions`;
CREATE TABLE `x_pm_roles_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_id` mediumint(5) NOT NULL DEFAULT '0' COMMENT '角色id,对应roles表id',
  `permission_id` int(10) NOT NULL DEFAULT '0' COMMENT '权限id,分两种情况，对应permissions表id和columns表id',
  `permission_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:操作权限，2:栏目权限',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COMMENT='权限：角色对应权限表';

-- ----------------------------
-- Records of x_pm_roles_permissions
-- ----------------------------
INSERT INTO `x_pm_roles_permissions` VALUES ('55', '1', '5', '2', '1530936268', '1530936268');
INSERT INTO `x_pm_roles_permissions` VALUES ('57', '1', '3', '2', '1530936282', '1530936282');
INSERT INTO `x_pm_roles_permissions` VALUES ('58', '13', '8', '2', '1530971074', '1530971074');
INSERT INTO `x_pm_roles_permissions` VALUES ('63', '14', '2', '2', '1540112427', '1540112427');
INSERT INTO `x_pm_roles_permissions` VALUES ('68', '14', '1', '2', '1540283150', '1540283150');
INSERT INTO `x_pm_roles_permissions` VALUES ('70', '17', '1', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('71', '17', '2', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('72', '17', '3', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('73', '17', '4', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('74', '17', '53', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('75', '17', '5', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('76', '17', '6', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('77', '17', '32', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('78', '17', '33', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('79', '17', '34', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('80', '17', '35', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('81', '17', '7', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('82', '17', '8', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('83', '17', '43', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('84', '17', '44', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('85', '17', '45', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('86', '17', '46', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('87', '17', '47', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('88', '17', '9', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('89', '17', '36', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('90', '17', '37', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('91', '17', '38', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('92', '17', '39', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('93', '17', '40', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('94', '17', '41', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('95', '17', '42', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('96', '17', '10', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('97', '17', '48', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('98', '17', '49', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('99', '17', '50', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('100', '17', '51', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('101', '17', '52', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('102', '17', '12', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('103', '17', '13', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('104', '17', '14', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('105', '17', '15', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('106', '17', '16', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('107', '17', '17', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('108', '17', '18', '2', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('109', '17', '54', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('110', '17', '55', '1', '1540368483', '1540368483');
INSERT INTO `x_pm_roles_permissions` VALUES ('111', '13', '3', '2', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('112', '13', '4', '2', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('113', '13', '53', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('114', '13', '5', '2', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('115', '13', '6', '2', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('116', '13', '32', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('117', '13', '43', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('118', '13', '9', '2', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('119', '13', '36', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('120', '13', '37', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('121', '13', '10', '2', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('122', '13', '48', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('123', '13', '18', '2', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('124', '13', '54', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('125', '13', '55', '1', '1540368684', '1540368684');
INSERT INTO `x_pm_roles_permissions` VALUES ('126', '1', '7', '2', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('127', '1', '8', '2', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('128', '1', '43', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('129', '1', '44', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('130', '1', '45', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('131', '1', '46', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('132', '1', '47', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('133', '1', '9', '2', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('134', '1', '36', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('135', '1', '37', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('136', '1', '38', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('137', '1', '39', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('138', '1', '40', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('139', '1', '41', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('140', '1', '42', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('141', '1', '10', '2', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('142', '1', '48', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('143', '1', '49', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('144', '1', '50', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('145', '1', '51', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('146', '1', '52', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('147', '1', '54', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('148', '1', '55', '1', '1540368707', '1540368707');
INSERT INTO `x_pm_roles_permissions` VALUES ('149', '13', '1', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('150', '13', '2', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('151', '13', '7', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('152', '13', '12', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('153', '13', '13', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('154', '13', '14', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('155', '13', '15', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('156', '13', '16', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('157', '13', '17', '2', '1540372514', '1540372514');
INSERT INTO `x_pm_roles_permissions` VALUES ('158', '17', '19', '2', '1540434439', '1540434439');
INSERT INTO `x_pm_roles_permissions` VALUES ('159', '17', '20', '2', '1540879787', '1540879787');
INSERT INTO `x_pm_roles_permissions` VALUES ('160', '17', '21', '2', '1540879787', '1540879787');
INSERT INTO `x_pm_roles_permissions` VALUES ('161', '17', '22', '2', '1540879787', '1540879787');
INSERT INTO `x_pm_roles_permissions` VALUES ('162', '17', '56', '1', '1540994988', '1540994988');
INSERT INTO `x_pm_roles_permissions` VALUES ('163', '17', '60', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('164', '17', '61', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('165', '17', '62', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('166', '17', '63', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('167', '17', '64', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('168', '17', '65', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('169', '17', '66', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('170', '17', '67', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('171', '17', '68', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('172', '17', '69', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('173', '17', '70', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('174', '17', '71', '1', '1541081097', '1541081097');
INSERT INTO `x_pm_roles_permissions` VALUES ('175', '17', '57', '1', '1541081460', '1541081460');
INSERT INTO `x_pm_roles_permissions` VALUES ('176', '17', '58', '1', '1541081460', '1541081460');
INSERT INTO `x_pm_roles_permissions` VALUES ('177', '17', '59', '1', '1541081460', '1541081460');
INSERT INTO `x_pm_roles_permissions` VALUES ('178', '13', '19', '2', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('179', '13', '57', '1', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('180', '13', '20', '2', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('181', '13', '21', '2', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('182', '13', '60', '1', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('183', '13', '22', '2', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('184', '13', '56', '1', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('185', '13', '64', '1', '1541082796', '1541082796');
INSERT INTO `x_pm_roles_permissions` VALUES ('186', '13', '66', '1', '1541082796', '1541082796');

-- ----------------------------
-- Table structure for x_pm_users_roles
-- ----------------------------
DROP TABLE IF EXISTS `x_pm_users_roles`;
CREATE TABLE `x_pm_users_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id,对应 admin_user表id',
  `role_id` mediumint(5) NOT NULL DEFAULT '0' COMMENT '角色id,对应roles表id',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='权限：用户对应角色表';

-- ----------------------------
-- Records of x_pm_users_roles
-- ----------------------------
INSERT INTO `x_pm_users_roles` VALUES ('25', '23', '14', '1540213902', '1540213902');
INSERT INTO `x_pm_users_roles` VALUES ('28', '1', '17', '1540368520', '1540368520');
INSERT INTO `x_pm_users_roles` VALUES ('30', '20', '13', '1540368597', '1540368597');

-- ----------------------------
-- Table structure for x_user
-- ----------------------------
DROP TABLE IF EXISTS `x_user`;
CREATE TABLE `x_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  UNIQUE KEY `password_reset_token` (`password_reset_token`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of x_user
-- ----------------------------

-- ----------------------------
-- Table structure for x_todolist
-- ----------------------------
DROP TABLE IF EXISTS `x_todolist`;
CREATE TABLE `x_todolist` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL DEFAULT '0' COMMENT '创建人ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '事项名称',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - 新建，1 - 已完成，2 - 已删除 ',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='待办事项';

-- ----------------------------
-- Records of x_todolist
-- ----------------------------
INSERT INTO `x_todolist` VALUES ('18', '20', 'layui升级到2.4.3', '1', '1540367333', '1540367380');
INSERT INTO `x_todolist` VALUES ('19', '20', '登录功能', '1', '1540367338', '1540367381');
INSERT INTO `x_todolist` VALUES ('20', '20', '权限管理功能', '1', '1540367342', '1540367382');
INSERT INTO `x_todolist` VALUES ('21', '20', '添加系统操作日志', '1', '1540367347', '1540367383');
INSERT INTO `x_todolist` VALUES ('22', '20', '菜单设置功能', '1', '1540367351', '1540367384');
INSERT INTO `x_todolist` VALUES ('23', '20', '系统设置功能', '1', '1540367356', '1541082972');
INSERT INTO `x_todolist` VALUES ('24', '20', '个人信息修改功能', '1', '1540367359', '1540367385');
INSERT INTO `x_todolist` VALUES ('25', '20', '权限节点验证功能', '1', '1540367363', '1540434292');
INSERT INTO `x_todolist` VALUES ('26', '20', '添加待办事项功能', '1', '1540367374', '1540367385');
INSERT INTO `x_todolist` VALUES ('27', '20', '文章管理功能', '1', '1541082993', '1541082994');
INSERT INTO `x_todolist` VALUES ('28', '20', '前台会员管理', '0', '1541083060', '1541083060');

-- ----------------------------
-- Table structure for x_system_log
-- ----------------------------
DROP TABLE IF EXISTS `x_system_log`;
CREATE TABLE `x_system_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '操作用户ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `route` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '链接',
  `params` mediumtext COMMENT '操作参数',
  `request_method` varchar(15) NOT NULL DEFAULT '' COMMENT '请求方式',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间，时间戳',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间，时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=686 DEFAULT CHARSET=utf8 COMMENT='系统日志';