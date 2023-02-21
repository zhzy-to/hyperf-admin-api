/*
 Navicat Premium Data Transfer

 Source Server         : 本地宝塔
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : go_admin_template

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 21/02/2023 15:52:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_menus
-- ----------------------------
DROP TABLE IF EXISTS `admin_menus`;
CREATE TABLE `admin_menus`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `order` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu` tinyint(1) NULL DEFAULT 1 COMMENT '是否为菜单 1是 0否',
  `uri` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单路由',
  `condition` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单接口权限别名',
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GET' COMMENT '请求类型 菜单则默认为GET',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '菜单权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_menus
-- ----------------------------
INSERT INTO `admin_menus` VALUES (1, 0, 0, '面板', 'help', 1, '', '', 'GET', 1, '2022-11-15 16:20:58', '2023-02-21 09:30:12');
INSERT INTO `admin_menus` VALUES (2, 1, 0, '控制台', 'home-filled', 1, '/', '', 'GET', 1, '2022-12-26 13:21:26', '2022-12-26 13:21:29');
INSERT INTO `admin_menus` VALUES (3, 0, 0, '管理', 'management', 1, '', '', 'GET', 1, '2022-12-26 15:09:26', '2022-12-26 15:09:28');
INSERT INTO `admin_menus` VALUES (4, 3, 0, '管理员', 'coordinate', 1, '/manager/list', '', 'GET', 1, '2022-12-28 09:50:00', '2022-12-28 09:50:03');
INSERT INTO `admin_menus` VALUES (5, 2, 0, '订单数据统计', '', 0, '', 'system:statistic:order', 'GET', 1, '2022-12-28 10:29:59', '2022-12-28 10:30:01');
INSERT INTO `admin_menus` VALUES (6, 2, 0, '图表数据统计', '', 0, '', 'system:statistic:chart', 'GET', 1, '2022-12-28 10:34:23', '2022-12-28 10:34:26');
INSERT INTO `admin_menus` VALUES (7, 2, 0, '商品数据统计', '', 0, '', 'system:statistic:goods', 'GET', 1, '2022-12-28 13:28:34', '2022-12-28 13:28:37');
INSERT INTO `admin_menus` VALUES (8, 0, 0, '其它', 'mostly-cloudy', 1, '', '', 'GET', 1, '2023-02-02 14:48:18', '2023-02-02 14:48:20');
INSERT INTO `admin_menus` VALUES (9, 8, 0, '图库', 'picture-filled', 1, '/image/list', '', 'GET', 1, '2023-02-02 14:48:22', '2023-02-02 14:48:25');
INSERT INTO `admin_menus` VALUES (10, 9, 0, '素材分类列表', '', 0, '', 'material:class:list', 'GET', 1, '2023-02-02 14:48:28', '2023-02-02 14:48:31');
INSERT INTO `admin_menus` VALUES (11, 9, 0, '新增素材分类', '', 0, '', 'material:class:add', 'POST', 1, '2023-02-02 14:48:33', '2023-02-02 14:48:37');
INSERT INTO `admin_menus` VALUES (12, 9, 0, '更新素材分类', '', 0, '', 'material:class:update', 'POST', 1, '2023-02-02 14:48:39', '2023-02-02 14:48:42');
INSERT INTO `admin_menus` VALUES (13, 9, 0, '删除素材分类', '', 0, '', 'material:class:delete', 'POST', 1, '2023-02-02 14:48:44', '2023-02-02 14:48:47');
INSERT INTO `admin_menus` VALUES (14, 8, 0, '公告', 'notification', 1, '/notice/list', '', 'GET', 1, '2023-01-30 11:45:54', '2023-01-30 11:45:56');
INSERT INTO `admin_menus` VALUES (15, 3, 0, '权限菜单', 'connection', 1, '/access/list', '', 'GET', 1, '2023-02-02 14:48:50', '2023-02-02 14:48:52');
INSERT INTO `admin_menus` VALUES (16, 3, 0, '角色管理', 'histogram', 1, '/role/list', '', 'GET', 1, '2023-02-02 14:48:55', '2023-02-06 15:58:45');
INSERT INTO `admin_menus` VALUES (20, 0, 0, '测试', '', 1, '', '', 'GET', 1, '2023-02-07 14:41:45', '2023-02-07 14:41:45');

-- ----------------------------
-- Table structure for admin_role_menus
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menus`;
CREATE TABLE `admin_role_menus`  (
  `role_id` int(11) UNSIGNED NOT NULL,
  `menu_id` int(11) UNSIGNED NOT NULL,
  UNIQUE INDEX `role_menu_id`(`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_menus
-- ----------------------------
INSERT INTO `admin_role_menus` VALUES (1, 1);
INSERT INTO `admin_role_menus` VALUES (1, 2);
INSERT INTO `admin_role_menus` VALUES (1, 3);
INSERT INTO `admin_role_menus` VALUES (1, 4);
INSERT INTO `admin_role_menus` VALUES (1, 5);
INSERT INTO `admin_role_menus` VALUES (1, 6);
INSERT INTO `admin_role_menus` VALUES (1, 7);
INSERT INTO `admin_role_menus` VALUES (1, 8);
INSERT INTO `admin_role_menus` VALUES (1, 9);
INSERT INTO `admin_role_menus` VALUES (1, 10);
INSERT INTO `admin_role_menus` VALUES (1, 11);
INSERT INTO `admin_role_menus` VALUES (1, 12);
INSERT INTO `admin_role_menus` VALUES (1, 13);
INSERT INTO `admin_role_menus` VALUES (1, 14);
INSERT INTO `admin_role_menus` VALUES (1, 15);
INSERT INTO `admin_role_menus` VALUES (1, 16);
INSERT INTO `admin_role_menus` VALUES (2, 1);
INSERT INTO `admin_role_menus` VALUES (2, 2);
INSERT INTO `admin_role_menus` VALUES (2, 5);
INSERT INTO `admin_role_menus` VALUES (2, 6);
INSERT INTO `admin_role_menus` VALUES (2, 7);
INSERT INTO `admin_role_menus` VALUES (2, 20);
INSERT INTO `admin_role_menus` VALUES (4, 3);
INSERT INTO `admin_role_menus` VALUES (4, 16);

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `desc` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NULL DEFAULT 1,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES (1, '系统管理员', '系统管理员', 1, '2022-12-26 13:22:12', '2023-02-21 14:47:00');
INSERT INTO `admin_roles` VALUES (2, '系统测试员', '系统测试员', 1, '2023-02-01 11:32:14', '2023-02-21 14:43:40');
INSERT INTO `admin_roles` VALUES (4, '准这同', '温天用', 1, '2023-02-21 10:05:56', '2023-02-21 10:24:01');

-- ----------------------------
-- Table structure for admin_user_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_roles`;
CREATE TABLE `admin_user_roles`  (
  `user_id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`) USING BTREE,
  UNIQUE INDEX `user_role_id`(`user_id`, `role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user_roles
-- ----------------------------
INSERT INTO `admin_user_roles` VALUES (1, 1);
INSERT INTO `admin_user_roles` VALUES (5, 1);
INSERT INTO `admin_user_roles` VALUES (6, 1);
INSERT INTO `admin_user_roles` VALUES (6, 2);
INSERT INTO `admin_user_roles` VALUES (7, 2);

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'admin 用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES (1, 'admin', '$2y$10$98./gGHFmlQoKKQcSHsHkOWk0DOEGpG0IDLcKUMbrQrPItsEnPUae', 'wang', 'https://cdn.learnku.com/uploads/avatars/47788_1613634819.gif!/both/100x100', 1, '2022-11-15 16:51:55', '2023-02-02 11:47:10');
INSERT INTO `admin_users` VALUES (5, 'zhao', '$2y$10$EOHRZkSjZ51v3o6s9z02WelhnskQMGY8oNrKwwYYWViHlSvVWS.gW', 'zhao', 'http://dummyimage.com/100x100', 1, '2023-01-13 15:24:57', '2023-02-01 09:58:39');
INSERT INTO `admin_users` VALUES (6, 'zhzy1', '$2y$10$CY56COi4hY81RO0AdF3BBerbjBgSsJjRBZogTfcWr5CuPXKF5Xedi', 'zhzy', 'http://ro1ma2js2.hb-bkt.clouddn.com/admin/20230106/91e4a538ly1gbr9f4a6arj20qr0qrjte.jpg', 1, '2023-02-01 13:44:12', '2023-02-01 15:47:33');
INSERT INTO `admin_users` VALUES (7, 'wangxiaoming', '$2y$10$eSIEkAU.O7Bo61pWsE0py.117QUC4bAJGEZnzbcv7clSytCUUczZK', '王小蒙', 'http://cdn.emm.cool/admin/20230131/QQ图片20200427145606.jpg', 1, '2023-02-02 11:35:34', '2023-02-02 11:35:34');

-- ----------------------------
-- Table structure for material
-- ----------------------------
DROP TABLE IF EXISTS `material`;
CREATE TABLE `material`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '素材地址',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material
-- ----------------------------
INSERT INTO `material` VALUES (1, 1, '测试2', 'admin/20230106/91e4a538ly1gbr9f4a6arj20qr0qrjte.jpg', NULL, '2023-01-30 14:07:59');
INSERT INTO `material` VALUES (3, 0, 'admin/20230112/91e4a538ly1g76558t1gej20u00u07cs.jpg', 'admin/20230112/91e4a538ly1g76558t1gej20u00u07cs.jpg', '2023-01-12 15:42:19', '2023-01-12 15:42:19');
INSERT INTO `material` VALUES (4, 1, '牛蛙', 'admin/20230112/91e4a538ly1g76558t1gej20u00u07cs.jpg', '2023-01-12 15:56:01', '2023-01-12 15:56:12');
INSERT INTO `material` VALUES (5, 1, '小秘密', 'admin/20230112/91e4a538ly1ggcsf11jlmj20u00u040d.jpg', '2023-01-12 15:56:24', '2023-01-12 15:57:37');
INSERT INTO `material` VALUES (6, 2, '小鱼儿', 'admin/20230113/QQ图片20230112164451.gif', '2023-01-13 09:28:33', '2023-01-13 09:28:41');
INSERT INTO `material` VALUES (7, 3, '傻猫猫', 'admin/20230131/QQ图片20200427145606.jpg', '2023-01-31 16:39:53', '2023-01-31 16:40:06');
INSERT INTO `material` VALUES (8, 4, '炸毛猫', 'admin/20230131/91e4a538ly1ggcsf11jlmj20u00u040d.jpg', '2023-01-31 16:40:42', '2023-01-31 16:40:51');

-- ----------------------------
-- Table structure for material_classification
-- ----------------------------
DROP TABLE IF EXISTS `material_classification`;
CREATE TABLE `material_classification`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `order` int(11) UNSIGNED NULL DEFAULT 100,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '素材分类' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of material_classification
-- ----------------------------
INSERT INTO `material_classification` VALUES (1, '电子设备', 1);
INSERT INTO `material_classification` VALUES (2, '猫猫猫猫', 1);
INSERT INTO `material_classification` VALUES (3, '游戏主机', 100);
INSERT INTO `material_classification` VALUES (4, '美妆护肤', 100);
INSERT INTO `material_classification` VALUES (7, '家用日常', 100);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `email` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `password` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` datetime(3) NULL DEFAULT NULL,
  `updated_at` datetime(3) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_users_created_at`(`created_at`) USING BTREE,
  INDEX `idx_users_updated_at`(`updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
