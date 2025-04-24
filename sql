-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th4 24, 2025 lúc 11:25 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `he_thong_quan_ly_mon_hoc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_kiem_tra_chu_de`
--

DROP TABLE IF EXISTS `bai_kiem_tra_chu_de`;
CREATE TABLE IF NOT EXISTS `bai_kiem_tra_chu_de` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bai_kiem_tra_id` int NOT NULL,
  `chu_de_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cd` (`chu_de_id`),
  KEY `fk_bkt` (`bai_kiem_tra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_kiem_tra_chu_de`
--

INSERT INTO `bai_kiem_tra_chu_de` (`id`, `bai_kiem_tra_id`, `chu_de_id`) VALUES
(135, 76, 6),
(136, 76, 7),
(149, 88, 18),
(151, 89, 18),
(166, 94, 21),
(167, 94, 22),
(168, 93, 21),
(169, 93, 22);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_kiem_tra_trac_nghiem`
--

DROP TABLE IF EXISTS `bai_kiem_tra_trac_nghiem`;
CREATE TABLE IF NOT EXISTS `bai_kiem_tra_trac_nghiem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lop_hoc_id` int NOT NULL,
  `mon_hoc_id` int NOT NULL,
  `tieu_de` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_general_ci,
  `thoi_gian_lam` int DEFAULT NULL,
  `thoi_gian_bat_dau` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_ket_thuc` timestamp NULL DEFAULT NULL,
  `tron_cau_hoi` tinyint(1) DEFAULT '0',
  `tron_dap_an` tinyint(1) DEFAULT '0',
  `hien_thi_dap_an` tinyint(1) DEFAULT '0',
  `so_lan_lam` int DEFAULT '1',
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lop_hoc_id` (`lop_hoc_id`),
  KEY `mon_hoc_id` (`mon_hoc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_kiem_tra_trac_nghiem`
--

INSERT INTO `bai_kiem_tra_trac_nghiem` (`id`, `lop_hoc_id`, `mon_hoc_id`, `tieu_de`, `mo_ta`, `thoi_gian_lam`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `tron_cau_hoi`, `tron_dap_an`, `hien_thi_dap_an`, `so_lan_lam`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(76, 1, 5, 'toán123', '123', 30, '2025-04-21 16:18:57', NULL, 0, 0, 0, 1, '2025-04-21 16:18:57', '2025-04-21 16:18:57'),
(88, 7, 17, 'toán', '', 60, '2025-04-23 04:49:44', NULL, 0, 0, 0, 1, '2025-04-23 04:49:44', '2025-04-23 04:49:44'),
(89, 7, 17, 'toán', '', 60, '2025-04-23 04:55:59', NULL, 0, 0, 0, 1, '2025-04-23 04:50:00', '2025-04-23 04:55:59'),
(93, 1, 19, 'kiểm giữa kì', '', 30, '2025-04-23 07:23:54', NULL, 0, 1, 0, 1, '2025-04-23 06:30:42', '2025-04-23 07:23:54'),
(94, 1, 19, 'kiểm giữa kì1', '', 60, '2025-04-23 07:23:43', NULL, 1, 0, 0, 1, '2025-04-23 06:39:49', '2025-04-23 07:23:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_kiem_tra_tu_luan`
--

DROP TABLE IF EXISTS `bai_kiem_tra_tu_luan`;
CREATE TABLE IF NOT EXISTS `bai_kiem_tra_tu_luan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lop_hoc_id` int NOT NULL,
  `mon_hoc_id` int NOT NULL,
  `tieu_de` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_general_ci,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `thoi_gian_lam` int DEFAULT NULL,
  `thoi_gian_bat_dau` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_ket_thuc` timestamp NULL DEFAULT NULL,
  `so_lan_lam` int DEFAULT '1',
  `dinh_kem` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lop_hoc_id` (`lop_hoc_id`),
  KEY `fk_tlmh` (`mon_hoc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_kiem_tra_tu_luan`
--

INSERT INTO `bai_kiem_tra_tu_luan` (`id`, `lop_hoc_id`, `mon_hoc_id`, `tieu_de`, `mo_ta`, `noi_dung`, `thoi_gian_lam`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `so_lan_lam`, `dinh_kem`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(8, 1, 12, 'toán', 'ok', '<p>AI là gì</p>', 60, '2025-04-21 16:21:46', NULL, 1, NULL, '2025-04-21 16:21:46', '2025-04-21 16:21:46'),
(9, 1, 5, 'toán1', 'ok1', '<p>hya&nbsp;</p>', NULL, '2025-04-21 16:24:00', '2025-04-22 16:24:00', 1, NULL, '2025-04-21 16:24:32', '2025-04-21 16:24:32'),
(10, 1, 5, 'toán2', '123', '<p>2131</p>', 30, '2025-04-21 16:25:30', NULL, 1, NULL, '2025-04-21 16:25:30', '2025-04-21 16:25:30'),
(11, 1, 12, 'â', 'â', '<p>a</p>', NULL, '2025-04-21 16:26:00', '2025-04-24 16:26:00', 1, NULL, '2025-04-21 16:26:46', '2025-04-21 16:26:46'),
(12, 1, 5, 'toán', '11', '<p>1</p>', NULL, '2025-04-21 16:27:00', '2025-04-23 16:27:00', 1, NULL, '2025-04-21 16:27:25', '2025-04-21 16:27:25'),
(17, 7, 17, 'toán', 'adasd', '<p>s</p>', NULL, '2025-04-23 04:56:00', '0000-00-00 00:00:00', 1, NULL, '2025-04-23 04:56:13', '2025-04-23 05:02:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_lam_trac_nghiem`
--

DROP TABLE IF EXISTS `bai_lam_trac_nghiem`;
CREATE TABLE IF NOT EXISTS `bai_lam_trac_nghiem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bai_kiem_tra_id` int NOT NULL,
  `thoi_gian_bat_dau` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_nop` timestamp NULL DEFAULT NULL,
  `diem` float DEFAULT NULL,
  `lan_thu` int DEFAULT '1',
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bai_kiem_tra_id` (`bai_kiem_tra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_lam_trac_nghiem`
--

INSERT INTO `bai_lam_trac_nghiem` (`id`, `bai_kiem_tra_id`, `thoi_gian_bat_dau`, `thoi_gian_nop`, `diem`, `lan_thu`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(46, 76, '2025-04-21 16:37:17', '2025-04-21 16:37:17', 0, 1, '2025-04-21 16:37:17', '2025-04-21 16:37:17'),
(47, 93, '2025-04-23 07:43:13', '2025-04-23 07:43:13', 2.5, 1, '2025-04-23 07:43:13', '2025-04-23 07:43:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_lam_tu_luan`
--

DROP TABLE IF EXISTS `bai_lam_tu_luan`;
CREATE TABLE IF NOT EXISTS `bai_lam_tu_luan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sinh_vien_id` int NOT NULL,
  `bai_kiem_tra_id` int NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci,
  `tep_tin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ngay_nop` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diem` float DEFAULT NULL,
  `nhan_xet` text COLLATE utf8mb4_general_ci,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sinh_vien_id` (`sinh_vien_id`),
  KEY `bai_kiem_tra_id` (`bai_kiem_tra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_lam_tu_luan`
--

INSERT INTO `bai_lam_tu_luan` (`id`, `sinh_vien_id`, `bai_kiem_tra_id`, `noi_dung`, `tep_tin`, `ngay_nop`, `diem`, `nhan_xet`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(3, 1, 8, '123', NULL, '2025-04-21 16:59:21', 5, 'bh', '2025-04-21 16:59:21', '2025-04-21 18:34:45'),
(4, 1, 9, 'ok1', NULL, '2025-04-21 16:59:50', NULL, NULL, '2025-04-21 16:59:50', '2025-04-21 16:59:50'),
(5, 2, 8, 'da', NULL, '2025-04-21 18:40:11', NULL, NULL, '2025-04-21 18:40:11', '2025-04-21 18:40:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cac_dap_an`
--

DROP TABLE IF EXISTS `cac_dap_an`;
CREATE TABLE IF NOT EXISTS `cac_dap_an` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cau_hoi_id` int NOT NULL,
  `dap_an_cua_trac_nghiem` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dung_hay_sai` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cau_hoi_id` (`cau_hoi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cac_dap_an`
--

INSERT INTO `cac_dap_an` (`id`, `cau_hoi_id`, `dap_an_cua_trac_nghiem`, `dung_hay_sai`) VALUES
(121, 37, '2', 1),
(122, 37, '3', 0),
(123, 37, '4', 0),
(126, 36, 'aaaaaa1', 1),
(127, 36, 'aa', 0),
(128, 38, 'vv1', 1),
(129, 38, 'vv', 0),
(132, 40, 'zzzz', 1),
(133, 40, 'zz', 0),
(134, 39, 'ooo', 1),
(135, 39, 'ô', 1),
(164, 54, 'aa', 1),
(165, 54, 'bb', 0),
(166, 55, 'aa', 1),
(167, 55, 'bb', 0),
(168, 56, '1', 0),
(169, 56, '3', 1),
(170, 56, 'ba', 1),
(171, 56, '4', 0),
(172, 57, '1', 0),
(173, 57, '2', 1),
(174, 57, '4', 0),
(175, 57, '5', 0),
(176, 58, '2', 0),
(177, 58, '3', 0),
(178, 59, '4', 1),
(179, 59, '6', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hoi_bai_kiem_tra_trac_nghiem`
--

DROP TABLE IF EXISTS `cau_hoi_bai_kiem_tra_trac_nghiem`;
CREATE TABLE IF NOT EXISTS `cau_hoi_bai_kiem_tra_trac_nghiem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cau_hoi_id` int NOT NULL,
  `bai_trac_nghiem_id` int NOT NULL,
  `chu_de_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cauhoi` (`cau_hoi_id`),
  KEY `fk_baikt` (`bai_trac_nghiem_id`),
  KEY `idx_chu_de_id` (`chu_de_id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cau_hoi_bai_kiem_tra_trac_nghiem`
--

INSERT INTO `cau_hoi_bai_kiem_tra_trac_nghiem` (`id`, `cau_hoi_id`, `bai_trac_nghiem_id`, `chu_de_id`) VALUES
(155, 37, 76, 6),
(156, 38, 76, 7),
(157, 39, 76, 7),
(171, 54, 88, 18),
(172, 55, 88, 18),
(174, 55, 89, 18),
(175, 54, 89, 18),
(198, 56, 94, 21),
(199, 58, 94, 22),
(200, 59, 94, 22),
(201, 57, 93, 21),
(202, 56, 93, 21),
(203, 58, 93, 22),
(204, 59, 93, 22);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hoi_chu_de`
--

DROP TABLE IF EXISTS `cau_hoi_chu_de`;
CREATE TABLE IF NOT EXISTS `cau_hoi_chu_de` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cau_hoi_id` int NOT NULL,
  `chu_de_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_chcd` (`chu_de_id`),
  KEY `fk_chtn` (`cau_hoi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cau_hoi_chu_de`
--

INSERT INTO `cau_hoi_chu_de` (`id`, `cau_hoi_id`, `chu_de_id`) VALUES
(18, 36, 6),
(19, 37, 6),
(20, 38, 7),
(21, 39, 7),
(22, 40, 6),
(36, 54, 18),
(37, 55, 18),
(38, 56, 21),
(39, 57, 21),
(40, 58, 22),
(41, 59, 22);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hoi_giao_vien`
--

DROP TABLE IF EXISTS `cau_hoi_giao_vien`;
CREATE TABLE IF NOT EXISTS `cau_hoi_giao_vien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sinh_vien_id` int NOT NULL,
  `giao_vien_id` int NOT NULL,
  `lop_hoc_id` int NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `trang_thai` enum('chua_tra_loi','da_tra_loi') COLLATE utf8mb4_general_ci DEFAULT 'chua_tra_loi',
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sinh_vien_id` (`sinh_vien_id`),
  KEY `giao_vien_id` (`giao_vien_id`),
  KEY `lop_hoc_id` (`lop_hoc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hoi_trac_nghiem`
--

DROP TABLE IF EXISTS `cau_hoi_trac_nghiem`;
CREATE TABLE IF NOT EXISTS `cau_hoi_trac_nghiem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `giao_vien_id` int NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `tieu_de` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_giao_vien_id` (`giao_vien_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cau_hoi_trac_nghiem`
--

INSERT INTO `cau_hoi_trac_nghiem` (`id`, `giao_vien_id`, `noi_dung`, `tieu_de`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(36, 1, 'aaaa', 'toán', '2025-04-13 03:25:18', '2025-04-13 05:29:15'),
(37, 1, 'x+3=5? x bằng bao nhiêu', 'toán', '2025-04-13 03:25:30', '2025-04-13 03:25:30'),
(38, 1, 'vvvv', 'vvvv', '2025-04-13 03:25:41', '2025-04-13 05:29:38'),
(39, 1, 'ooo', 'oooo', '2025-04-13 08:01:14', '2025-04-20 07:08:51'),
(40, 1, 'zzzzzzzzzzzzzzzzzzzzzzzzz', 'toán34', '2025-04-13 09:41:49', '2025-04-13 09:41:49'),
(54, 2, 'xyz', 'abc', '2025-04-23 03:35:37', '2025-04-23 03:35:37'),
(55, 2, 'aaaa', 'toán', '2025-04-23 04:42:36', '2025-04-23 04:42:36'),
(56, 1, '1+2=?', 'chuong1', '2025-04-23 06:10:52', '2025-04-23 06:10:52'),
(57, 1, '1+1=?', 'chuong1', '2025-04-23 06:11:35', '2025-04-23 06:11:35'),
(58, 1, 'x+5=5? x bằng bao nhiêu', 'chuong2', '2025-04-23 06:12:31', '2025-04-23 06:12:31'),
(59, 1, '2+2=?', 'chuong2', '2025-04-23 06:12:31', '2025-04-23 06:12:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_tra_loi`
--

DROP TABLE IF EXISTS `cau_tra_loi`;
CREATE TABLE IF NOT EXISTS `cau_tra_loi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bai_lam_id` int NOT NULL,
  `cau_hoi_id` int NOT NULL,
  `dap_an_chon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bai_lam_id` (`bai_lam_id`),
  KEY `cau_hoi_id` (`cau_hoi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cau_tra_loi`
--

INSERT INTO `cau_tra_loi` (`id`, `bai_lam_id`, `cau_hoi_id`, `dap_an_chon`) VALUES
(118, 46, 37, 'B'),
(119, 46, 39, 'A'),
(120, 47, 58, 'A'),
(121, 47, 59, 'A');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chu_de`
--

DROP TABLE IF EXISTS `chu_de`;
CREATE TABLE IF NOT EXISTS `chu_de` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mon_hoc_id` int NOT NULL,
  `ten_chu_de` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-cdmh` (`mon_hoc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chu_de`
--

INSERT INTO `chu_de` (`id`, `mon_hoc_id`, `ten_chu_de`) VALUES
(6, 5, 'ki thuat'),
(7, 5, 'vvvv'),
(18, 17, 'toán vĩ mô'),
(21, 19, 'chuong1'),
(22, 19, 'chuong2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giao_vien`
--

DROP TABLE IF EXISTS `giao_vien`;
CREATE TABLE IF NOT EXISTS `giao_vien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nguoi_dung_id` int NOT NULL,
  `hoc_vi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `chuyen_nganh` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mo_ta` text COLLATE utf8mb4_general_ci,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giao_vien`
--

INSERT INTO `giao_vien` (`id`, `nguoi_dung_id`, `hoc_vi`, `chuyen_nganh`, `mo_ta`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 1, 'Thạc Sĩ', 'Toán Học', 'giáo viên ưu tú', '2025-03-31 13:33:16', '2025-03-31 13:33:16'),
(2, 2, 'Tiến Sĩ', 'Công Nghệ Thông Tin', 'giáo viên ưu tú', '2025-04-01 08:33:57', '2025-04-01 08:33:57'),
(8, 13, 'Tiến Sĩ', 'CNTT', '', '2025-04-19 07:44:51', '2025-04-19 07:44:51'),
(9, 14, 'Thạc Sĩ', 'CNTT', 'Giáo viên tốt', '2025-04-19 11:46:17', '2025-04-19 11:46:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop_hoc`
--

DROP TABLE IF EXISTS `lop_hoc`;
CREATE TABLE IF NOT EXISTS `lop_hoc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ma_lop` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ten_lop` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `giao_vien_id` int NOT NULL,
  `mo_ta` text COLLATE utf8mb4_general_ci,
  `ngay_bat_dau` date DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ma_lop` (`ma_lop`),
  KEY `giao_vien_id` (`giao_vien_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lop_hoc`
--

INSERT INTO `lop_hoc` (`id`, `ma_lop`, `ten_lop`, `giao_vien_id`, `mo_ta`, `ngay_bat_dau`, `ngay_ket_thuc`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'TH06', 'D21_TH06', 1, NULL, '2025-04-13', '2025-04-13', '2025-04-13 03:22:16', '2025-04-13 03:23:31'),
(3, 'qqq', 'qq', 9, 'qq', '2025-04-08', '2025-04-01', '2025-04-20 05:03:09', '2025-04-20 05:03:09'),
(7, 'd21-1', 'cntt 21', 2, '', '0000-00-00', '0000-00-00', '2025-04-22 15:13:28', '2025-04-22 15:13:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop_hoc_mon_hoc`
--

DROP TABLE IF EXISTS `lop_hoc_mon_hoc`;
CREATE TABLE IF NOT EXISTS `lop_hoc_mon_hoc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lop_hoc_id` int NOT NULL,
  `mon_hoc_id` int NOT NULL,
  `giao_vien_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lop_hoc_id` (`lop_hoc_id`),
  KEY `mon_hoc_id` (`mon_hoc_id`),
  KEY `giao_vien_id` (`giao_vien_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lop_hoc_mon_hoc`
--

INSERT INTO `lop_hoc_mon_hoc` (`id`, `lop_hoc_id`, `mon_hoc_id`, `giao_vien_id`) VALUES
(1, 1, 5, 1),
(7, 1, 12, 1),
(14, 7, 17, 2),
(15, 1, 19, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mon_hoc`
--

DROP TABLE IF EXISTS `mon_hoc`;
CREATE TABLE IF NOT EXISTS `mon_hoc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `giao_vien_id` int NOT NULL,
  `ma_mon` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ten_mon` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_general_ci,
  `hoc_ky` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nam_hoc` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `so_tin_chi` int DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_giaovien_mamon` (`giao_vien_id`,`ma_mon`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `mon_hoc`
--

INSERT INTO `mon_hoc` (`id`, `giao_vien_id`, `ma_mon`, `ten_mon`, `mo_ta`, `hoc_ky`, `nam_hoc`, `so_tin_chi`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(5, 1, 'nmlt', 'nhap mon lap trinh', '................', '2', '2022-2024', 3, '2025-04-11 07:49:05', '2025-04-11 07:49:05'),
(12, 1, 'ktlt', 'ki thuat lap trinh', '', '2', '2022-2024', 3, '2025-04-13 03:45:20', '2025-04-13 03:45:20'),
(15, 1, 'adad', 'dfd', 'df', '2', '2022-2024', 3, '2025-04-19 05:42:28', '2025-04-19 05:42:28'),
(17, 2, 'ab', 'aabb', '', '2', '2025-2026', 3, '2025-04-22 10:47:17', '2025-04-22 10:47:17'),
(19, 1, 'mm', 'monmoi', '', '2', '2025-2026', 3, '2025-04-23 06:07:25', '2025-04-23 06:07:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ma_so` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `ho_va_ten` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mat_khau` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `vai_tro` enum('sinh_vien','giao_vien','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trang_thai` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `ma_so` (`ma_so`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ma_so`, `ho_va_ten`, `email`, `mat_khau`, `vai_tro`, `ngay_tao`, `ngay_cap_nhat`, `trang_thai`) VALUES
(1, 'DH5200', 'Nguyễn Văn A', 'a.nguyenvan@student.stu.edu.vn', '123456', 'giao_vien', '2025-03-31 11:39:10', '2025-04-19 11:35:33', 1),
(2, 'DH5201', 'Đặng Thị B', 'DH5201@stu.edu.vn', '654321', 'giao_vien', '2025-04-01 08:33:02', '2025-04-01 08:33:02', 1),
(3, 'DH52110816', 'Dang Tho a', 'dh52110816@student.stu.edu.vn', '123456', 'sinh_vien', '2025-04-14 08:34:43', '2025-04-20 04:35:08', 1),
(4, 'DH52110817', 'Nguyen thi B', 'dh52110817@student.edu.vn', 'DH52110817', 'sinh_vien', '2025-04-14 08:37:06', '2025-04-14 08:37:06', 1),
(6, 'DH52110818', 'Duong Van Q', 'dh52110818@student.stu.edu.vn', 'DH52110816', 'sinh_vien', '2025-04-14 08:38:31', '2025-04-14 08:38:31', 1),
(7, 'admin', 'admin', '', 'admin', 'admin', '2025-04-19 05:26:17', '2025-04-19 05:26:17', 1),
(13, 'gv1', 'Dang Minh A', 'gv1@stu.vn', '$2y$10$1s0bkL.ETU2Vy0TxGmwzrOaahZ4u4tsSK3YwS/xOUoHVvlCx7QwIG', 'giao_vien', '2025-04-19 07:44:51', '2025-04-19 11:35:05', 1),
(14, 'gv2', 'Dang Minh B', 'gv2@stu.vn', '123', 'giao_vien', '2025-04-19 11:46:17', '2025-04-20 05:02:06', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phan_hoi_giao_vien`
--

DROP TABLE IF EXISTS `phan_hoi_giao_vien`;
CREATE TABLE IF NOT EXISTS `phan_hoi_giao_vien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cau_hoi_id` int NOT NULL,
  `giao_vien_id` int NOT NULL,
  `noi_dung` text COLLATE utf8mb4_general_ci NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cau_hoi_id` (`cau_hoi_id`),
  KEY `giao_vien_id` (`giao_vien_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phien_lam_bai_trac_nghiem`
--

DROP TABLE IF EXISTS `phien_lam_bai_trac_nghiem`;
CREATE TABLE IF NOT EXISTS `phien_lam_bai_trac_nghiem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sinh_vien_id` int NOT NULL,
  `bai_kiem_tra_id` int NOT NULL,
  `thoi_gian_bat_dau` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_con_lai` int DEFAULT NULL,
  `cau_tra_loi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `trang_thai` enum('dang_lam','da_nop','tam_ngung') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'dang_lam',
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_svtn_sv` (`sinh_vien_id`),
  KEY `fk_svtn_bt` (`bai_kiem_tra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phien_lam_bai_trac_nghiem`
--

INSERT INTO `phien_lam_bai_trac_nghiem` (`id`, `sinh_vien_id`, `bai_kiem_tra_id`, `thoi_gian_bat_dau`, `thoi_gian_con_lai`, `cau_tra_loi`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(55, 1, 76, '2025-04-21 16:37:23', 1800, NULL, 'dang_lam', '2025-04-21 16:37:23', '2025-04-21 16:37:23'),
(60, 1, 93, '2025-04-23 07:44:17', 1607, '{\"58\":\"A\",\"59\":\"A\"}', 'dang_lam', '2025-04-23 07:44:17', '2025-04-23 07:58:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phien_lam_bai_tu_luan`
--

DROP TABLE IF EXISTS `phien_lam_bai_tu_luan`;
CREATE TABLE IF NOT EXISTS `phien_lam_bai_tu_luan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sinh_vien_id` int NOT NULL,
  `bai_kiem_tra_id` int NOT NULL,
  `thoi_gian_bat_dau` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thoi_gian_con_lai` int DEFAULT NULL,
  `noi_dung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tep_tin` text COLLATE utf8mb4_general_ci,
  `trang_thai` enum('dang_lam','da_nop','tam_ngung') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'dang_lam',
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_svtl_sv` (`sinh_vien_id`),
  KEY `fk_svtl_bt` (`bai_kiem_tra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phien_lam_bai_tu_luan`
--

INSERT INTO `phien_lam_bai_tu_luan` (`id`, `sinh_vien_id`, `bai_kiem_tra_id`, `thoi_gian_bat_dau`, `thoi_gian_con_lai`, `noi_dung`, `tep_tin`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(2, 1, 12, '2025-04-21 16:29:27', 0, '', NULL, 'dang_lam', '2025-04-21 16:29:27', '2025-04-21 16:32:11'),
(3, 1, 10, '2025-04-21 16:29:35', 1538, '1234', NULL, 'dang_lam', '2025-04-21 16:29:35', '2025-04-21 17:16:20'),
(5, 1, 11, '2025-04-21 16:51:44', 0, '123', NULL, 'dang_lam', '2025-04-21 16:51:44', '2025-04-21 17:08:18'),
(6, 1, 8, '2025-04-21 16:59:21', 3592, '123', NULL, 'dang_lam', '2025-04-21 16:59:21', '2025-04-21 16:59:21'),
(8, 1, 9, '2025-04-21 16:59:50', 0, 'ok1', NULL, 'dang_lam', '2025-04-21 16:59:50', '2025-04-21 16:59:50'),
(10, 2, 8, '2025-04-21 18:40:11', 3593, 'da', NULL, 'dang_lam', '2025-04-21 18:40:11', '2025-04-21 18:40:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sinh_vien`
--

DROP TABLE IF EXISTS `sinh_vien`;
CREATE TABLE IF NOT EXISTS `sinh_vien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nguoi_dung_id` int NOT NULL,
  `lop_hoc_id` int NOT NULL,
  `nam_nhap_hoc` int DEFAULT NULL,
  `nganh_hoc` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`),
  KEY `fk_sv_lh` (`lop_hoc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sinh_vien`
--

INSERT INTO `sinh_vien` (`id`, `nguoi_dung_id`, `lop_hoc_id`, `nam_nhap_hoc`, `nganh_hoc`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 3, 1, 2021, 'CNTT', '2025-04-14 08:39:33', '2025-04-14 08:39:33'),
(2, 4, 1, 2021, 'CNTT', '2025-04-14 08:39:33', '2025-04-14 08:39:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sinh_vien_bai_lam_trac_nghiem`
--

DROP TABLE IF EXISTS `sinh_vien_bai_lam_trac_nghiem`;
CREATE TABLE IF NOT EXISTS `sinh_vien_bai_lam_trac_nghiem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sinh_vien_id` int NOT NULL,
  `bai_trac_nghiem` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bai_lam` (`bai_trac_nghiem`),
  KEY `fk_sinhvien` (`sinh_vien_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sinh_vien_bai_lam_trac_nghiem`
--

INSERT INTO `sinh_vien_bai_lam_trac_nghiem` (`id`, `sinh_vien_id`, `bai_trac_nghiem`) VALUES
(45, 1, 46),
(46, 1, 47);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bai_kiem_tra_chu_de`
--
ALTER TABLE `bai_kiem_tra_chu_de`
  ADD CONSTRAINT `fk_bkt` FOREIGN KEY (`bai_kiem_tra_id`) REFERENCES `bai_kiem_tra_trac_nghiem` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_cd` FOREIGN KEY (`chu_de_id`) REFERENCES `chu_de` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `bai_kiem_tra_trac_nghiem`
--
ALTER TABLE `bai_kiem_tra_trac_nghiem`
  ADD CONSTRAINT `bai_kiem_tra_trac_nghiem_ibfk_2` FOREIGN KEY (`lop_hoc_id`) REFERENCES `lop_hoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bkt_mh` FOREIGN KEY (`mon_hoc_id`) REFERENCES `mon_hoc` (`id`);

--
-- Các ràng buộc cho bảng `bai_kiem_tra_tu_luan`
--
ALTER TABLE `bai_kiem_tra_tu_luan`
  ADD CONSTRAINT `bai_kiem_tra_tu_luan_ibfk_2` FOREIGN KEY (`lop_hoc_id`) REFERENCES `lop_hoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tlmh` FOREIGN KEY (`mon_hoc_id`) REFERENCES `mon_hoc` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `bai_lam_trac_nghiem`
--
ALTER TABLE `bai_lam_trac_nghiem`
  ADD CONSTRAINT `bai_lam_trac_nghiem_ibfk_2` FOREIGN KEY (`bai_kiem_tra_id`) REFERENCES `bai_kiem_tra_trac_nghiem` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `bai_lam_tu_luan`
--
ALTER TABLE `bai_lam_tu_luan`
  ADD CONSTRAINT `bai_lam_tu_luan_ibfk_1` FOREIGN KEY (`sinh_vien_id`) REFERENCES `sinh_vien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bai_lam_tu_luan_ibfk_2` FOREIGN KEY (`bai_kiem_tra_id`) REFERENCES `bai_kiem_tra_tu_luan` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cac_dap_an`
--
ALTER TABLE `cac_dap_an`
  ADD CONSTRAINT `cac_dap_an_ibfk_1` FOREIGN KEY (`cau_hoi_id`) REFERENCES `cau_hoi_trac_nghiem` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cau_hoi_bai_kiem_tra_trac_nghiem`
--
ALTER TABLE `cau_hoi_bai_kiem_tra_trac_nghiem`
  ADD CONSTRAINT `fk_baikt` FOREIGN KEY (`bai_trac_nghiem_id`) REFERENCES `bai_kiem_tra_trac_nghiem` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_cauhoi` FOREIGN KEY (`cau_hoi_id`) REFERENCES `cau_hoi_trac_nghiem` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_chu_de` FOREIGN KEY (`chu_de_id`) REFERENCES `chu_de` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `cau_hoi_chu_de`
--
ALTER TABLE `cau_hoi_chu_de`
  ADD CONSTRAINT `fk_chcd` FOREIGN KEY (`chu_de_id`) REFERENCES `chu_de` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_chtn` FOREIGN KEY (`cau_hoi_id`) REFERENCES `cau_hoi_trac_nghiem` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `cau_hoi_giao_vien`
--
ALTER TABLE `cau_hoi_giao_vien`
  ADD CONSTRAINT `cau_hoi_giao_vien_ibfk_1` FOREIGN KEY (`sinh_vien_id`) REFERENCES `sinh_vien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cau_hoi_giao_vien_ibfk_2` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_vien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cau_hoi_giao_vien_ibfk_3` FOREIGN KEY (`lop_hoc_id`) REFERENCES `lop_hoc` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cau_hoi_trac_nghiem`
--
ALTER TABLE `cau_hoi_trac_nghiem`
  ADD CONSTRAINT `fk_giao_vien_id` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_vien` (`id`) ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `cau_tra_loi`
--
ALTER TABLE `cau_tra_loi`
  ADD CONSTRAINT `cau_tra_loi_ibfk_1` FOREIGN KEY (`bai_lam_id`) REFERENCES `bai_lam_trac_nghiem` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cau_tra_loi_ibfk_2` FOREIGN KEY (`cau_hoi_id`) REFERENCES `cau_hoi_trac_nghiem` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chu_de`
--
ALTER TABLE `chu_de`
  ADD CONSTRAINT `fk-cdmh` FOREIGN KEY (`mon_hoc_id`) REFERENCES `mon_hoc` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `giao_vien`
--
ALTER TABLE `giao_vien`
  ADD CONSTRAINT `giao_vien_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lop_hoc`
--
ALTER TABLE `lop_hoc`
  ADD CONSTRAINT `lop_hoc_ibfk_2` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_vien` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lop_hoc_mon_hoc`
--
ALTER TABLE `lop_hoc_mon_hoc`
  ADD CONSTRAINT `fk_lhmh_gv` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_vien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lhmh_lh` FOREIGN KEY (`lop_hoc_id`) REFERENCES `lop_hoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lhmh_mh` FOREIGN KEY (`mon_hoc_id`) REFERENCES `mon_hoc` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `mon_hoc`
--
ALTER TABLE `mon_hoc`
  ADD CONSTRAINT `fk_monhoc_giaovien` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_vien` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `phan_hoi_giao_vien`
--
ALTER TABLE `phan_hoi_giao_vien`
  ADD CONSTRAINT `phan_hoi_giao_vien_ibfk_1` FOREIGN KEY (`cau_hoi_id`) REFERENCES `cau_hoi_giao_vien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phan_hoi_giao_vien_ibfk_2` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_vien` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phien_lam_bai_trac_nghiem`
--
ALTER TABLE `phien_lam_bai_trac_nghiem`
  ADD CONSTRAINT `fk_svtn_bt` FOREIGN KEY (`bai_kiem_tra_id`) REFERENCES `bai_kiem_tra_trac_nghiem` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_svtn_sv` FOREIGN KEY (`sinh_vien_id`) REFERENCES `sinh_vien` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phien_lam_bai_tu_luan`
--
ALTER TABLE `phien_lam_bai_tu_luan`
  ADD CONSTRAINT `fk_svtl_bt` FOREIGN KEY (`bai_kiem_tra_id`) REFERENCES `bai_kiem_tra_tu_luan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_svtl_sv` FOREIGN KEY (`sinh_vien_id`) REFERENCES `sinh_vien` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sinh_vien`
--
ALTER TABLE `sinh_vien`
  ADD CONSTRAINT `fk_sv_lh` FOREIGN KEY (`lop_hoc_id`) REFERENCES `lop_hoc` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `sinh_vien_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `sinh_vien_bai_lam_trac_nghiem`
--
ALTER TABLE `sinh_vien_bai_lam_trac_nghiem`
  ADD CONSTRAINT `fk_bai_lam` FOREIGN KEY (`bai_trac_nghiem`) REFERENCES `bai_lam_trac_nghiem` (`id`) ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_sinhvien` FOREIGN KEY (`sinh_vien_id`) REFERENCES `sinh_vien` (`id`) ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
