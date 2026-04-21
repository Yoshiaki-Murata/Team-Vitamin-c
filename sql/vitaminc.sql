-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2026-04-21 07:38:49
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
-- テーブルの構造 `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` int(8) NOT NULL COMMENT '数字8桁',
  `login_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `login_id`) VALUES
(2, '我管理人', 46637269, 'admin');

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

-- --------------------------------------------------------

--
-- テーブルの構造 `apply_status`
--

CREATE TABLE `apply_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `apply_status`
--

INSERT INTO `apply_status` (`id`, `name`) VALUES
(1, '未対応'),
(2, '対応済'),
(3, '対応中');

-- --------------------------------------------------------

--
-- テーブルの構造 `carecons`
--

CREATE TABLE `carecons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `carecons`
--

INSERT INTO `carecons` (`id`, `name`) VALUES
(1, 'キャリコン'),
(2, 'キャリコンプラス');

-- --------------------------------------------------------

--
-- テーブルの構造 `carecon_lines`
--

CREATE TABLE `carecon_lines` (
  `id` int(11) NOT NULL,
  `line` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `carecon_lines`
--

INSERT INTO `carecon_lines` (`id`, `line`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13);

-- --------------------------------------------------------

--
-- テーブルの構造 `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `classes`
--

INSERT INTO `classes` (`id`, `name`) VALUES
(1, '6A'),
(2, '6B'),
(3, '6C'),
(4, '6D'),
(5, '6E'),
(6, '6F'),
(7, '6G'),
(8, '6H'),
(9, '7A'),
(10, '7B'),
(11, '7C'),
(12, 'キャリコンルーム');

-- --------------------------------------------------------

--
-- テーブルの構造 `consultants`
--

CREATE TABLE `consultants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `consultants`
--

INSERT INTO `consultants` (`id`, `name`) VALUES
(1, '村田よしあき'),
(2, 'アンドリュー'),
(4, '中嶋茂雄');

-- --------------------------------------------------------

--
-- テーブルの構造 `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `courses`
--

INSERT INTO `courses` (`id`, `name`) VALUES
(1, '求職者支援訓練'),
(2, '公共職業訓練');

-- --------------------------------------------------------

--
-- テーブルの構造 `methods`
--

CREATE TABLE `methods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `methods`
--

INSERT INTO `methods` (`id`, `name`) VALUES
(1, '対面'),
(2, 'Zoom');

-- --------------------------------------------------------

--
-- テーブルの構造 `reservation_infos`
--

CREATE TABLE `reservation_infos` (
  `id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `reservation_infos`
--

INSERT INTO `reservation_infos` (`id`, `slot_id`, `student_id`, `method_id`) VALUES
(10, 14, 18, 1),
(11, 15, 28, 1),
(12, 16, 26, 1),
(15, 20, 18, 1),
(17, 21, 28, 1),
(18, 22, 21, 2),
(19, 17, 17, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `reservation_slots`
--

CREATE TABLE `reservation_slots` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `consultant_id` int(11) DEFAULT NULL,
  `carecon_id` int(11) NOT NULL,
  `reserve_status_id` int(11) NOT NULL,
  `lines_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `reservation_slots`
--

INSERT INTO `reservation_slots` (`id`, `date`, `time_id`, `class_id`, `consultant_id`, `carecon_id`, `reserve_status_id`, `lines_id`) VALUES
(14, '2026-04-25', 1, NULL, NULL, 1, 2, 1),
(15, '2026-04-25', 2, NULL, NULL, 1, 2, 1),
(16, '2026-04-25', 3, NULL, NULL, 1, 2, 1),
(17, '2026-04-25', 1, 12, 4, 2, 2, 2),
(18, '2026-04-25', 2, NULL, NULL, 2, 1, 2),
(19, '2026-04-25', 3, NULL, NULL, 2, 1, 2),
(20, '2026-05-02', 1, NULL, NULL, 1, 2, 1),
(21, '2026-05-02', 2, NULL, NULL, 1, 2, 1),
(22, '2026-05-02', 3, NULL, NULL, 1, 2, 1),
(23, '2026-05-02', 1, NULL, NULL, 2, 1, 2),
(24, '2026-05-02', 2, NULL, NULL, 2, 1, 2),
(25, '2026-05-02', 3, NULL, NULL, 2, 1, 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `reserve_status`
--

CREATE TABLE `reserve_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `reserve_status`
--

INSERT INTO `reserve_status` (`id`, `name`) VALUES
(1, '空'),
(2, '満');

-- --------------------------------------------------------

--
-- テーブルの構造 `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `class_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `password` int(8) NOT NULL COMMENT '数字8桁',
  `admission_date` date NOT NULL,
  `graduation_date` date NOT NULL,
  `login_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `students`
--

INSERT INTO `students` (`id`, `name`, `number`, `class_id`, `course_id`, `status_id`, `password`, `admission_date`, `graduation_date`, `login_id`) VALUES
(17, '村田よしあき', '19', 3, 2, 1, 99999999, '2025-11-05', '2026-04-30', '2025116c19'),
(18, '江原実里', '02', 3, 2, 1, 29514223, '2025-11-05', '2026-04-30', '2025116c02'),
(21, '生間如人', '01', 1, 1, 1, 28569558, '2025-12-04', '2026-05-31', '2025126a01'),
(26, '神矢茂雄', '06', 2, 2, 1, 36572696, '2026-03-05', '2026-08-31', '2026036b06'),
(27, '林 晴翔', '11', 7, 1, 1, 52872893, '2026-04-06', '2026-09-30', '2026046g11'),
(28, '梅崎竜之介', '01', 3, 2, 1, 43934686, '2025-11-05', '2026-04-30', '2025116c01');

-- --------------------------------------------------------

--
-- テーブルの構造 `student_status`
--

CREATE TABLE `student_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `student_status`
--

INSERT INTO `student_status` (`id`, `name`) VALUES
(1, '在籍'),
(2, '退校');

-- --------------------------------------------------------

--
-- テーブルの構造 `times`
--

CREATE TABLE `times` (
  `id` int(11) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `times`
--

INSERT INTO `times` (`id`, `time`) VALUES
(1, '10:00～'),
(2, '11:00～'),
(3, '12:00～'),
(4, '13:00～'),
(5, '14:00～'),
(6, '15:00～'),
(7, '16:00～');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin-password` (`password`);

--
-- テーブルのインデックス `apply_lists`
--
ALTER TABLE `apply_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apply_status_ibfk` (`apply_status_id`),
  ADD KEY `reserve_info_ibfk` (`reserve_info_id`);

--
-- テーブルのインデックス `apply_status`
--
ALTER TABLE `apply_status`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `carecons`
--
ALTER TABLE `carecons`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `carecon_lines`
--
ALTER TABLE `carecon_lines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `line` (`line`);

--
-- テーブルのインデックス `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `consultants`
--
ALTER TABLE `consultants`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `methods`
--
ALTER TABLE `methods`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `reservation_infos`
--
ALTER TABLE `reservation_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `method_ibfk` (`method_id`),
  ADD KEY `slot_ibfk` (`slot_id`),
  ADD KEY `student_ibfk` (`student_id`);

--
-- テーブルのインデックス `reservation_slots`
--
ALTER TABLE `reservation_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_ibfk` (`time_id`),
  ADD KEY `class_ibfk2` (`class_id`),
  ADD KEY `consultant_ibfk` (`consultant_id`),
  ADD KEY `carecon_ibfk` (`carecon_id`),
  ADD KEY `reserve_status_ibfk` (`reserve_status_id`),
  ADD KEY `line_ibfk` (`lines_id`);

--
-- テーブルのインデックス `reserve_status`
--
ALTER TABLE `reserve_status`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_id` (`login_id`),
  ADD KEY `class_ibfk` (`class_id`),
  ADD KEY `course_ibfk` (`course_id`),
  ADD KEY `status_ibfk` (`status_id`);

--
-- テーブルのインデックス `student_status`
--
ALTER TABLE `student_status`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `times`
--
ALTER TABLE `times`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `apply_lists`
--
ALTER TABLE `apply_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- テーブルの AUTO_INCREMENT `apply_status`
--
ALTER TABLE `apply_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `carecons`
--
ALTER TABLE `carecons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `carecon_lines`
--
ALTER TABLE `carecon_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- テーブルの AUTO_INCREMENT `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルの AUTO_INCREMENT `consultants`
--
ALTER TABLE `consultants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `methods`
--
ALTER TABLE `methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `reservation_infos`
--
ALTER TABLE `reservation_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- テーブルの AUTO_INCREMENT `reservation_slots`
--
ALTER TABLE `reservation_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- テーブルの AUTO_INCREMENT `reserve_status`
--
ALTER TABLE `reserve_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- テーブルの AUTO_INCREMENT `student_status`
--
ALTER TABLE `student_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `times`
--
ALTER TABLE `times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `apply_lists`
--
ALTER TABLE `apply_lists`
  ADD CONSTRAINT `apply_status_ibfk` FOREIGN KEY (`apply_status_id`) REFERENCES `apply_status` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reserve_info_ibfk` FOREIGN KEY (`reserve_info_id`) REFERENCES `reservation_infos` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `reservation_infos`
--
ALTER TABLE `reservation_infos`
  ADD CONSTRAINT `method_ibfk` FOREIGN KEY (`method_id`) REFERENCES `methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `slot_ibfk` FOREIGN KEY (`slot_id`) REFERENCES `reservation_slots` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_ibfk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `reservation_slots`
--
ALTER TABLE `reservation_slots`
  ADD CONSTRAINT `carecon_ibfk` FOREIGN KEY (`carecon_id`) REFERENCES `carecons` (`id`),
  ADD CONSTRAINT `class_ibfk2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `consultant_ibfk` FOREIGN KEY (`consultant_id`) REFERENCES `consultants` (`id`),
  ADD CONSTRAINT `line_ibfk` FOREIGN KEY (`lines_id`) REFERENCES `carecon_lines` (`id`),
  ADD CONSTRAINT `reserve_status_ibfk` FOREIGN KEY (`reserve_status_id`) REFERENCES `reserve_status` (`id`),
  ADD CONSTRAINT `time_ibfk` FOREIGN KEY (`time_id`) REFERENCES `times` (`id`);

--
-- テーブルの制約 `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `class_ibfk` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `course_ibfk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `status_ibfk` FOREIGN KEY (`status_id`) REFERENCES `student_status` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
