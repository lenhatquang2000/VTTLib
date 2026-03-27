/*
 Navicat Premium Data Transfer

 Source Server         : LibDemo
 Source Server Type    : MySQL
 Source Server Version : 101116
 Source Host           : 103.200.23.189:3306
 Source Schema         : pisystem__lib_demo

 Target Server Type    : MySQL
 Target Server Version : 101116
 File Encoding         : 65001

 Date: 17/03/2026 15:11:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_code` int NULL DEFAULT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `model_id` bigint UNSIGNED NULL DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_logs_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 92 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of activity_logs
-- ----------------------------
INSERT INTO `activity_logs` VALUES (1, 1, 'patron_created', NULL, NULL, NULL, NULL, 'App\\Models\\PatronDetail', 1, '{\"name\":\"Po Pi\"}', '127.0.0.1', '2026-01-19 20:51:23', '2026-01-19 20:51:23');
INSERT INTO `activity_logs` VALUES (2, 1, 'patron_created', NULL, NULL, NULL, NULL, 'App\\Models\\PatronDetail', 2, '{\"name\":\"Po Pi\"}', '127.0.0.1', '2026-01-19 21:01:19', '2026-01-19 21:01:19');
INSERT INTO `activity_logs` VALUES (3, 1, 'patron_created', NULL, NULL, NULL, NULL, 'App\\Models\\PatronDetail', 3, '{\"name\":\"Po Pi\"}', '127.0.0.1', '2026-01-19 21:05:18', '2026-01-19 21:05:18');
INSERT INTO `activity_logs` VALUES (4, 1, 'user_created', NULL, NULL, NULL, NULL, 'App\\Models\\User', 16, '{\"name\":\"T\\u00f4 Trung Hi\\u1ebfu\",\"email\":\"trunghieu3832@vttu.edu.vn\",\"created_at\":\"2026-02-03T09:00:25.000000Z\"}', '127.0.0.1', '2026-02-03 09:00:25', '2026-02-03 09:00:25');
INSERT INTO `activity_logs` VALUES (5, 1, 'user_created', NULL, NULL, NULL, NULL, 'App\\Models\\User', 16, '{\"name\":\"T\\u00f4 Trung Hi\\u1ebfu\",\"email\":\"trunghieu3832@vttu.edu.vn\",\"role_id\":4}', '127.0.0.1', '2026-02-03 09:00:25', '2026-02-03 09:00:25');
INSERT INTO `activity_logs` VALUES (6, 1, 'user_updated', NULL, NULL, NULL, NULL, 'App\\Models\\User', 16, '{\"password\":\"changed\"}', '127.0.0.1', '2026-02-03 09:26:22', '2026-02-03 09:26:22');
INSERT INTO `activity_logs` VALUES (7, 1, 'user_updated', NULL, NULL, NULL, NULL, 'App\\Models\\User', 16, '{\"old_data\":{\"name\":\"T\\u00f4 Trung Hi\\u1ebfu\",\"email\":\"trunghieu3832@vttu.edu.vn\",\"password_changed\":true},\"new_data\":{\"name\":\"T\\u00f4 Trung Hi\\u1ebfu\",\"username\":\"tthieu\",\"email\":\"trunghieu3832@vttu.edu.vn\",\"password\":\"Hh123457a!\"}}', '127.0.0.1', '2026-02-03 09:26:22', '2026-02-03 09:26:22');
INSERT INTO `activity_logs` VALUES (8, 1, 'role_assigned', NULL, NULL, NULL, NULL, 'App\\Models\\User', 16, '{\"role_name\":\"Test\",\"role_id\":5}', '127.0.0.1', '2026-02-03 10:10:57', '2026-02-03 10:10:57');
INSERT INTO `activity_logs` VALUES (9, 1, 'role_assigned', NULL, NULL, NULL, NULL, 'App\\Models\\User', 16, '{\"role_name\":\"Test\",\"role_id\":7}', '127.0.0.1', '2026-02-04 01:02:22', '2026-02-04 01:02:22');
INSERT INTO `activity_logs` VALUES (10, 1, 'role_assigned', NULL, NULL, NULL, NULL, 'App\\Models\\User', 4, '{\"role_name\":\"Test\",\"role_id\":7}', '127.0.0.1', '2026-02-04 03:03:54', '2026-02-04 03:03:54');
INSERT INTO `activity_logs` VALUES (11, 1, 'role_assigned', NULL, NULL, NULL, NULL, 'App\\Models\\User', 16, '{\"role_name\":\"root\",\"role_id\":3}', '127.0.0.1', '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `activity_logs` VALUES (12, 16, 'role_removed', NULL, NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"user_data\":{\"name\":\"System Root\",\"email\":\"root@root.com\"},\"role_data\":{\"name\":\"admin\",\"display_name\":\"Qu\\u1ea3n tr\\u1ecb vi\\u00ean\"}}', '127.0.0.1', '2026-02-04 04:16:28', '2026-02-04 04:16:28');
INSERT INTO `activity_logs` VALUES (13, 16, 'role_assigned', NULL, NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"role_name\":\"admin\",\"role_id\":1}', '127.0.0.1', '2026-02-04 04:16:52', '2026-02-04 04:16:52');
INSERT INTO `activity_logs` VALUES (14, 16, 'role_removed', NULL, NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"user_data\":{\"name\":\"System Root\",\"email\":\"root@root.com\"},\"role_data\":{\"name\":\"admin\",\"display_name\":\"Qu\\u1ea3n tr\\u1ecb vi\\u00ean\"}}', '127.0.0.1', '2026-02-04 04:17:13', '2026-02-04 04:17:13');
INSERT INTO `activity_logs` VALUES (15, 1, 'role_assigned', NULL, NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"role_name\":\"admin\",\"role_id\":1}', '127.0.0.1', '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `activity_logs` VALUES (16, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-02-05 09:12:46', '2026-02-05 09:12:46');
INSERT INTO `activity_logs` VALUES (17, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"tthieu\"}', NULL, NULL, NULL, '127.0.0.1', '2026-02-05 09:13:06', '2026-02-05 09:13:06');
INSERT INTO `activity_logs` VALUES (18, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"tthieu\"}', NULL, NULL, NULL, '127.0.0.1', '2026-02-05 09:13:20', '2026-02-05 09:13:20');
INSERT INTO `activity_logs` VALUES (19, 16, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"tthieu\"}', NULL, NULL, NULL, '127.0.0.1', '2026-02-05 09:13:28', '2026-02-05 09:13:28');
INSERT INTO `activity_logs` VALUES (20, 16, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"tthieu\"}', NULL, NULL, NULL, '127.0.0.1', '2026-02-05 09:29:13', '2026-02-05 09:29:13');
INSERT INTO `activity_logs` VALUES (21, 16, 'admin.marc.book.store', 'POST', 'http://localhost:8000/topsecret/marc-books', 302, '{\"framework\":\"AVMARC21\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"value\":\"ACCSC\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]}},\"items\":[{\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}],\"cover_image\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-02-05 09:37:32', '2026-02-05 09:37:32');
INSERT INTO `activity_logs` VALUES (22, 16, 'root.login.store', 'POST', 'http://localhost:8000/root/login', 302, '{\"username\":\"tthieu\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 01:10:23', '2026-03-02 01:10:23');
INSERT INTO `activity_logs` VALUES (23, 16, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"tthieu\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 01:33:11', '2026-03-02 01:33:11');
INSERT INTO `activity_logs` VALUES (24, 16, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 01:35:56', '2026-03-02 01:35:56');
INSERT INTO `activity_logs` VALUES (25, 16, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"hieutt\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 01:37:04', '2026-03-02 01:37:04');
INSERT INTO `activity_logs` VALUES (26, 16, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"tthieu\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 01:37:10', '2026-03-02 01:37:10');
INSERT INTO `activity_logs` VALUES (27, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 01:39:44', '2026-03-02 01:39:44');
INSERT INTO `activity_logs` VALUES (28, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 03:06:22', '2026-03-02 03:06:22');
INSERT INTO `activity_logs` VALUES (29, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 06:25:05', '2026-03-02 06:25:05');
INSERT INTO `activity_logs` VALUES (30, 1, 'admin.marc.book.store', 'POST', 'http://localhost:8000/topsecret/marc-books', 302, '{\"framework\":\"AVMARC21\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"c\",\"value\":\"abc\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]}},\"items\":[{\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:01:49', '2026-03-02 07:01:49');
INSERT INTO `activity_logs` VALUES (31, 1, 'admin.patrons.groups.destroy', 'DELETE', 'http://localhost:8000/topsecret/patron-groups/1', 302, '{\"_method\":\"DELETE\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:33:57', '2026-03-02 07:33:57');
INSERT INTO `activity_logs` VALUES (32, 1, 'admin.patrons.groups.store', 'POST', 'http://localhost:8000/topsecret/patron-groups', 302, '{\"name\":\"Gi\\u00e1o Vi\\u00ean\",\"code\":\"gv\",\"order\":\"1\",\"description\":\"\\u0111\\u1ecdc gi\\u1ea3 l\\u00e0 Gi\\u1ea3ng Vi\\u00ean\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:34:20', '2026-03-02 07:34:20');
INSERT INTO `activity_logs` VALUES (33, 1, 'admin.patrons.groups.store', 'POST', 'http://localhost:8000/topsecret/patron-groups', 302, '{\"name\":\"H\\u1ecdc Sinh\",\"code\":\"st\",\"order\":\"1\",\"description\":\"th\\u1ec3 lo\\u1ea1i \\u0111\\u1ecdc gi\\u1ea3 l\\u00e0 sinh vi\\u00ean\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:34:50', '2026-03-02 07:34:50');
INSERT INTO `activity_logs` VALUES (34, 1, 'admin.patrons.groups.reorder', 'PATCH', 'http://localhost:8000/topsecret/patron-groups/reorder', 200, '{\"ids\":[\"3\",\"2\"]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:41:31', '2026-03-02 07:41:31');
INSERT INTO `activity_logs` VALUES (35, 1, 'admin.patrons.groups.reorder', 'PATCH', 'http://localhost:8000/topsecret/patron-groups/reorder', 200, '{\"ids\":[\"2\",\"3\"]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:41:37', '2026-03-02 07:41:37');
INSERT INTO `activity_logs` VALUES (36, 1, 'admin.patrons.groups.reorder', 'PATCH', 'http://localhost:8000/topsecret/patron-groups/reorder', 200, '{\"ids\":[\"3\",\"2\"]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:41:41', '2026-03-02 07:41:41');
INSERT INTO `activity_logs` VALUES (37, 1, 'admin.z3950.test', 'POST', 'http://localhost:8000/topsecret/z3950/1/test', 200, '[]', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:47:40', '2026-03-02 07:47:40');
INSERT INTO `activity_logs` VALUES (38, 1, 'admin.z3950.test', 'POST', 'http://localhost:8000/topsecret/z3950/2/test', 200, '[]', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:48:10', '2026-03-02 07:48:10');
INSERT INTO `activity_logs` VALUES (39, 1, 'admin.settings.barcode.store', 'POST', 'http://localhost:8000/topsecret/settings/barcode', 302, '{\"name\":\"V\\u00f5 Tr\\u01b0\\u1eddng To\\u1ea3n\",\"prefix\":\"VTTU\",\"length\":\"12\",\"start_number\":\"1\",\"target_type\":\"item\",\"is_active\":\"1\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 07:58:27', '2026-03-02 07:58:27');
INSERT INTO `activity_logs` VALUES (40, 1, 'admin.marc.framework.store', 'POST', 'http://localhost:8000/topsecret/marc-definitions/framework', 302, '{\"code\":\"F2\",\"name\":\"F2\",\"description\":\"F2\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 09:04:11', '2026-03-02 09:04:11');
INSERT INTO `activity_logs` VALUES (41, 1, 'admin.marc.tag.destroy', 'DELETE', 'http://localhost:8000/topsecret/marc-definitions/tag/15', 302, '{\"_method\":\"DELETE\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-02 09:21:18', '2026-03-02 09:21:18');
INSERT INTO `activity_logs` VALUES (42, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-03 00:23:47', '2026-03-03 00:23:47');
INSERT INTO `activity_logs` VALUES (43, 1, 'admin.document-types.order', 'POST', 'http://localhost:8000/topsecret/document-types/order', 200, '{\"orders\":[{\"id\":\"1\",\"order\":0},{\"id\":\"3\",\"order\":1},{\"id\":\"2\",\"order\":2},{\"id\":\"4\",\"order\":3},{\"id\":\"5\",\"order\":4},{\"id\":\"6\",\"order\":5},{\"id\":\"7\",\"order\":6},{\"id\":\"8\",\"order\":7},{\"id\":\"9\",\"order\":8},{\"id\":\"10\",\"order\":9}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-03 00:30:39', '2026-03-03 00:30:39');
INSERT INTO `activity_logs` VALUES (44, 1, 'admin.settings.barcode.store', 'POST', 'http://localhost:8000/topsecret/settings/barcode', 302, '{\"name\":\"B\\u1ea1n \\u0110\\u1ecdc\",\"prefix\":\"VTTU\",\"length\":\"6\",\"start_number\":\"1\",\"target_type\":\"patron\",\"is_active\":\"1\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-03 00:48:45', '2026-03-03 00:48:45');
INSERT INTO `activity_logs` VALUES (45, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 01:20:52', '2026-03-06 01:20:52');
INSERT INTO `activity_logs` VALUES (46, 1, 'admin.marc.book.store', 'POST', 'http://localhost:8000/topsecret/marc-books', 302, '{\"framework\":\"STANDARD\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"value\":\"A\"}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]}},\"items\":[{\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 01:46:30', '2026-03-06 01:46:30');
INSERT INTO `activity_logs` VALUES (47, 1, 'admin.marc.book.store', 'POST', 'http://localhost:8000/topsecret/marc-books', 302, '{\"framework\":\"STANDARD\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]}},\"items\":[{\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 01:59:44', '2026-03-06 01:59:44');
INSERT INTO `activity_logs` VALUES (48, 1, 'admin.marc.book.store', 'POST', 'http://localhost:8000/topsecret/marc-books', 302, '{\"framework\":\"STANDARD\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]}},\"items\":[{\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 02:12:15', '2026-03-06 02:12:15');
INSERT INTO `activity_logs` VALUES (49, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 07:51:52', '2026-03-06 07:51:52');
INSERT INTO `activity_logs` VALUES (50, 1, 'admin.marc.book.store', 'POST', 'http://localhost:8000/topsecret/marc-books', 302, '{\"framework\":\"STANDARD\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"value\":\"abc\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]}},\"items\":[{\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 08:07:00', '2026-03-06 08:07:00');
INSERT INTO `activity_logs` VALUES (51, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/8', 302, '{\"_method\":\"PUT\",\"framework\":\"STANDARD\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":\"47\",\"value\":\"abc\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":null,\"value\":\"ACCSC\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}},\"items\":[{\"id\":null,\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 08:07:25', '2026-03-06 08:07:25');
INSERT INTO `activity_logs` VALUES (52, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/8', 302, '{\"_method\":\"PUT\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":null,\"value\":\"abc\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":\"48\",\"value\":\"ACCSC\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}},\"items\":[{\"id\":null,\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-06 08:28:28', '2026-03-06 08:28:28');
INSERT INTO `activity_logs` VALUES (53, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-07 02:04:02', '2026-03-07 02:04:02');
INSERT INTO `activity_logs` VALUES (54, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-09 00:47:38', '2026-03-09 00:47:38');
INSERT INTO `activity_logs` VALUES (55, 1, 'admin.marc.book.store', 'POST', 'http://localhost:8000/topsecret/marc-books', 302, '{\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"value\":\"abc\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"value\":null}]}},\"items\":[{\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-09 00:48:55', '2026-03-09 00:48:55');
INSERT INTO `activity_logs` VALUES (56, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/9', 302, '{\"_method\":\"PUT\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"50\",\"value\":\"abc\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}},\"items\":[{\"id\":null,\"document_type_id\":null,\"storage_location_id\":null,\"quantity\":\"1\"}]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-09 00:49:03', '2026-03-09 00:49:03');
INSERT INTO `activity_logs` VALUES (57, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-09 07:22:54', '2026-03-09 07:22:54');
INSERT INTO `activity_logs` VALUES (58, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 01:00:31', '2026-03-10 01:00:31');
INSERT INTO `activity_logs` VALUES (59, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 06:46:40', '2026-03-10 06:46:40');
INSERT INTO `activity_logs` VALUES (60, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 500, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 08:43:57', '2026-03-10 08:43:57');
INSERT INTO `activity_logs` VALUES (61, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 500, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 08:57:28', '2026-03-10 08:57:28');
INSERT INTO `activity_logs` VALUES (62, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 500, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 08:58:50', '2026-03-10 08:58:50');
INSERT INTO `activity_logs` VALUES (63, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 500, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:01:37', '2026-03-10 09:01:37');
INSERT INTO `activity_logs` VALUES (64, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 200, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:03:24', '2026-03-10 09:03:24');
INSERT INTO `activity_logs` VALUES (65, 1, 'admin.marc.import.process', 'POST', 'http://localhost:8000/topsecret/marc-import/process', 302, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"validated_data\":[]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:08:08', '2026-03-10 09:08:08');
INSERT INTO `activity_logs` VALUES (66, 1, 'admin.marc.import.process', 'POST', 'http://localhost:8000/topsecret/marc-import/process', 302, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"validated_data\":[]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:11:21', '2026-03-10 09:11:21');
INSERT INTO `activity_logs` VALUES (67, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 200, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:11:45', '2026-03-10 09:11:45');
INSERT INTO `activity_logs` VALUES (68, 1, 'admin.marc.import.process', 'POST', 'http://localhost:8000/topsecret/marc-import/process', 302, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"validated_data\":[]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:11:57', '2026-03-10 09:11:57');
INSERT INTO `activity_logs` VALUES (69, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 200, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:17:21', '2026-03-10 09:17:21');
INSERT INTO `activity_logs` VALUES (70, 1, 'admin.marc.import.process', 'POST', 'http://localhost:8000/topsecret/marc-import/process', 302, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"validated_data\":[]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:17:25', '2026-03-10 09:17:25');
INSERT INTO `activity_logs` VALUES (71, 1, 'admin.marc.import.process', 'POST', 'http://localhost:8000/topsecret/marc-import/process', 302, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"validated_data\":[]}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:18:54', '2026-03-10 09:18:54');
INSERT INTO `activity_logs` VALUES (72, 1, 'admin.marc.import.upload', 'POST', 'http://localhost:8000/topsecret/marc-import/upload', 200, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"excel_file\":{}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:19:16', '2026-03-10 09:19:16');
INSERT INTO `activity_logs` VALUES (73, 1, 'admin.marc.import.process', 'POST', 'http://localhost:8000/topsecret/marc-import/process', 200, '{\"framework_id\":\"1\",\"action_type\":\"create\",\"validated_data\":{\"2\":{\"title\":\"L\\u1eadp tr\\u00ecnh Laravel 10 c\\u0103n b\\u1ea3n\",\"author\":\"Nguy\\u1ec5n V\\u0103n A\",\"isbn\":\"978-604-1-12345-6\",\"publisher\":\"H\\u00e0 N\\u1ed9i | NXB Gi\\u00e1o D\\u1ee5c | 2023\",\"publication_year\":2023,\"issn\":null,\"subject\":\"L\\u1eadp tr\\u00ecnh Web\",\"classification\":\"005.13\",\"location\":\"K\\u1ec7 s\\u00e1ch A1\",\"notes\":\"T\\u00e0i li\\u1ec7u h\\u01b0\\u1edbng d\\u1eabn th\\u1ef1c h\\u00e0nh\",\"language\":\"vietnamese\",\"description\":\"S\\u00e1ch h\\u01b0\\u1edbng d\\u1eabn h\\u1ecdc Laravel t\\u1eeb c\\u01a1 b\\u1ea3n \\u0111\\u1ebfn n\\u00e2ng cao\",\"record_type\":\"book\"},\"3\":{\"title\":\"C\\u1ea5u tr\\u00fac d\\u1eef li\\u1ec7u v\\u00e0 Gi\\u1ea3i thu\\u1eadt\",\"author\":\"Tr\\u1ea7n Th\\u1ecb B\",\"isbn\":\"978-604-2-98765-4\",\"publisher\":\"TP.HCM | NXB Tr\\u1ebb | 2022\",\"publication_year\":2022,\"issn\":null,\"subject\":\"C\\u00f4ng ngh\\u1ec7 th\\u00f4ng tin\",\"classification\":\"005.73\",\"location\":\"K\\u1ec7 s\\u00e1ch B2\",\"notes\":\"T\\u00e0i li\\u1ec7u tham kh\\u1ea3o\",\"language\":\"vietnamese\",\"description\":\"Ph\\u00e2n t\\u00edch c\\u00e1c thu\\u1eadt to\\u00e1n c\\u01a1 b\\u1ea3n\",\"record_type\":\"book\"},\"4\":{\"title\":\"T\\u1ea1p ch\\u00ed Tin h\\u1ecdc & \\u0110\\u1eddi s\\u1ed1ng\",\"author\":\"Nhi\\u1ec1u t\\u00e1c gi\\u1ea3\",\"isbn\":null,\"publisher\":\"H\\u00e0 N\\u1ed9i | NXB Khoa H\\u1ecdc | 2024\",\"publication_year\":2024,\"issn\":\"1234-5678\",\"subject\":\"Tin h\\u1ecdc\",\"classification\":\"004\",\"location\":null,\"notes\":\"S\\u1ed1 \\u0111\\u1eb7c bi\\u1ec7t th\\u00e1ng 3\",\"language\":null,\"description\":\"T\\u1ea1p ch\\u00ed \\u0111\\u1ecbnh k\\u1ef3\",\"record_type\":\"serial\"},\"5\":{\"title\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\",\"author\":\"L\\u00ea V\\u0103n C\",\"isbn\":9781234567897,\"publisher\":\"\\u0110\\u00e0 N\\u1eb5ng | NXB C\\u00f4ng Ngh\\u1ec7 | 2021\",\"publication_year\":2021,\"issn\":null,\"subject\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\",\"classification\":\"005.74\",\"location\":\"K\\u1ec7 s\\u00e1ch A2\",\"notes\":null,\"language\":\"vietnamese\",\"description\":\"K\\u1ef9 thu\\u1eadt t\\u1ed1i \\u01b0u truy v\\u1ea5n SQL\",\"record_type\":\"book\"}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `activity_logs` VALUES (74, 1, 'admin.marc.book.destroy', 'DELETE', 'http://localhost:8000/topsecret/marc-books/10', 200, '[]', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:26:33', '2026-03-10 09:26:33');
INSERT INTO `activity_logs` VALUES (75, 1, 'admin.settings.locations.store', 'POST', 'http://localhost:8000/topsecret/settings/locations', 302, '{\"branch_id\":\"1\",\"name\":\"L\\u1ea7u 3\",\"code\":\"l3\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-10 09:41:21', '2026-03-10 09:41:21');
INSERT INTO `activity_logs` VALUES (76, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:08:11', '2026-03-11 01:08:11');
INSERT INTO `activity_logs` VALUES (77, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"0\",\"status\":\"approved\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"96\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"95\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"89\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"88\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"93\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"97\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:11:03', '2026-03-11 01:11:03');
INSERT INTO `activity_logs` VALUES (78, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"0\",\"status\":\"approved\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"96\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"95\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"89\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"88\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"93\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"97\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:11:24', '2026-03-11 01:11:24');
INSERT INTO `activity_logs` VALUES (79, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"1\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":null,\"value\":\"10000\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"96\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"95\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"89\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"88\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"100\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"97\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:12:17', '2026-03-11 01:12:17');
INSERT INTO `activity_logs` VALUES (80, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"0\",\"status\":\"approved\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"10000\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"96\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"95\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"89\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"88\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"100\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"97\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:13:17', '2026-03-11 01:13:17');
INSERT INTO `activity_logs` VALUES (81, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"1\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"10000\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"96\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"95\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"89\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"88\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"100\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"97\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:15:35', '2026-03-11 01:15:35');
INSERT INTO `activity_logs` VALUES (82, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"1\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"10000\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:16:56', '2026-03-11 01:16:56');
INSERT INTO `activity_logs` VALUES (83, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"1\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"10000\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:19:06', '2026-03-11 01:19:06');
INSERT INTO `activity_logs` VALUES (84, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"1\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"999\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:19:43', '2026-03-11 01:19:43');
INSERT INTO `activity_logs` VALUES (85, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"1\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"999999\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:20:12', '2026-03-11 01:20:12');
INSERT INTO `activity_logs` VALUES (86, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"0\",\"status\":\"approved\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"999999\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:20:55', '2026-03-11 01:20:55');
INSERT INTO `activity_logs` VALUES (87, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"0\",\"status\":\"pending\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"999999\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:22:17', '2026-03-11 01:22:17');
INSERT INTO `activity_logs` VALUES (88, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"0\",\"status\":\"approved\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"999999\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:22:22', '2026-03-11 01:22:22');
INSERT INTO `activity_logs` VALUES (89, 1, 'admin.marc.book.update', 'PUT', 'http://localhost:8000/topsecret/marc-books/13', 302, '{\"_method\":\"PUT\",\"tab\":\"0\",\"status\":\"approved\",\"subject_category\":\"Article\",\"record_type\":\"book\",\"serial_frequency\":\"unknown\",\"date_type\":\"bc\",\"acquisition_method\":\"untraced\",\"document_format\":\"none\",\"cataloging_standard\":\"AACR2\",\"fields\":{\"020\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"94\",\"value\":\"9781234567897\"},{\"code\":\"c\",\"id\":\"101\",\"value\":\"999999\"}]},\"041\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"082\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"102\",\"value\":\"005.74\"}]},\"150\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"650\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"103\",\"value\":\"C\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"100\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"104\",\"value\":\"L\\u00ea V\\u0103n C\"}]},\"245\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"105\",\"value\":\"T\\u1ed1i \\u01b0u h\\u00f3a c\\u01a1 s\\u1edf d\\u1eef li\\u1ec7u\"}]},\"250\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"260\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"106\",\"value\":\"2021\"}]},\"300\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"852\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":\"a\",\"id\":\"107\",\"value\":\"K\\u1ec7 s\\u00e1ch A2\"}]},\"856\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"900\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"911\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"920\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"925\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"926\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"930\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"933\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"940\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]},\"941\":{\"ind1\":null,\"ind2\":null,\"subfields\":[{\"code\":null,\"id\":null,\"value\":null}]}}}', NULL, NULL, NULL, '127.0.0.1', '2026-03-11 01:24:47', '2026-03-11 01:24:47');
INSERT INTO `activity_logs` VALUES (90, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-14 07:31:47', '2026-03-14 07:31:47');
INSERT INTO `activity_logs` VALUES (91, 1, 'agent.login.store', 'POST', 'http://localhost:8000/topsecret/store', 302, '{\"username\":\"root\"}', NULL, NULL, NULL, '127.0.0.1', '2026-03-17 08:10:50', '2026-03-17 08:10:50');

-- ----------------------------
-- Table structure for barcode_configs
-- ----------------------------
DROP TABLE IF EXISTS `barcode_configs`;
CREATE TABLE `barcode_configs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `length` int NOT NULL DEFAULT 6,
  `start_number` bigint NOT NULL DEFAULT 1,
  `current_number` bigint NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `target_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'item',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of barcode_configs
-- ----------------------------
INSERT INTO `barcode_configs` VALUES (2, 'Võ Trường Toản', 'VTTU', 12, 1, 0, 1, 'item', '2026-03-02 07:58:27', '2026-03-02 07:58:27');
INSERT INTO `barcode_configs` VALUES (3, 'Bạn Đọc', 'VTTU', 6, 1, 0, 1, 'patron', '2026-03-03 00:48:45', '2026-03-03 00:48:45');

-- ----------------------------
-- Table structure for bibliographic_records
-- ----------------------------
DROP TABLE IF EXISTS `bibliographic_records`;
CREATE TABLE `bibliographic_records`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `leader` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cover_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `framework` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AVMARC21',
  `subject_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `serial_frequency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `acquisition_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `document_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cataloging_standard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `record_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'book',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bibliographic_records
-- ----------------------------
INSERT INTO `bibliographic_records` VALUES (1, '00000nam a2200000 a 4500', NULL, 'AVMARC21', 'Article', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'approved', '2026-01-13 21:21:16', '2026-01-15 20:51:25');
INSERT INTO `bibliographic_records` VALUES (2, '00000cam a2200000 i 4500', NULL, 'AVMARC21', NULL, NULL, NULL, NULL, NULL, NULL, 'book', 'pending', '2026-01-14 19:24:44', '2026-01-14 20:16:59');
INSERT INTO `bibliographic_records` VALUES (3, '00000nam a2200000 i 4500', 'covers/BVk35gbPL6XmPyy6wgYcbdqQh9aXlyCodlnH7oH5.png', 'AVMARC21', 'Article', 'unknown', 'bc', NULL, 'none', 'AACR2', 'book', 'pending', '2026-02-05 09:37:32', '2026-02-05 09:37:32');
INSERT INTO `bibliographic_records` VALUES (4, '00000nam a2200000 i 4500', NULL, 'AVMARC21', 'Article', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'pending', '2026-03-02 07:01:49', '2026-03-02 07:01:49');
INSERT INTO `bibliographic_records` VALUES (5, '00000nam a2200000 i 4500', NULL, 'STANDARD', 'Article', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'pending', '2026-03-06 01:46:30', '2026-03-06 01:46:30');
INSERT INTO `bibliographic_records` VALUES (6, '00000nam a2200000 i 4500', NULL, 'STANDARD', 'Article', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'pending', '2026-03-06 01:59:44', '2026-03-06 01:59:44');
INSERT INTO `bibliographic_records` VALUES (7, '00000nam a2200000 i 4500', NULL, 'STANDARD', 'Article', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'pending', '2026-03-06 02:12:15', '2026-03-06 02:12:15');
INSERT INTO `bibliographic_records` VALUES (8, '00000cam a2200000 i 4500', NULL, 'STANDARD', 'Article', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'pending', '2026-03-06 08:07:00', '2026-03-06 08:07:25');
INSERT INTO `bibliographic_records` VALUES (9, '00000cam a2200000 i 4500', NULL, 'STANDARD', 'Article', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'pending', '2026-03-09 00:48:55', '2026-03-09 00:49:03');
INSERT INTO `bibliographic_records` VALUES (11, '01234nam a2200000 a 4500', NULL, 'STANDARD', 'General', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'pending', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `bibliographic_records` VALUES (12, '01234nas a2200000 a 4500', NULL, 'STANDARD', 'General', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'serial', 'pending', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `bibliographic_records` VALUES (13, '01234cam a2200000 a 4500', NULL, 'STANDARD', 'General', 'unknown', 'bc', 'untraced', 'none', 'AACR2', 'book', 'approved', '2026-03-10 09:19:20', '2026-03-11 01:24:47');

-- ----------------------------
-- Table structure for book_items
-- ----------------------------
DROP TABLE IF EXISTS `book_items`;
CREATE TABLE `book_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bibliographic_record_id` bigint UNSIGNED NOT NULL,
  `document_type_id` bigint UNSIGNED NULL DEFAULT NULL,
  `branch_id` bigint UNSIGNED NULL DEFAULT NULL,
  `storage_location_id` bigint UNSIGNED NULL DEFAULT NULL,
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `accession_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT 1,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `temporary_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `order_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `waits_for_print` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `volume_issue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `day` int NULL DEFAULT NULL,
  `month_season` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `year` int NULL DEFAULT NULL,
  `shelf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `shelf_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `book_items_barcode_unique`(`barcode` ASC) USING BTREE,
  UNIQUE INDEX `book_items_accession_number_unique`(`accession_number` ASC) USING BTREE,
  INDEX `book_items_bibliographic_record_id_foreign`(`bibliographic_record_id` ASC) USING BTREE,
  INDEX `book_items_storage_location_id_foreign`(`storage_location_id` ASC) USING BTREE,
  INDEX `book_items_document_type_id_foreign`(`document_type_id` ASC) USING BTREE,
  CONSTRAINT `book_items_bibliographic_record_id_foreign` FOREIGN KEY (`bibliographic_record_id`) REFERENCES `bibliographic_records` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `book_items_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `book_items_storage_location_id_foreign` FOREIGN KEY (`storage_location_id`) REFERENCES `storage_locations` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of book_items
-- ----------------------------
INSERT INTO `book_items` VALUES (3, 1, NULL, NULL, NULL, '16022004', '11111111', 'Daily newspaper', 1, 'A-01', 'T01', 'available', NULL, 0, 'ABC', '1', 16, '02', 2004, 'S-42', 'P-05', '2026-01-14 21:31:47', '2026-01-14 21:31:47');

-- ----------------------------
-- Table structure for books
-- ----------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE `books`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `publisher` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `year_publish` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `isbn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `books_isbn_unique`(`isbn` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of books
-- ----------------------------
INSERT INTO `books` VALUES (1, 'Lập trình Laravel căn bản', 'Nguyễn Văn A', 'NXB Giáo Dục', '2023', '1234567890', '2026-01-13 21:21:16', '2026-01-13 21:21:16');

-- ----------------------------
-- Table structure for branches
-- ----------------------------
DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `branches_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of branches
-- ----------------------------
INSERT INTO `branches` VALUES (1, 'Thư Viện  Đại Học Võ Trường Toản', '1234', 'Phường Mỹ Bình, Thành phố Long Xuyên, Tỉnh An Giang', '0365914056', 1, '2026-01-19 20:49:53', '2026-01-19 20:49:53');

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for circulation_policies
-- ----------------------------
DROP TABLE IF EXISTS `circulation_policies`;
CREATE TABLE `circulation_policies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `patron_group_id` bigint UNSIGNED NOT NULL,
  `max_loan_days` int NOT NULL DEFAULT 14,
  `max_items` int NOT NULL DEFAULT 5,
  `max_renewals` int NOT NULL DEFAULT 2,
  `renewal_days` int NOT NULL DEFAULT 7,
  `fine_per_day` decimal(10, 2) NOT NULL DEFAULT 1000.00,
  `max_fine` decimal(10, 2) NOT NULL DEFAULT 100000.00,
  `grace_period_days` int NOT NULL DEFAULT 0,
  `can_reserve` tinyint(1) NOT NULL DEFAULT 1,
  `max_reservations` int NOT NULL DEFAULT 3,
  `reservation_hold_days` int NOT NULL DEFAULT 3,
  `max_outstanding_fine` decimal(10, 2) NOT NULL DEFAULT 50000.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `circulation_policies_patron_group_id_foreign`(`patron_group_id` ASC) USING BTREE,
  CONSTRAINT `circulation_policies_patron_group_id_foreign` FOREIGN KEY (`patron_group_id`) REFERENCES `patron_groups` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of circulation_policies
-- ----------------------------

-- ----------------------------
-- Table structure for document_types
-- ----------------------------
DROP TABLE IF EXISTS `document_types`;
CREATE TABLE `document_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `marc_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'MARC21 Type of Record (Leader/06)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `default_loan_days` int NOT NULL DEFAULT 14,
  `is_loanable` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `document_types_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of document_types
-- ----------------------------
INSERT INTO `document_types` VALUES (1, 'Sách', 'BOOK', 'a', 'Sách in thông thường', 'book', 14, 1, 1, 0, '2026-01-24 09:03:27', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (2, 'Tạp chí', 'JOURNAL', 's', 'Tạp chí, tập san định kỳ', 'newspaper', 7, 1, 1, 2, '2026-01-24 09:03:27', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (3, 'Báo', 'NEWSPAPER', 's', 'Báo hàng ngày', 'file-text', 1, 0, 1, 1, '2026-01-24 09:03:27', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (4, 'Luận văn/Luận án', 'THESIS', 'a', 'Luận văn thạc sĩ, luận án tiến sĩ', 'graduation-cap', 7, 1, 1, 3, '2026-01-24 09:03:28', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (5, 'Đề tài nghiên cứu', 'RESEARCH', 'a', 'Đề tài nghiên cứu khoa học', 'flask', 7, 1, 1, 4, '2026-01-24 09:03:28', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (6, 'CD/DVD', 'DISC', 'g', 'Đĩa CD, DVD, Blu-ray', 'disc', 7, 1, 1, 5, '2026-01-24 09:03:28', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (7, 'Bản đồ', 'MAP', 'e', 'Bản đồ, atlas', 'map', 7, 0, 1, 6, '2026-01-24 09:03:28', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (8, 'Tài liệu điện tử', 'EBOOK', 'a', 'Sách điện tử, tài liệu số', 'tablet', 30, 1, 1, 7, '2026-01-24 09:03:28', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (9, 'Tài liệu tham khảo', 'REFERENCE', 'a', 'Từ điển, bách khoa toàn thư (không cho mượn)', 'bookmark', 0, 0, 1, 8, '2026-01-24 09:03:28', '2026-03-03 00:30:39');
INSERT INTO `document_types` VALUES (10, 'Khác', 'OTHER', NULL, 'Các loại tài liệu khác', 'file', 14, 1, 1, 9, '2026-01-24 09:03:28', '2026-03-03 00:30:39');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for fines
-- ----------------------------
DROP TABLE IF EXISTS `fines`;
CREATE TABLE `fines`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patron_detail_id` bigint UNSIGNED NOT NULL,
  `loan_transaction_id` bigint UNSIGNED NULL DEFAULT NULL,
  `fine_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  `paid_amount` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `waived_amount` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_date` datetime NULL DEFAULT NULL,
  `collected_by` bigint UNSIGNED NULL DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fines_loan_transaction_id_foreign`(`loan_transaction_id` ASC) USING BTREE,
  INDEX `fines_collected_by_foreign`(`collected_by` ASC) USING BTREE,
  INDEX `fines_patron_detail_id_status_index`(`patron_detail_id` ASC, `status` ASC) USING BTREE,
  CONSTRAINT `fines_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fines_loan_transaction_id_foreign` FOREIGN KEY (`loan_transaction_id`) REFERENCES `loan_transactions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fines_patron_detail_id_foreign` FOREIGN KEY (`patron_detail_id`) REFERENCES `patron_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fines
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for loan_transactions
-- ----------------------------
DROP TABLE IF EXISTS `loan_transactions`;
CREATE TABLE `loan_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patron_detail_id` bigint UNSIGNED NOT NULL,
  `book_item_id` bigint UNSIGNED NOT NULL,
  `circulation_policy_id` bigint UNSIGNED NULL DEFAULT NULL,
  `loan_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `return_date` datetime NULL DEFAULT NULL,
  `renewal_count` int NOT NULL DEFAULT 0,
  `last_renewal_date` datetime NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrowed',
  `loaned_by` bigint UNSIGNED NULL DEFAULT NULL,
  `returned_to` bigint UNSIGNED NULL DEFAULT NULL,
  `loan_branch_id` bigint UNSIGNED NULL DEFAULT NULL,
  `return_branch_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `loan_transactions_circulation_policy_id_foreign`(`circulation_policy_id` ASC) USING BTREE,
  INDEX `loan_transactions_loaned_by_foreign`(`loaned_by` ASC) USING BTREE,
  INDEX `loan_transactions_returned_to_foreign`(`returned_to` ASC) USING BTREE,
  INDEX `loan_transactions_loan_branch_id_foreign`(`loan_branch_id` ASC) USING BTREE,
  INDEX `loan_transactions_return_branch_id_foreign`(`return_branch_id` ASC) USING BTREE,
  INDEX `loan_transactions_patron_detail_id_status_index`(`patron_detail_id` ASC, `status` ASC) USING BTREE,
  INDEX `loan_transactions_book_item_id_status_index`(`book_item_id` ASC, `status` ASC) USING BTREE,
  INDEX `loan_transactions_due_date_index`(`due_date` ASC) USING BTREE,
  CONSTRAINT `loan_transactions_book_item_id_foreign` FOREIGN KEY (`book_item_id`) REFERENCES `book_items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `loan_transactions_circulation_policy_id_foreign` FOREIGN KEY (`circulation_policy_id`) REFERENCES `circulation_policies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `loan_transactions_loan_branch_id_foreign` FOREIGN KEY (`loan_branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `loan_transactions_loaned_by_foreign` FOREIGN KEY (`loaned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `loan_transactions_patron_detail_id_foreign` FOREIGN KEY (`patron_detail_id`) REFERENCES `patron_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `loan_transactions_return_branch_id_foreign` FOREIGN KEY (`return_branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `loan_transactions_returned_to_foreign` FOREIGN KEY (`returned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of loan_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for marc_fields
-- ----------------------------
DROP TABLE IF EXISTS `marc_fields`;
CREATE TABLE `marc_fields`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `record_id` bigint UNSIGNED NOT NULL,
  `tag` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicator1` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `indicator2` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sequence` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `marc_fields_record_id_foreign`(`record_id` ASC) USING BTREE,
  CONSTRAINT `marc_fields_record_id_foreign` FOREIGN KEY (`record_id`) REFERENCES `bibliographic_records` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 85 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of marc_fields
-- ----------------------------
INSERT INTO `marc_fields` VALUES (1, 1, '020', '', '', 0, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (2, 1, '041', '', '', 1, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (3, 1, '082', '', '', 2, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (4, 1, '100', '', '', 3, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (5, 1, '150', '', '', 4, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (6, 1, '245', '', '', 5, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (7, 1, '250', '', '', 6, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (8, 1, '260', '', '', 7, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (9, 1, '300', '', '', 8, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (10, 1, '650', '', '', 9, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (11, 1, '852', '', '', 10, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (12, 1, '856', '', '', 11, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (13, 1, '900', '', '', 12, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (14, 1, '911', '', '', 13, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (15, 1, '920', '', '', 14, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (16, 1, '925', '', '', 15, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (17, 1, '926', '', '', 16, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (18, 1, '930', '', '', 17, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (19, 1, '933', '', '', 18, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (20, 1, '940', '', '', 19, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (21, 1, '941', '', '', 20, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_fields` VALUES (28, 2, '041', '', '', 0, '2026-01-14 19:44:09', '2026-01-14 20:16:59');
INSERT INTO `marc_fields` VALUES (29, 2, '082', '', '', 1, '2026-01-14 19:44:09', '2026-01-14 20:16:59');
INSERT INTO `marc_fields` VALUES (30, 2, '150', '', '', 2, '2026-01-14 20:09:05', '2026-01-14 20:16:59');
INSERT INTO `marc_fields` VALUES (31, 3, '245', '', '', 0, '2026-02-05 09:37:32', '2026-02-05 09:37:32');
INSERT INTO `marc_fields` VALUES (32, 4, '020', '', '', 0, '2026-03-02 07:01:49', '2026-03-02 07:01:49');
INSERT INTO `marc_fields` VALUES (33, 5, '150', '', '', 0, '2026-03-06 01:46:30', '2026-03-06 01:46:30');
INSERT INTO `marc_fields` VALUES (36, 8, '020', '', '', 0, '2026-03-06 08:28:28', '2026-03-06 08:28:28');
INSERT INTO `marc_fields` VALUES (37, 9, '020', '', '', 0, '2026-03-09 00:48:55', '2026-03-09 00:49:03');
INSERT INTO `marc_fields` VALUES (49, 11, '245', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (50, 11, '100', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (51, 11, '260', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (52, 11, '260', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (53, 11, '020', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (54, 11, '650', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (55, 11, '082', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (56, 11, '852', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (57, 11, '500', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (58, 11, '008', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (59, 11, '520', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (60, 12, '245', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (61, 12, '100', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (62, 12, '260', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (63, 12, '260', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (64, 12, '022', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (65, 12, '650', '', '', 0, '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_fields` VALUES (66, 12, '082', '', '', 0, '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_fields` VALUES (67, 12, '500', '', '', 0, '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_fields` VALUES (68, 12, '520', '', '', 0, '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_fields` VALUES (69, 13, '245', '', '', 4, '2026-03-10 09:19:20', '2026-03-11 01:13:16');
INSERT INTO `marc_fields` VALUES (70, 13, '100', '', '', 3, '2026-03-10 09:19:20', '2026-03-11 01:13:16');
INSERT INTO `marc_fields` VALUES (71, 13, '260', '', '', 5, '2026-03-10 09:19:20', '2026-03-11 01:13:17');
INSERT INTO `marc_fields` VALUES (72, 13, '260', '', '', 5, '2026-03-10 09:19:20', '2026-03-11 01:11:23');
INSERT INTO `marc_fields` VALUES (73, 13, '020', '', '', 0, '2026-03-10 09:19:20', '2026-03-11 01:24:47');
INSERT INTO `marc_fields` VALUES (74, 13, '650', '', '', 2, '2026-03-10 09:19:20', '2026-03-11 01:13:16');
INSERT INTO `marc_fields` VALUES (75, 13, '082', '', '', 1, '2026-03-10 09:19:20', '2026-03-11 01:13:16');
INSERT INTO `marc_fields` VALUES (76, 13, '852', '', '', 6, '2026-03-10 09:19:20', '2026-03-11 01:13:17');
INSERT INTO `marc_fields` VALUES (79, 13, '082', '', '', 0, '2026-03-11 01:15:35', '2026-03-11 01:24:47');
INSERT INTO `marc_fields` VALUES (80, 13, '650', '', '', 0, '2026-03-11 01:15:35', '2026-03-11 01:24:47');
INSERT INTO `marc_fields` VALUES (81, 13, '100', '', '', 0, '2026-03-11 01:15:35', '2026-03-11 01:24:47');
INSERT INTO `marc_fields` VALUES (82, 13, '245', '', '', 0, '2026-03-11 01:15:35', '2026-03-11 01:24:47');
INSERT INTO `marc_fields` VALUES (83, 13, '260', '', '', 0, '2026-03-11 01:15:35', '2026-03-11 01:24:47');
INSERT INTO `marc_fields` VALUES (84, 13, '852', '', '', 0, '2026-03-11 01:15:35', '2026-03-11 01:24:47');

-- ----------------------------
-- Table structure for marc_framework_tags
-- ----------------------------
DROP TABLE IF EXISTS `marc_framework_tags`;
CREATE TABLE `marc_framework_tags`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `framework_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `marc_framework_tags_framework_id_tag_id_unique`(`framework_id` ASC, `tag_id` ASC) USING BTREE,
  INDEX `marc_framework_tags_tag_id_foreign`(`tag_id` ASC) USING BTREE,
  CONSTRAINT `marc_framework_tags_framework_id_foreign` FOREIGN KEY (`framework_id`) REFERENCES `marc_frameworks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `marc_framework_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `marc_tag_definitions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 89 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of marc_framework_tags
-- ----------------------------
INSERT INTO `marc_framework_tags` VALUES (68, 1, 16, 1, 0, '2026-03-02 09:27:12', '2026-03-02 09:27:12');
INSERT INTO `marc_framework_tags` VALUES (69, 1, 2, 1, 1, '2026-03-02 09:27:12', '2026-03-02 09:27:12');
INSERT INTO `marc_framework_tags` VALUES (70, 1, 18, 1, 2, '2026-03-02 09:27:12', '2026-03-02 09:27:12');
INSERT INTO `marc_framework_tags` VALUES (71, 1, 5, 1, 3, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (72, 1, 10, 1, 4, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (73, 1, 21, 1, 5, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (74, 1, 6, 1, 6, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (75, 1, 23, 1, 7, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (76, 1, 24, 1, 8, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (77, 1, 9, 1, 9, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (78, 1, 11, 1, 10, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (79, 1, 27, 1, 11, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (80, 1, 28, 1, 12, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (81, 1, 13, 1, 13, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (82, 1, 30, 1, 14, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (83, 1, 31, 1, 15, '2026-03-02 09:27:13', '2026-03-02 09:27:13');
INSERT INTO `marc_framework_tags` VALUES (84, 1, 14, 1, 16, '2026-03-02 09:27:14', '2026-03-02 09:27:14');
INSERT INTO `marc_framework_tags` VALUES (85, 1, 33, 1, 17, '2026-03-02 09:27:14', '2026-03-02 09:27:14');
INSERT INTO `marc_framework_tags` VALUES (86, 1, 34, 1, 18, '2026-03-02 09:27:14', '2026-03-02 09:27:14');
INSERT INTO `marc_framework_tags` VALUES (87, 1, 35, 1, 19, '2026-03-02 09:27:14', '2026-03-02 09:27:14');
INSERT INTO `marc_framework_tags` VALUES (88, 1, 36, 1, 20, '2026-03-02 09:27:14', '2026-03-02 09:27:14');

-- ----------------------------
-- Table structure for marc_frameworks
-- ----------------------------
DROP TABLE IF EXISTS `marc_frameworks`;
CREATE TABLE `marc_frameworks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `marc_frameworks_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of marc_frameworks
-- ----------------------------
INSERT INTO `marc_frameworks` VALUES (1, 'STANDARD', 'Khung biên mục chuẩn MARC21', 'Khung biên mục đầy đủ theo yêu cầu hệ thống (VTTLib Standard)', 1, '2026-03-02 08:50:25', '2026-03-02 09:27:12');
INSERT INTO `marc_frameworks` VALUES (3, 'F2', 'F2', 'F2', 1, '2026-03-02 09:04:10', '2026-03-02 09:04:10');

-- ----------------------------
-- Table structure for marc_subfield_definitions
-- ----------------------------
DROP TABLE IF EXISTS `marc_subfield_definitions`;
CREATE TABLE `marc_subfield_definitions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 0,
  `is_repeatable` tinyint(1) NOT NULL DEFAULT 0,
  `help_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `marc_subfield_definitions_tag_id_code_unique`(`tag_id` ASC, `code` ASC) USING BTREE,
  CONSTRAINT `marc_subfield_definitions_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `marc_tag_definitions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 105 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of marc_subfield_definitions
-- ----------------------------
INSERT INTO `marc_subfield_definitions` VALUES (1, NULL, 'c', 'Điều kiện mua (Giá)', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (2, NULL, 'd', 'Phân phối / Số lượng', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (3, NULL, 'a', 'Mã ngôn ngữ văn bản', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (4, NULL, 'a', 'Chỉ số phân loại (DDC)', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (5, NULL, 'b', 'Chỉ số ấn phẩm', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (6, NULL, '2', 'Chỉ số phiên bản Dewey', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (7, NULL, 'a', 'Họ và tên riêng', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (8, NULL, 'd', 'Ngày tháng (sinh/mất)', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (9, NULL, 'a', 'Thuật ngữ chủ đề', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (10, NULL, 'a', 'Nhan đề chính', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (11, NULL, 'b', 'Phụ đề / Nhan đề song song', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (12, NULL, 'c', 'Thông tin trách nhiệm (Tác giả, biên tập...)', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (13, NULL, 'a', 'Lần xuất bản', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (14, NULL, 'a', 'Nơi xuất bản', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (15, NULL, 'b', 'Nhà xuất bản', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (16, NULL, 'c', 'Ngày xuất bản', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (17, NULL, 'a', 'Số trang / Khối lượng', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (18, NULL, 'b', 'Đặc điểm vật lý khác', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (19, NULL, 'c', 'Khổ sách', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (20, NULL, 'e', 'Tài liệu đi kèm', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (21, NULL, 'a', 'Thuật ngữ chuyên ngành', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (22, NULL, 'j', 'VTTU code', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (23, NULL, '1', 'Mã quốc gia', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (24, NULL, 'b', 'Vị trí cụ thể', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (25, NULL, 'c', 'Vị trí xếp giá', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (26, NULL, 'u', 'Đường dẫn URL', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (27, NULL, 'a', 'Tên người nhập tin', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (28, NULL, 'a', 'Độ mật', 1, 0, 0, NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfield_definitions` VALUES (29, NULL, 'd', 'TEst nè', 1, 0, 0, NULL, '2026-01-13 21:34:14', '2026-01-13 21:34:14');
INSERT INTO `marc_subfield_definitions` VALUES (30, NULL, 'a', 'Số ISBN', 1, 0, 0, NULL, '2026-03-02 08:50:25', '2026-03-02 08:50:25');
INSERT INTO `marc_subfield_definitions` VALUES (32, 16, 'a', 'Số ISBN', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:12');
INSERT INTO `marc_subfield_definitions` VALUES (33, 16, 'c', 'Điều kiện mua/giá tiền', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:12');
INSERT INTO `marc_subfield_definitions` VALUES (34, 17, 'a', 'Mã ngôn ngữ văn bản', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (35, 18, 'a', 'Chỉ số phân loại', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (36, 18, '2', 'Số lần xuất bản của DDC', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (37, 19, 'a', 'Chủ đề', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (38, 20, 'a', 'Thuật ngữ chuyên ngành', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (39, 21, 'a', 'Tên tác giả', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (40, 22, 'a', 'Nhan đề chính', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (41, 22, 'b', 'Nhan đề phụ/Thông tin khác', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (42, 22, 'c', 'Thông tin trách nhiệm', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (43, 23, 'a', 'Số lần xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (44, 24, 'a', 'Nơi xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (45, 24, 'b', 'Nhà xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (46, 24, 'c', 'Năm xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (47, 25, 'a', 'Số trang', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (48, 25, 'c', 'Khổ/Kích thước', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (49, 25, 'e', 'Tài liệu kèm theo', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (50, 26, 'a', 'Ký hiệu thư viện', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (51, 26, 'c', 'Vị trí xếp giá', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (52, 27, 'u', 'Link URL', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (53, 28, 'a', 'Dấu hiệu ấn phẩm mới', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (54, 29, 'a', 'Tên người nhập', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (55, 30, 'a', 'Tên tác giả bổ sung', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (56, 31, 'a', 'Loại vật mang tin', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (57, 32, 'a', 'Cấp độ mật', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_subfield_definitions` VALUES (58, 33, 'a', 'Thông tin lưu chiểu', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:14');
INSERT INTO `marc_subfield_definitions` VALUES (59, 34, 'a', 'Trạng thái tài liệu mới', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:14');
INSERT INTO `marc_subfield_definitions` VALUES (60, 35, 'a', 'Số văn bản', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:14');
INSERT INTO `marc_subfield_definitions` VALUES (61, 36, 'a', 'Tên danh mục', 1, 0, 0, NULL, '2026-03-02 09:23:36', '2026-03-02 09:27:14');
INSERT INTO `marc_subfield_definitions` VALUES (62, 37, 'a', 'Số ISBN', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (63, 37, 'c', 'Điều kiện mua/giá tiền', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (64, 38, 'a', 'Mã ngôn ngữ văn bản', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (65, 39, 'a', 'Chỉ số phân loại', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (66, 39, '2', 'Số lần xuất bản của DDC', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (67, 40, 'a', 'Chủ đề', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (68, 41, 'a', 'Thuật ngữ chuyên ngành', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (69, 42, 'a', 'Tên tác giả', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (70, 43, 'a', 'Nhan đề chính', 1, 0, 0, NULL, '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_subfield_definitions` VALUES (71, 43, 'b', 'Nhan đề phụ/Thông tin khác', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (72, 43, 'c', 'Thông tin trách nhiệm', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (73, 44, 'a', 'Số lần xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (74, 45, 'a', 'Nơi xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (75, 45, 'b', 'Nhà xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (76, 45, 'c', 'Năm xuất bản', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (77, 46, 'a', 'Số trang', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (78, 46, 'c', 'Khổ/Kích thước', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (79, 46, 'e', 'Tài liệu kèm theo', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (80, 47, 'a', 'Ký hiệu thư viện', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (81, 47, 'c', 'Vị trí xếp giá', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (82, 48, 'u', 'Link URL', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (83, 49, 'a', 'Dấu hiệu ấn phẩm mới', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (84, 50, 'a', 'Tên người nhập', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (85, 51, 'a', 'Tên tác giả bổ sung', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (86, 52, 'a', 'Loại vật mang tin', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (87, 53, 'a', 'Cấp độ mật', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (88, 54, 'a', 'Thông tin lưu chiểu', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (89, 55, 'a', 'Trạng thái tài liệu mới', 1, 0, 0, NULL, '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_subfield_definitions` VALUES (90, 56, 'a', 'Số văn bản', 1, 0, 0, NULL, '2026-03-02 09:23:49', '2026-03-02 09:23:49');
INSERT INTO `marc_subfield_definitions` VALUES (91, 57, 'a', 'Tên danh mục', 1, 0, 0, NULL, '2026-03-02 09:23:49', '2026-03-02 09:23:49');
INSERT INTO `marc_subfield_definitions` VALUES (92, 2, 'a', 'Mã ngôn ngữ văn bản', 1, 0, 0, NULL, '2026-03-02 09:24:08', '2026-03-02 09:27:12');
INSERT INTO `marc_subfield_definitions` VALUES (93, 5, 'a', 'Chủ đề', 1, 0, 0, NULL, '2026-03-02 09:24:08', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (94, 10, 'a', 'Thuật ngữ chuyên ngành', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (95, 6, 'a', 'Nhan đề chính', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (96, 6, 'b', 'Nhan đề phụ/Thông tin khác', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (97, 6, 'c', 'Thông tin trách nhiệm', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (98, 9, 'a', 'Số trang', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (99, 9, 'c', 'Khổ/Kích thước', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (100, 9, 'e', 'Tài liệu kèm theo', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (101, 11, 'a', 'Ký hiệu thư viện', 1, 0, 0, NULL, '2026-03-02 09:24:09', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (102, 11, 'c', 'Vị trí xếp giá', 1, 0, 0, NULL, '2026-03-02 09:24:10', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (103, 13, 'a', 'Tên người nhập', 1, 0, 0, NULL, '2026-03-02 09:24:10', '2026-03-02 09:27:13');
INSERT INTO `marc_subfield_definitions` VALUES (104, 14, 'a', 'Cấp độ mật', 1, 0, 0, NULL, '2026-03-02 09:24:10', '2026-03-02 09:27:14');

-- ----------------------------
-- Table structure for marc_subfields
-- ----------------------------
DROP TABLE IF EXISTS `marc_subfields`;
CREATE TABLE `marc_subfields`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `marc_field_id` bigint UNSIGNED NOT NULL,
  `code` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `marc_subfields_marc_field_id_foreign`(`marc_field_id` ASC) USING BTREE,
  CONSTRAINT `marc_subfields_marc_field_id_foreign` FOREIGN KEY (`marc_field_id`) REFERENCES `marc_fields` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 108 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of marc_subfields
-- ----------------------------
INSERT INTO `marc_subfields` VALUES (1, 1, 'c', '250.000 VNĐ', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (2, 1, 'd', 'Bản in phổ thông', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (3, 2, 'a', 'vie', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (4, 3, 'a', '005.133', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (5, 3, 'b', 'Phòng kỹ thuật', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (6, 3, '2', '23', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (7, 4, 'a', 'Nguyễn Văn A', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (8, 5, 'a', 'Lập trình PHP', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (9, 6, 'a', 'Lập trình Laravel từ cơ bản đến nâng cao', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (10, 6, 'b', 'Giáo trình đào tạo nội bộ', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (11, 6, 'c', 'Nguyễn Văn A; Trần Văn B', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (12, 7, 'a', 'Tái bản lần thứ 2', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (13, 8, 'a', 'Hà Nội', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (14, 8, 'b', 'NXB Công Nghệ', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (15, 8, 'c', '2025', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (16, 9, 'a', '350 trang', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (17, 9, 'b', 'Minh họa màu', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (18, 9, 'c', '24cm', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (19, 9, 'e', 'Kèm CD bài tập', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (20, 10, 'a', 'Phát triển Web', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (21, 11, 'j', 'VTTU', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (22, 11, '1', 'VN', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (23, 11, 'b', 'Kho chính', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (24, 11, 'c', 'Giá 01-A', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (25, 12, 'u', 'https://vttlib.com/ebook/laravel-basic', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (26, 13, 'a', 'Mới nhập tháng 1/2026', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (27, 14, 'a', 'Trung tâm Công nghệ phần mềm', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (28, 15, 'a', 'Nguyễn Văn C (Biên tập)', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (29, 16, 'a', 'Giấy in', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (30, 17, 'a', 'Bình thường', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (31, 18, 'a', 'DEP-2026-001', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (32, 19, 'a', 'HOT_BOOK', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (33, 20, 'a', 'Area 51 Level', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (34, 21, 'a', 'Danh mục kỹ thuật', '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_subfields` VALUES (41, 28, 'a', 'AAA', '2026-01-14 19:44:09', '2026-01-14 20:16:59');
INSERT INTO `marc_subfields` VALUES (42, 29, '2', 'AAA', '2026-01-14 19:44:09', '2026-01-14 19:44:09');
INSERT INTO `marc_subfields` VALUES (43, 30, 'a', 'A', '2026-01-14 20:09:05', '2026-01-14 20:09:05');
INSERT INTO `marc_subfields` VALUES (44, 31, 'a', 'ACCSC', '2026-02-05 09:37:32', '2026-02-05 09:37:32');
INSERT INTO `marc_subfields` VALUES (45, 32, 'c', 'abc', '2026-03-02 07:01:49', '2026-03-02 07:01:49');
INSERT INTO `marc_subfields` VALUES (46, 33, 'a', 'A', '2026-03-06 01:46:30', '2026-03-06 01:46:30');
INSERT INTO `marc_subfields` VALUES (49, 36, 'a', 'abc', '2026-03-06 08:28:28', '2026-03-06 08:28:28');
INSERT INTO `marc_subfields` VALUES (50, 37, 'a', 'abc', '2026-03-09 00:48:55', '2026-03-09 00:48:55');
INSERT INTO `marc_subfields` VALUES (64, 49, 'a', 'Cấu trúc dữ liệu và Giải thuật', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (65, 50, 'a', 'Trần Thị B', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (66, 51, 'a', 'TP.HCM', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (67, 51, 'b', 'NXB Trẻ', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (68, 51, 'c', '2022', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (69, 52, 'a', '2022', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (70, 53, 'a', '978-604-2-98765-4', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (71, 54, 'a', 'Công nghệ thông tin', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (72, 55, 'a', '005.73', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (73, 56, 'a', 'Kệ sách B2', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (74, 57, 'a', 'Tài liệu tham khảo', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (75, 58, 'a', 'vietnamese', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (76, 59, 'a', 'Phân tích các thuật toán cơ bản', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (77, 60, 'a', 'Tạp chí Tin học & Đời sống', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (78, 61, 'a', 'Nhiều tác giả', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (79, 62, 'a', 'Hà Nội', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (80, 62, 'b', 'NXB Khoa Học', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (81, 62, 'c', '2024', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (82, 63, 'a', '2024', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (83, 64, 'a', '1234-5678', '2026-03-10 09:19:19', '2026-03-10 09:19:19');
INSERT INTO `marc_subfields` VALUES (84, 65, 'a', 'Tin học', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (85, 66, 'a', '004', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (86, 67, 'a', 'Số đặc biệt tháng 3', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (87, 68, 'a', 'Tạp chí định kỳ', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (88, 69, 'a', 'Tối ưu hóa cơ sở dữ liệu', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (89, 70, 'a', 'Lê Văn C', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (93, 72, 'a', '2021', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (94, 73, 'a', '9781234567897', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (95, 74, 'a', 'Cơ sở dữ liệu', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (96, 75, 'a', '005.74', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (97, 76, 'a', 'Kệ sách A2', '2026-03-10 09:19:20', '2026-03-10 09:19:20');
INSERT INTO `marc_subfields` VALUES (100, 71, 'a', '2021', '2026-03-11 01:11:03', '2026-03-11 01:11:03');
INSERT INTO `marc_subfields` VALUES (101, 73, 'c', '999999', '2026-03-11 01:12:17', '2026-03-11 01:20:12');
INSERT INTO `marc_subfields` VALUES (102, 79, 'a', '005.74', '2026-03-11 01:15:35', '2026-03-11 01:15:35');
INSERT INTO `marc_subfields` VALUES (103, 80, 'a', 'Cơ sở dữ liệu', '2026-03-11 01:15:35', '2026-03-11 01:15:35');
INSERT INTO `marc_subfields` VALUES (104, 81, 'a', 'Lê Văn C', '2026-03-11 01:15:35', '2026-03-11 01:15:35');
INSERT INTO `marc_subfields` VALUES (105, 82, 'a', 'Tối ưu hóa cơ sở dữ liệu', '2026-03-11 01:15:35', '2026-03-11 01:15:35');
INSERT INTO `marc_subfields` VALUES (106, 83, 'a', '2021', '2026-03-11 01:15:35', '2026-03-11 01:15:35');
INSERT INTO `marc_subfields` VALUES (107, 84, 'a', 'Kệ sách A2', '2026-03-11 01:15:35', '2026-03-11 01:15:35');

-- ----------------------------
-- Table structure for marc_tag_definitions
-- ----------------------------
DROP TABLE IF EXISTS `marc_tag_definitions`;
CREATE TABLE `marc_tag_definitions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 58 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of marc_tag_definitions
-- ----------------------------
INSERT INTO `marc_tag_definitions` VALUES (1, '020', 'CHỈ SỐ ISBN', NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_tag_definitions` VALUES (2, '041', 'MÃ NGÔN NGỮ', 'Xác định ngôn ngữ nội dung tài liệu', '2026-01-13 21:21:16', '2026-03-02 09:24:08');
INSERT INTO `marc_tag_definitions` VALUES (3, '082', 'CHỈ SỐ PHÂN LOẠI THẬP PHÂN DEWEY (DDC)', NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_tag_definitions` VALUES (4, '100', 'TIÊU ĐỀ MÔ TẢ CHÍNH - TÁC GIẢ CÁ NHÂN', NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_tag_definitions` VALUES (5, '150', 'CHỦ ĐỀ', 'Thuật ngữ mô tả nội dung', '2026-01-13 21:21:16', '2026-03-02 09:24:08');
INSERT INTO `marc_tag_definitions` VALUES (6, '245', 'NHAN ĐỀ VÀ THÔNG TIN TRÁCH NHIỆM', 'Nhan đề chính, phụ và thông tin chịu trách nhiệm', '2026-01-13 21:21:16', '2026-03-02 09:24:09');
INSERT INTO `marc_tag_definitions` VALUES (7, '250', 'THÔNG TIN VỀ LẦN XUẤT BẢN', NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_tag_definitions` VALUES (8, '260', 'THÔNG TIN VỀ XUẤT BẢN, PHÁT HÀNH', NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_tag_definitions` VALUES (9, '300', 'MÔ TẢ VẬT LÝ', 'Số trang, đặc điểm vật lý, khổ, tư liệu kèm theo', '2026-01-13 21:21:16', '2026-03-02 09:24:09');
INSERT INTO `marc_tag_definitions` VALUES (10, '650', 'CHUYÊN NGÀNH', 'Phân loại theo lĩnh vực đào tạo', '2026-01-13 21:21:16', '2026-03-02 09:24:08');
INSERT INTO `marc_tag_definitions` VALUES (11, '852', 'VỊ TRÍ/SỐ BÁO DANH', 'Ký hiệu thư viện, số quản lý, vị trí xếp giá', '2026-01-13 21:21:16', '2026-03-02 09:24:09');
INSERT INTO `marc_tag_definitions` VALUES (12, '856', 'TƯ LIỆU ĐÍNH KÈM (URL)', NULL, '2026-01-13 21:21:16', '2026-01-13 21:21:16');
INSERT INTO `marc_tag_definitions` VALUES (13, '911', 'NGƯỜI NHẬP TIN', '', '2026-01-13 21:21:16', '2026-03-02 09:24:10');
INSERT INTO `marc_tag_definitions` VALUES (14, '926', 'MỨC ĐỘ MẬT', '', '2026-01-13 21:21:16', '2026-03-02 09:24:10');
INSERT INTO `marc_tag_definitions` VALUES (16, '020', 'ISBN', 'Thông tin nhận diện ấn phẩm, điều kiện mua và phân phối', '2026-03-02 09:23:35', '2026-03-02 09:23:35');
INSERT INTO `marc_tag_definitions` VALUES (17, '041', 'Mã ngôn ngữ', 'Xác định ngôn ngữ nội dung tài liệu', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (18, '082', 'Chỉ số phân loại DDC', 'Phục vụ xếp giá, lưu thông và tra cứu', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (19, '150', 'Chủ đề', 'Thuật ngữ mô tả nội dung', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (20, '650', 'Chuyên ngành', 'Phân loại theo lĩnh vực đào tạo', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (21, '100', 'Tác giả cá nhân', 'Tác giả chính của tài liệu', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (22, '245', 'Nhan đề và thông tin trách nhiệm', 'Nhan đề chính, phụ và thông tin chịu trách nhiệm', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (23, '250', 'Lần xuất bản', 'Thông tin về lần tái bản/xuất bản', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (24, '260', 'Thông tin xuất bản, phát hành', 'Nơi xuất bản, nhà xuất bản, năm xuất bản', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (25, '300', 'Mô tả vật lý', 'Số trang, đặc điểm vật lý, khổ, tư liệu kèm theo', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (26, '852', 'Vị trí/số báo danh', 'Ký hiệu thư viện, số quản lý, vị trí xếp giá', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (27, '856', 'Tư liệu đính kèm', 'Liên kết tài liệu số (URL)', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (28, '900', 'Biểu ghi ấn phẩm mới', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (29, '911', 'Người nhập tin', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (30, '920', 'Tác giả bổ sung', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (31, '925', 'Vật mang tin', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (32, '926', 'Mức độ mật', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (33, '930', 'Deposit (lưu chiểu)', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (34, '933', 'Dấu hiệu tài liệu mới', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (35, '940', 'Văn bản pháp lý', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (36, '941', 'Danh mục tài liệu', '', '2026-03-02 09:23:36', '2026-03-02 09:23:36');
INSERT INTO `marc_tag_definitions` VALUES (37, '020', 'ISBN', 'Thông tin nhận diện ấn phẩm, điều kiện mua và phân phối', '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_tag_definitions` VALUES (38, '041', 'Mã ngôn ngữ', 'Xác định ngôn ngữ nội dung tài liệu', '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_tag_definitions` VALUES (39, '082', 'Chỉ số phân loại DDC', 'Phục vụ xếp giá, lưu thông và tra cứu', '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_tag_definitions` VALUES (40, '150', 'Chủ đề', 'Thuật ngữ mô tả nội dung', '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_tag_definitions` VALUES (41, '650', 'Chuyên ngành', 'Phân loại theo lĩnh vực đào tạo', '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_tag_definitions` VALUES (42, '100', 'Tác giả cá nhân', 'Tác giả chính của tài liệu', '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_tag_definitions` VALUES (43, '245', 'Nhan đề và thông tin trách nhiệm', 'Nhan đề chính, phụ và thông tin chịu trách nhiệm', '2026-03-02 09:23:47', '2026-03-02 09:23:47');
INSERT INTO `marc_tag_definitions` VALUES (44, '250', 'Lần xuất bản', 'Thông tin về lần tái bản/xuất bản', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (45, '260', 'Thông tin xuất bản, phát hành', 'Nơi xuất bản, nhà xuất bản, năm xuất bản', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (46, '300', 'Mô tả vật lý', 'Số trang, đặc điểm vật lý, khổ, tư liệu kèm theo', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (47, '852', 'Vị trí/số báo danh', 'Ký hiệu thư viện, số quản lý, vị trí xếp giá', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (48, '856', 'Tư liệu đính kèm', 'Liên kết tài liệu số (URL)', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (49, '900', 'Biểu ghi ấn phẩm mới', '', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (50, '911', 'Người nhập tin', '', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (51, '920', 'Tác giả bổ sung', '', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (52, '925', 'Vật mang tin', '', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (53, '926', 'Mức độ mật', '', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (54, '930', 'Deposit (lưu chiểu)', '', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (55, '933', 'Dấu hiệu tài liệu mới', '', '2026-03-02 09:23:48', '2026-03-02 09:23:48');
INSERT INTO `marc_tag_definitions` VALUES (56, '940', 'Văn bản pháp lý', '', '2026-03-02 09:23:49', '2026-03-02 09:23:49');
INSERT INTO `marc_tag_definitions` VALUES (57, '941', 'Danh mục tài liệu', '', '2026-03-02 09:23:49', '2026-03-02 09:23:49');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2026_01_12_215445_create_books_table', 1);
INSERT INTO `migrations` VALUES (5, '2026_01_13_193437_create_roles_table', 1);
INSERT INTO `migrations` VALUES (6, '2026_01_13_193438_create_role_user_table', 1);
INSERT INTO `migrations` VALUES (7, '2026_01_13_194555_create_sidebars_table', 1);
INSERT INTO `migrations` VALUES (8, '2026_01_13_194557_create_user_role_sidebars_table', 1);
INSERT INTO `migrations` VALUES (9, '2026_01_14_035500_create_bibliographic_records_table', 1);
INSERT INTO `migrations` VALUES (10, '2026_01_14_035501_create_marc_fields_table', 1);
INSERT INTO `migrations` VALUES (11, '2026_01_14_035502_create_marc_subfields_table', 1);
INSERT INTO `migrations` VALUES (12, '2026_01_14_040000_create_marc_definitions_table', 1);
INSERT INTO `migrations` VALUES (13, '2026_01_14_041000_update_marc_definitions_features', 1);
INSERT INTO `migrations` VALUES (14, '2026_01_14_041600_add_marc_framework_to_sidebar', 1);
INSERT INTO `migrations` VALUES (15, '2026_01_14_042500_add_parent_id_to_sidebars_table', 2);
INSERT INTO `migrations` VALUES (16, '2026_01_14_043000_restructure_sidebar_for_cataloging', 3);
INSERT INTO `migrations` VALUES (17, '2026_01_13_214045_update_sidebar_books_route_to_marc', 4);
INSERT INTO `migrations` VALUES (18, '2026_01_14_184455_add_status_to_bibliographic_records_table', 4);
INSERT INTO `migrations` VALUES (19, '2026_01_14_204242_create_book_items_table', 5);
INSERT INTO `migrations` VALUES (20, '2026_01_15_203521_add_metadata_fields_to_bibliographic_records_table', 6);
INSERT INTO `migrations` VALUES (21, '2026_01_15_211220_add_patron_management_to_sidebar', 7);
INSERT INTO `migrations` VALUES (22, '2026_01_15_212810_create_patron_structure_tables', 8);
INSERT INTO `migrations` VALUES (23, '2026_01_19_132353_create_system_settings_and_barcode_configs_table', 9);
INSERT INTO `migrations` VALUES (24, '2026_01_19_133812_add_system_settings_to_sidebar', 10);
INSERT INTO `migrations` VALUES (25, '2026_01_19_142552_create_branches_and_storage_locations_tables', 11);
INSERT INTO `migrations` VALUES (26, '2026_01_19_142600_add_location_ids_to_book_items_table', 12);
INSERT INTO `migrations` VALUES (27, '2026_01_19_143356_grant_system_settings_access_to_root', 13);
INSERT INTO `migrations` VALUES (28, '2026_01_19_185313_update_system_settings_route_to_admin', 14);
INSERT INTO `migrations` VALUES (29, '2026_01_19_193003_add_id_card_to_patron_details_table', 14);
INSERT INTO `migrations` VALUES (30, '2026_01_19_202131_create_activity_logs_table', 15);
INSERT INTO `migrations` VALUES (31, '2026_01_19_202323_add_soft_deletes_to_patron_details_table', 15);
INSERT INTO `migrations` VALUES (32, '2026_01_24_000000_extend_users_table', 16);
INSERT INTO `migrations` VALUES (33, '2026_01_24_140000_create_circulation_system_tables', 17);
INSERT INTO `migrations` VALUES (34, '2026_01_24_142600_add_circulation_to_sidebar', 18);
INSERT INTO `migrations` VALUES (35, '2026_01_24_150000_create_document_types_table', 19);
INSERT INTO `migrations` VALUES (36, '2026_01_24_150100_add_document_types_to_sidebar', 19);
INSERT INTO `migrations` VALUES (37, '2026_01_24_162600_create_z3950_servers_table', 20);
INSERT INTO `migrations` VALUES (38, '2026_01_24_162700_add_z3950_to_sidebar', 20);
INSERT INTO `migrations` VALUES (39, '2026_02_02_043015_add_user_management_to_sidebar', 21);
INSERT INTO `migrations` VALUES (40, '2026_02_02_043337_grant_user_management_access_to_admin_and_root', 22);
INSERT INTO `migrations` VALUES (41, '2026_02_02_083205_create_role_sidebars_table', 23);
INSERT INTO `migrations` VALUES (42, '2026_02_02_085234_add_role_management_to_sidebar', 24);
INSERT INTO `migrations` VALUES (43, '2026_02_02_085916_initialize_role_sidebar_templates', 25);
INSERT INTO `migrations` VALUES (44, '2026_02_03_081712_add_username_to_users_table', 26);
INSERT INTO `migrations` VALUES (45, '2026_02_04_042727_create_suppliers_table', 27);
INSERT INTO `migrations` VALUES (46, '2026_02_04_043056_add_patron_categories_to_sidebar', 28);
INSERT INTO `migrations` VALUES (47, '2026_02_04_071856_add_request_details_to_activity_logs', 29);
INSERT INTO `migrations` VALUES (48, '2026_02_05_092522_add_cover_image_to_bibliographic_records_table', 30);
INSERT INTO `migrations` VALUES (49, '2026_03_02_011712_merge_root_to_admin_sidebar', 31);
INSERT INTO `migrations` VALUES (50, '2026_03_02_083333_create_marc_frameworks_table', 32);
INSERT INTO `migrations` VALUES (51, '2026_03_02_085141_fix_marc_subfield_definitions_relationships', 33);
INSERT INTO `migrations` VALUES (52, '2026_03_02_091403_refactor_marc_definitions_to_dictionary_based', 34);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for patron_addresses
-- ----------------------------
DROP TABLE IF EXISTS `patron_addresses`;
CREATE TABLE `patron_addresses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patron_detail_id` bigint UNSIGNED NOT NULL,
  `address_line` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `patron_addresses_patron_detail_id_foreign`(`patron_detail_id` ASC) USING BTREE,
  CONSTRAINT `patron_addresses_patron_detail_id_foreign` FOREIGN KEY (`patron_detail_id`) REFERENCES `patron_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of patron_addresses
-- ----------------------------

-- ----------------------------
-- Table structure for patron_details
-- ----------------------------
DROP TABLE IF EXISTS `patron_details`;
CREATE TABLE `patron_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `patron_group_id` bigint UNSIGNED NULL DEFAULT NULL,
  `patron_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_card` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mssv` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `card_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `is_read_only` tinyint(1) NOT NULL DEFAULT 0,
  `is_waiting_for_print` tinyint(1) NOT NULL DEFAULT 0,
  `dob` date NULL DEFAULT NULL,
  `gender` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `school_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `batch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `branch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `classification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'individual',
  `card_fee` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `deposit` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `registration_date` date NULL DEFAULT NULL,
  `expiry_date` date NULL DEFAULT NULL,
  `creator_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `patron_details_patron_code_unique`(`patron_code` ASC) USING BTREE,
  UNIQUE INDEX `patron_details_mssv_unique`(`mssv` ASC) USING BTREE,
  INDEX `patron_details_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `patron_details_creator_id_foreign`(`creator_id` ASC) USING BTREE,
  INDEX `patron_details_patron_group_id_foreign`(`patron_group_id` ASC) USING BTREE,
  CONSTRAINT `patron_details_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `patron_details_patron_group_id_foreign` FOREIGN KEY (`patron_group_id`) REFERENCES `patron_groups` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `patron_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of patron_details
-- ----------------------------
INSERT INTO `patron_details` VALUES (3, 15, NULL, '260119210352', NULL, '1234567890', '0365914056', 'PoPi', 'normal', 0, 1, '1999-06-09', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'all', 'student', 0.00, 0.00, 0.00, '2026-01-19', '2027-01-19', 1, NULL, '2026-01-19 21:05:18', '2026-01-19 21:05:18', NULL);

-- ----------------------------
-- Table structure for patron_groups
-- ----------------------------
DROP TABLE IF EXISTS `patron_groups`;
CREATE TABLE `patron_groups`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `patron_groups_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of patron_groups
-- ----------------------------
INSERT INTO `patron_groups` VALUES (2, 'Giáo Viên', 'gv', 'đọc giả là Giảng Viên', 1, 2, '2026-03-02 07:34:20', '2026-03-02 07:41:41');
INSERT INTO `patron_groups` VALUES (3, 'Học Sinh', 'st', 'thể loại đọc giả là sinh viên', 1, 1, '2026-03-02 07:34:50', '2026-03-02 07:41:41');

-- ----------------------------
-- Table structure for reservations
-- ----------------------------
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patron_detail_id` bigint UNSIGNED NOT NULL,
  `bibliographic_record_id` bigint UNSIGNED NOT NULL,
  `book_item_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reservation_date` datetime NOT NULL,
  `expiry_date` datetime NULL DEFAULT NULL,
  `pickup_date` datetime NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `pickup_branch_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `notified_at` datetime NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `reservations_book_item_id_foreign`(`book_item_id` ASC) USING BTREE,
  INDEX `reservations_pickup_branch_id_foreign`(`pickup_branch_id` ASC) USING BTREE,
  INDEX `reservations_patron_detail_id_status_index`(`patron_detail_id` ASC, `status` ASC) USING BTREE,
  INDEX `reservations_bibliographic_record_id_status_index`(`bibliographic_record_id` ASC, `status` ASC) USING BTREE,
  CONSTRAINT `reservations_bibliographic_record_id_foreign` FOREIGN KEY (`bibliographic_record_id`) REFERENCES `bibliographic_records` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `reservations_book_item_id_foreign` FOREIGN KEY (`book_item_id`) REFERENCES `book_items` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `reservations_patron_detail_id_foreign` FOREIGN KEY (`patron_detail_id`) REFERENCES `patron_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `reservations_pickup_branch_id_foreign` FOREIGN KEY (`pickup_branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reservations
-- ----------------------------

-- ----------------------------
-- Table structure for role_sidebars
-- ----------------------------
DROP TABLE IF EXISTS `role_sidebars`;
CREATE TABLE `role_sidebars`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint UNSIGNED NOT NULL,
  `sidebar_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `role_sidebars_role_id_foreign`(`role_id` ASC) USING BTREE,
  INDEX `role_sidebars_sidebar_id_foreign`(`sidebar_id` ASC) USING BTREE,
  CONSTRAINT `role_sidebars_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_sidebars_sidebar_id_foreign` FOREIGN KEY (`sidebar_id`) REFERENCES `sidebars` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 77 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_sidebars
-- ----------------------------
INSERT INTO `role_sidebars` VALUES (2, 3, 1, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (3, 3, 2, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (5, 3, 4, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (6, 3, 5, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (7, 3, 6, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (8, 3, 7, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (9, 3, 8, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (10, 3, 9, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (14, 3, 13, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (15, 3, 14, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (16, 3, 15, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (17, 3, 16, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (18, 3, 17, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (19, 3, 18, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (20, 3, 19, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (21, 3, 20, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (23, 1, 1, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (24, 1, 2, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (26, 1, 4, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (27, 1, 5, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (28, 1, 6, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (29, 1, 7, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (30, 1, 8, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (31, 1, 9, '2026-02-02 09:01:33', '2026-02-02 09:01:33');
INSERT INTO `role_sidebars` VALUES (35, 1, 13, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (36, 1, 14, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (37, 1, 15, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (38, 1, 16, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (39, 1, 17, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (40, 1, 18, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (41, 1, 19, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (42, 1, 20, '2026-02-02 09:01:34', '2026-02-02 09:01:34');
INSERT INTO `role_sidebars` VALUES (49, 7, 2, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (51, 7, 15, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (52, 7, 7, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (53, 7, 8, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (54, 7, 9, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (55, 7, 5, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (56, 7, 6, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (57, 7, 4, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (58, 7, 1, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (59, 7, 19, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (60, 7, 20, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (61, 7, 13, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (62, 7, 14, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (65, 7, 16, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (66, 7, 17, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (67, 7, 18, NULL, NULL);
INSERT INTO `role_sidebars` VALUES (69, 3, 24, '2026-03-02 01:19:20', '2026-03-02 01:19:20');
INSERT INTO `role_sidebars` VALUES (70, 3, 25, '2026-03-02 01:19:20', '2026-03-02 01:19:20');
INSERT INTO `role_sidebars` VALUES (71, 3, 26, '2026-03-02 01:19:20', '2026-03-02 01:19:20');
INSERT INTO `role_sidebars` VALUES (72, 3, 27, '2026-03-02 01:19:20', '2026-03-02 01:19:20');
INSERT INTO `role_sidebars` VALUES (73, 1, 24, '2026-03-02 01:38:43', '2026-03-02 01:38:43');
INSERT INTO `role_sidebars` VALUES (74, 1, 25, '2026-03-02 01:38:43', '2026-03-02 01:38:43');
INSERT INTO `role_sidebars` VALUES (75, 1, 26, '2026-03-02 01:38:43', '2026-03-02 01:38:43');
INSERT INTO `role_sidebars` VALUES (76, 1, 27, '2026-03-02 01:38:43', '2026-03-02 01:38:43');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_user_user_id_role_id_unique`(`user_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `role_user_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES (1, 1, 3, NULL, NULL);
INSERT INTO `role_user` VALUES (2, 2, 1, NULL, NULL);
INSERT INTO `role_user` VALUES (3, 3, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (4, 4, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (5, 5, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (6, 6, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (7, 7, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (8, 8, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (9, 9, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (10, 10, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (11, 11, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (12, 12, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (16, 15, 2, NULL, NULL);
INSERT INTO `role_user` VALUES (19, 16, 7, NULL, NULL);
INSERT INTO `role_user` VALUES (20, 4, 7, NULL, NULL);
INSERT INTO `role_user` VALUES (21, 16, 3, NULL, NULL);
INSERT INTO `role_user` VALUES (23, 1, 1, NULL, NULL);
INSERT INTO `role_user` VALUES (24, 16, 1, NULL, NULL);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'admin', 'Quản trị viên', '2026-01-13 21:21:15', '2026-01-13 21:21:15');
INSERT INTO `roles` VALUES (2, 'visitor', 'Khách', '2026-01-13 21:21:15', '2026-01-13 21:21:15');
INSERT INTO `roles` VALUES (3, 'root', 'Siêu quản trị', '2026-01-13 21:21:15', '2026-01-13 21:21:15');
INSERT INTO `roles` VALUES (7, 'Test', 'Test', '2026-02-04 00:40:41', '2026-02-04 00:40:41');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('LDv88jOLWD3vsUi9HfWN8u5uyhWlSb0LnOxC2bVp', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQjlJenpXVlhGZTBGOHZyQTlxRjA5T25mMkd4VXlZWFZZMldkTEZLaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90b3BzZWNyZXQvbWFyYy1ib29rcyI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4ubWFyYy5ib29rIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1773735117);
INSERT INTO `sessions` VALUES ('mSO8HiCTFHXyJsjaQQVfHszyJTfpo433CSZk5TIi', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiR0V1MHFFemlybjRqaWthRjdvaG11ZVBIZzNsaU1iNHM2SFk4V3BGViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90b3BzZWNyZXQvbWFyYy1ib29rcy9mb3JtLzEzP3RhYj0wIjtzOjU6InJvdXRlIjtzOjIwOiJhZG1pbi5tYXJjLmJvb2suZm9ybSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo2OiJsb2NhbGUiO3M6MjoidmkiO30=', 1773198254);
INSERT INTO `sessions` VALUES ('SCCdH9CIVTRM1ut9Boj2ESzZtw3iasPrQZboEBHA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREtESG1lRlhnU3lBZXJDYzhFNjk0bm01Nk1pV0VjZlpmTEVVaXJzSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90b3BzZWNyZXQvbWFyYy1pbXBvcnQiO3M6NToicm91dGUiO3M6MjM6ImFkbWluLm1hcmMuaW1wb3J0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1773474931);

-- ----------------------------
-- Table structure for sidebars
-- ----------------------------
DROP TABLE IF EXISTS `sidebars`;
CREATE TABLE `sidebars`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sidebars_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `sidebars_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `sidebars` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sidebars
-- ----------------------------
INSERT INTO `sidebars` VALUES (1, 'MARC Framework', 'admin.marc.index', '<i class=\"fas fa-book-open\"></i>', 2, 1, '2026-01-13 21:21:15', '2026-03-10 07:11:03', 6);
INSERT INTO `sidebars` VALUES (2, 'Dashboard', 'admin.dashboard', '<i class=\"fas fa-tachometer-alt\"></i>', 1, 1, '2026-01-13 21:21:16', '2026-03-10 07:11:03', NULL);
INSERT INTO `sidebars` VALUES (4, 'Books', 'admin.marc.book', '<i class=\"fas fa-book\"></i>', 1, 1, '2026-01-13 21:21:16', '2026-03-10 07:11:03', 6);
INSERT INTO `sidebars` VALUES (5, 'Loans', '#', '<i class=\"fas fa-hand-holding-usd\"></i>', 5, 1, '2026-01-13 21:21:16', '2026-03-10 07:11:03', NULL);
INSERT INTO `sidebars` VALUES (6, 'Cataloging', '#', '<i class=\"fas fa-layer-group\"></i>', 3, 1, '2026-01-13 21:24:51', '2026-03-10 07:11:03', NULL);
INSERT INTO `sidebars` VALUES (7, 'Patron Management', '#', '<i class=\"fas fa-users\"></i>', 6, 1, '2026-01-15 21:12:46', '2026-03-10 07:11:03', NULL);
INSERT INTO `sidebars` VALUES (8, 'Patron List', 'admin.patrons.index', '<i class=\"fas fa-list\"></i>', 1, 1, '2026-01-15 21:12:46', '2026-03-10 07:11:03', 7);
INSERT INTO `sidebars` VALUES (9, 'Add New Patron', 'admin.patrons.create', '<i class=\"fas fa-user-plus\"></i>', 2, 1, '2026-01-15 21:12:46', '2026-03-10 07:11:03', 7);
INSERT INTO `sidebars` VALUES (13, 'System Management', '#', '<i class=\"fas fa-cogs\"></i>', 10, 1, '2026-01-19 14:01:47', '2026-03-10 07:11:03', NULL);
INSERT INTO `sidebars` VALUES (14, 'System Settings', 'admin.settings.index', '<i class=\"fas fa-cog\"></i>', 1, 1, '2026-01-19 14:01:47', '2026-03-10 07:11:03', 13);
INSERT INTO `sidebars` VALUES (15, 'Circulation', '#', '<i class=\"fas fa-sync-alt\"></i>', 35, 1, '2026-01-24 07:26:41', '2026-03-10 07:11:03', NULL);
INSERT INTO `sidebars` VALUES (16, 'Policies', 'admin.circulation.index', '<i class=\"fas fa-clipboard-list\"></i>', 1, 1, '2026-01-24 07:26:41', '2026-03-10 07:11:03', 15);
INSERT INTO `sidebars` VALUES (17, 'Loan Desk', 'admin.circulation.loan-desk', '<i class=\"fas fa-desktop\"></i>', 2, 1, '2026-01-24 07:26:41', '2026-03-10 07:11:03', 15);
INSERT INTO `sidebars` VALUES (18, 'Fines', 'admin.circulation.fines', '<i class=\"fas fa-dollar-sign\"></i>', 3, 1, '2026-01-24 07:26:41', '2026-03-10 07:11:03', 15);
INSERT INTO `sidebars` VALUES (19, 'Document Types', 'admin.document-types.index', '<i class=\"fas fa-file-alt\"></i>', 5, 1, '2026-01-24 09:03:28', '2026-03-10 07:11:03', 6);
INSERT INTO `sidebars` VALUES (20, 'Z39.50 Servers', 'admin.z3950.index', '<i class=\"fas fa-server\"></i>', 10, 1, '2026-01-24 09:39:09', '2026-03-10 07:11:03', 6);
INSERT INTO `sidebars` VALUES (24, 'User Privilege Management', 'admin.users.privileges', '<i class=\"fas fa-shield-alt\"></i>', 2, 1, '2026-03-02 01:19:20', '2026-03-10 07:11:03', 29);
INSERT INTO `sidebars` VALUES (25, 'Role Management', 'admin.roles.index', '<i class=\"fas fa-user-tag\"></i>', 3, 1, '2026-03-02 01:19:20', '2026-03-10 07:11:03', 29);
INSERT INTO `sidebars` VALUES (26, 'Patron Categories', 'admin.patrons.groups.index', '<i class=\"fas fa-users-cog\"></i>', 5, 1, '2026-03-02 01:19:20', '2026-03-10 07:11:03', 13);
INSERT INTO `sidebars` VALUES (27, 'System Logs', 'admin.activity-logs.index', '<i class=\"fas fa-file-alt\"></i>', 10, 1, '2026-03-02 01:19:20', '2026-03-10 07:11:03', 13);
INSERT INTO `sidebars` VALUES (28, 'Users List', 'admin.users.index', '<i class=\"fas fa-list-ul\"></i>', 1, 1, '2026-03-02 06:41:44', '2026-03-10 07:11:04', 29);
INSERT INTO `sidebars` VALUES (29, 'User Management', '#', '<i class=\"fas fa-user-cog\"></i>', 2, 1, '2026-03-02 06:43:08', '2026-03-10 07:11:04', NULL);
INSERT INTO `sidebars` VALUES (30, 'MARC Import', 'admin.marc.import.index', '<i class=\"fas fa-file-excel\"></i>', 4, 1, '2026-03-10 07:42:31', '2026-03-10 07:42:31', NULL);
INSERT INTO `sidebars` VALUES (31, 'MARC Reports', 'admin.marc.reports.index', '<i class=\"fas fa-chart-bar\"></i>', 5, 1, '2026-03-10 07:42:32', '2026-03-10 07:42:32', NULL);

-- ----------------------------
-- Table structure for storage_locations
-- ----------------------------
DROP TABLE IF EXISTS `storage_locations`;
CREATE TABLE `storage_locations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `storage_locations_code_unique`(`code` ASC) USING BTREE,
  INDEX `storage_locations_branch_id_foreign`(`branch_id` ASC) USING BTREE,
  CONSTRAINT `storage_locations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of storage_locations
-- ----------------------------
INSERT INTO `storage_locations` VALUES (1, 1, 'Lầu 3', 'l3', NULL, 1, '2026-03-10 09:41:21', '2026-03-10 09:41:21');

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `suppliers_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of suppliers
-- ----------------------------

-- ----------------------------
-- Table structure for system_settings
-- ----------------------------
DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `system_settings_key_unique`(`key` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_settings
-- ----------------------------
INSERT INTO `system_settings` VALUES (1, 'library_name_vi', 'ĐHVTT', 'library', '2026-01-19 20:45:38', '2026-01-19 20:45:38');
INSERT INTO `system_settings` VALUES (2, 'library_name_en', 'VTTU', 'library', '2026-01-19 20:45:38', '2026-01-19 20:45:38');
INSERT INTO `system_settings` VALUES (3, 'address', 'Thư Viện Đại Học VTTU', 'library', '2026-01-19 20:45:38', '2026-01-19 20:45:38');
INSERT INTO `system_settings` VALUES (4, 'phone', '0365914056', 'library', '2026-01-19 20:45:38', '2026-01-19 20:45:38');
INSERT INTO `system_settings` VALUES (5, 'email', 'vttu@edu.com', 'library', '2026-01-19 20:45:38', '2026-01-19 20:45:38');
INSERT INTO `system_settings` VALUES (6, 'website', 'https://popivn36.onrender.com', 'library', '2026-01-19 20:45:38', '2026-01-19 20:45:38');
INSERT INTO `system_settings` VALUES (7, 'loan_time_unit', 'day', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (8, 'opening_time', '07:30', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (9, 'closing_time', '17:00', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (10, 'fine_notification_time', '17:00', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (11, 'debt_notification_time', '17:00', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (12, 'grace_period', '1', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (13, 'fine_period', '2', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (14, 'default_replacement_cost', '0', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (15, 'default_processing_cost', '0', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (16, 'urgent_fine_rate', '2000', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');
INSERT INTO `system_settings` VALUES (17, 'normal_fine_rate', '1000', 'policy', '2026-02-04 04:06:02', '2026-02-04 04:06:02');

-- ----------------------------
-- Table structure for user_role_sidebars
-- ----------------------------
DROP TABLE IF EXISTS `user_role_sidebars`;
CREATE TABLE `user_role_sidebars`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_user_id` bigint UNSIGNED NOT NULL,
  `sidebar_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_role_sidebars_role_user_id_foreign`(`role_user_id` ASC) USING BTREE,
  INDEX `user_role_sidebars_sidebar_id_foreign`(`sidebar_id` ASC) USING BTREE,
  CONSTRAINT `user_role_sidebars_role_user_id_foreign` FOREIGN KEY (`role_user_id`) REFERENCES `role_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_role_sidebars_sidebar_id_foreign` FOREIGN KEY (`sidebar_id`) REFERENCES `sidebars` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 308 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_role_sidebars
-- ----------------------------
INSERT INTO `user_role_sidebars` VALUES (74, 2, 2, '2026-01-24 07:27:50', '2026-01-24 07:27:50');
INSERT INTO `user_role_sidebars` VALUES (76, 2, 6, '2026-01-24 07:27:50', '2026-01-24 07:27:50');
INSERT INTO `user_role_sidebars` VALUES (77, 2, 4, '2026-01-24 07:27:50', '2026-01-24 07:27:50');
INSERT INTO `user_role_sidebars` VALUES (78, 2, 1, '2026-01-24 07:27:50', '2026-01-24 07:27:50');
INSERT INTO `user_role_sidebars` VALUES (79, 2, 5, '2026-01-24 07:27:50', '2026-01-24 07:27:50');
INSERT INTO `user_role_sidebars` VALUES (80, 2, 7, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (81, 2, 8, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (82, 2, 9, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (83, 2, 13, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (84, 2, 14, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (85, 2, 15, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (86, 2, 16, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (87, 2, 17, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (88, 2, 18, '2026-01-24 07:27:51', '2026-01-24 07:27:51');
INSERT INTO `user_role_sidebars` VALUES (104, 1, 2, '2026-01-24 08:03:11', '2026-01-24 08:03:11');
INSERT INTO `user_role_sidebars` VALUES (106, 1, 6, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (107, 1, 4, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (108, 1, 1, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (109, 1, 5, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (110, 1, 7, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (111, 1, 8, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (112, 1, 9, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (114, 1, 13, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (115, 1, 14, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (116, 1, 15, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (117, 1, 16, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (118, 1, 17, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (119, 1, 18, '2026-01-24 08:03:12', '2026-01-24 08:03:12');
INSERT INTO `user_role_sidebars` VALUES (120, 2, 19, '2026-01-24 09:03:28', '2026-01-24 09:03:28');
INSERT INTO `user_role_sidebars` VALUES (122, 1, 19, '2026-01-24 09:03:28', '2026-01-24 09:03:28');
INSERT INTO `user_role_sidebars` VALUES (123, 2, 20, '2026-01-24 09:39:09', '2026-01-24 09:39:09');
INSERT INTO `user_role_sidebars` VALUES (125, 1, 20, '2026-01-24 09:39:09', '2026-01-24 09:39:09');
INSERT INTO `user_role_sidebars` VALUES (155, 19, 2, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (157, 19, 6, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (158, 19, 4, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (159, 19, 1, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (160, 19, 19, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (161, 19, 20, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (162, 19, 13, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (164, 19, 15, '2026-02-04 02:09:40', '2026-02-04 02:09:40');
INSERT INTO `user_role_sidebars` VALUES (165, 19, 7, '2026-02-04 02:24:49', '2026-02-04 02:24:49');
INSERT INTO `user_role_sidebars` VALUES (166, 19, 8, '2026-02-04 02:24:49', '2026-02-04 02:24:49');
INSERT INTO `user_role_sidebars` VALUES (167, 19, 9, '2026-02-04 02:24:49', '2026-02-04 02:24:49');
INSERT INTO `user_role_sidebars` VALUES (168, 20, 2, '2026-02-04 03:03:54', '2026-02-04 03:03:54');
INSERT INTO `user_role_sidebars` VALUES (170, 20, 15, '2026-02-04 03:03:54', '2026-02-04 03:03:54');
INSERT INTO `user_role_sidebars` VALUES (171, 20, 7, '2026-02-04 03:03:54', '2026-02-04 03:03:54');
INSERT INTO `user_role_sidebars` VALUES (172, 20, 8, '2026-02-04 03:03:54', '2026-02-04 03:03:54');
INSERT INTO `user_role_sidebars` VALUES (173, 20, 9, '2026-02-04 03:03:54', '2026-02-04 03:03:54');
INSERT INTO `user_role_sidebars` VALUES (174, 19, 5, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (175, 19, 14, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (176, 19, 16, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (177, 19, 17, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (178, 19, 18, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (180, 20, 1, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (181, 20, 4, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (182, 20, 5, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (183, 20, 6, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (184, 20, 13, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (185, 20, 14, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (186, 20, 16, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (187, 20, 17, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (188, 20, 18, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (189, 20, 19, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (190, 20, 20, '2026-02-04 03:28:25', '2026-02-04 03:28:25');
INSERT INTO `user_role_sidebars` VALUES (193, 21, 1, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (194, 21, 2, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (196, 21, 4, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (197, 21, 5, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (198, 21, 6, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (199, 21, 7, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (200, 21, 8, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (201, 21, 9, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (202, 21, 13, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (203, 21, 14, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (204, 21, 15, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (205, 21, 16, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (206, 21, 17, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (207, 21, 18, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (208, 21, 19, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (209, 21, 20, '2026-02-04 04:10:24', '2026-02-04 04:10:24');
INSERT INTO `user_role_sidebars` VALUES (229, 23, 1, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (230, 23, 2, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (232, 23, 4, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (233, 23, 5, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (234, 23, 6, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (235, 23, 7, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (236, 23, 8, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (237, 23, 9, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (238, 23, 13, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (239, 23, 14, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (240, 23, 15, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (241, 23, 16, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (242, 23, 17, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (243, 23, 18, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (244, 23, 19, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (245, 23, 20, '2026-02-04 04:26:47', '2026-02-04 04:26:47');
INSERT INTO `user_role_sidebars` VALUES (252, 2, 24, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (253, 2, 25, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (254, 2, 26, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (255, 2, 27, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (256, 23, 24, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (257, 23, 25, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (258, 23, 26, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (259, 23, 27, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (260, 24, 1, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (261, 24, 2, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (262, 24, 4, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (263, 24, 5, '2026-03-02 01:52:46', '2026-03-02 01:52:46');
INSERT INTO `user_role_sidebars` VALUES (264, 24, 6, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (265, 24, 7, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (266, 24, 8, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (267, 24, 9, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (268, 24, 13, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (269, 24, 14, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (270, 24, 15, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (271, 24, 16, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (272, 24, 17, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (273, 24, 18, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (274, 24, 19, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (275, 24, 20, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (276, 24, 24, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (277, 24, 25, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (278, 24, 26, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (279, 24, 27, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (280, 1, 24, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (281, 1, 25, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (282, 1, 26, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (283, 1, 27, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (284, 21, 24, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (285, 21, 25, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (286, 21, 26, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (287, 21, 27, '2026-03-02 01:52:47', '2026-03-02 01:52:47');
INSERT INTO `user_role_sidebars` VALUES (288, 2, 28, '2026-03-02 06:41:45', '2026-03-02 06:41:45');
INSERT INTO `user_role_sidebars` VALUES (289, 1, 28, '2026-03-02 06:41:45', '2026-03-02 06:41:45');
INSERT INTO `user_role_sidebars` VALUES (290, 19, 28, '2026-03-02 06:41:45', '2026-03-02 06:41:45');
INSERT INTO `user_role_sidebars` VALUES (291, 20, 28, '2026-03-02 06:41:45', '2026-03-02 06:41:45');
INSERT INTO `user_role_sidebars` VALUES (292, 21, 28, '2026-03-02 06:41:45', '2026-03-02 06:41:45');
INSERT INTO `user_role_sidebars` VALUES (293, 23, 28, '2026-03-02 06:41:45', '2026-03-02 06:41:45');
INSERT INTO `user_role_sidebars` VALUES (294, 24, 28, '2026-03-02 06:41:45', '2026-03-02 06:41:45');
INSERT INTO `user_role_sidebars` VALUES (295, 2, 29, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (296, 1, 29, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (297, 19, 29, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (298, 19, 24, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (299, 19, 25, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (300, 20, 29, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (301, 20, 24, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (302, 20, 25, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (303, 21, 29, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (304, 23, 29, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (305, 24, 29, '2026-03-02 06:43:09', '2026-03-02 06:43:09');
INSERT INTO `user_role_sidebars` VALUES (306, 1, 30, '2026-03-10 07:42:32', '2026-03-10 07:42:32');
INSERT INTO `user_role_sidebars` VALUES (307, 1, 31, '2026-03-10 07:42:32', '2026-03-10 07:42:32');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `job_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'System Root', 'root', NULL, 'root@root.com', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2Ndp6bWPMLIV1T321fc0HOTOAzvQMZ42iKXITGbO5JPwsiwoJRY42', 'wLer7xFyIXlq3eaL9qO1czHzt0sG2SDc7MkMvpJwapGBp05dS9BMEVqFL2Ek', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (2, 'Secret Agent', NULL, NULL, 'agent@vttlib.com', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$YzPmWMznX5n6a3JskE/i9.6m/19x8b41zbb/VVlBic4VAnOKYRVGq', 'cOVKkgMHsgrAVts9e0QHt35z8sdHPWzXkpP7Ql4jIeKuYxaajFNt9YpiWQ04', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (3, 'Miss Freida McCullough IV', NULL, NULL, 'bednar.tre@example.com', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'GeKVSDrAvK', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (4, 'Dr. Sonia Crooks MD', NULL, NULL, 'murray.drake@example.net', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'HP5LgIYjXJ', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (5, 'Trisha Shields', NULL, NULL, 'jeremy.bode@example.com', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'mldsdBBlkD', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (6, 'Willow Schoen', NULL, NULL, 'mac40@example.net', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'AmxD1PzH4x', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (7, 'Grady Lueilwitz', NULL, NULL, 'everardo98@example.net', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', '7E0SnUJuus', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (8, 'Miss Edna Carroll', NULL, NULL, 'alta22@example.net', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', '26AMsRzgcS', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (9, 'Ms. Christina Raynor', NULL, NULL, 'eladio.crist@example.net', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'AnohFXE5Mj', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (10, 'Amya Auer', NULL, NULL, 'charlene.pfeffer@example.org', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'maN06OgCk8', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (11, 'Cassandra Wolf', NULL, NULL, 'kenyon15@example.org', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'DskXTLsPrT', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (12, 'Ada Larson', NULL, NULL, 'ubradtke@example.com', NULL, NULL, 'active', '2026-01-13 21:21:16', '$2y$12$2yRusJpwnGVdNKnwWyFYuu4RHZ4j6ispOXFMxJhTvA0vlzEZko7J.', 'byN8k8bjkb', '2026-01-13 21:21:16', '2026-01-13 21:21:16', NULL, NULL);
INSERT INTO `users` VALUES (15, 'Po Pi', NULL, NULL, 'popo1@root.com', NULL, NULL, 'active', NULL, '$2y$12$47vUCF/Rqyq6/D/qC2ra9eYByaCe/ye769ArWjRBnBJ0lsayXXZ2O', NULL, '2026-01-19 21:05:18', '2026-01-19 21:05:18', NULL, NULL);
INSERT INTO `users` VALUES (16, 'Tô Trung Hiếu', 'tthieu', NULL, 'trunghieu3832@vttu.edu.vn', NULL, NULL, 'active', NULL, '$2y$12$2Ndp6bWPMLIV1T321fc0HOTOAzvQMZ42iKXITGbO5JPwsiwoJRY42', NULL, '2026-02-03 09:00:25', '2026-02-03 09:26:22', NULL, NULL);

-- ----------------------------
-- Table structure for z3950_servers
-- ----------------------------
DROP TABLE IF EXISTS `z3950_servers`;
CREATE TABLE `z3950_servers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int NOT NULL DEFAULT 210,
  `database_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `charset` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTF-8',
  `record_syntax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USMARC',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `use_ssl` tinyint(1) NOT NULL DEFAULT 0,
  `timeout` int NOT NULL DEFAULT 30,
  `max_records` int NOT NULL DEFAULT 100,
  `order` int NOT NULL DEFAULT 0,
  `last_connected_at` timestamp NULL DEFAULT NULL,
  `last_status` enum('success','failed','unknown') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `last_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of z3950_servers
-- ----------------------------
INSERT INTO `z3950_servers` VALUES (1, 'Library of Congress', 'z3950.loc.gov', 7090, 'VOYAGER', NULL, NULL, 'UTF-8', 'USMARC', 'Thư viện Quốc hội Hoa Kỳ - Nguồn biên mục chuẩn quốc tế', 1, 0, 30, 100, 1, '2026-03-02 07:47:40', 'success', NULL, '2026-01-24 09:39:09', '2026-03-02 07:47:40');
INSERT INTO `z3950_servers` VALUES (2, 'Thư viện Quốc gia Việt Nam', 'z3950.nlv.gov.vn', 210, 'INNOPAC', NULL, NULL, 'UTF-8', 'USMARC', 'Thư viện Quốc gia Việt Nam - Nguồn biên mục tiếng Việt', 1, 0, 30, 100, 2, '2026-03-02 07:48:10', 'failed', 'Connection failed: A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond (10060)', '2026-01-24 09:39:09', '2026-03-02 07:48:10');
INSERT INTO `z3950_servers` VALUES (3, 'OCLC WorldCat', 'zcat.oclc.org', 210, 'OLUCWorldCat', NULL, NULL, 'UTF-8', 'USMARC', 'OCLC WorldCat - Cơ sở dữ liệu thư mục lớn nhất thế giới (cần đăng ký)', 0, 0, 30, 100, 3, '2026-02-04 03:41:42', 'success', NULL, '2026-01-24 09:39:09', '2026-02-04 03:41:42');

SET FOREIGN_KEY_CHECKS = 1;
