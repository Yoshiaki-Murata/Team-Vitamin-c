-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2026-04-21 07:30:26
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `vitaminc`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `apply_lists`
--

CREATE TABLE `apply_lists` (
  `id` int(11) NOT NULL,
  `reserve_info_id` int(11) DEFAULT NULL,
  `apply_detail` text NOT NULL,
  `apply_status_id` int(11) NOT NULL,
  `apply_datetime` datetime NOT NULL,
  `res_date` date DEFAULT NULL COMMENT '初期データ',
  `res_time` varchar(20) DEFAULT NULL COMMENT '初期データ',
  `res_line` int(11) DEFAULT NULL COMMENT '初期データ',
  `res_student_name` varchar(100) DEFAULT NULL COMMENT '初期データ',
  `res_class_name` varchar(100) NOT NULL COMMENT '初期データ',
  `res_consultant_name` varchar(100) NOT NULL COMMENT '初期データ',
  `res_method_name` varchar(50) DEFAULT NULL COMMENT '初期データ',
  `carecon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `apply_lists`
--
ALTER TABLE `apply_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apply_status_ibfk` (`apply_status_id`),
  ADD KEY `reserve_info_ibfk` (`reserve_info_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `apply_lists`
--
ALTER TABLE `apply_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `apply_lists`
--
ALTER TABLE `apply_lists`
  ADD CONSTRAINT `apply_status_ibfk` FOREIGN KEY (`apply_status_id`) REFERENCES `apply_status` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reserve_info_ibfk` FOREIGN KEY (`reserve_info_id`) REFERENCES `reservation_infos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
