-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2026-04-08 03:30:00
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
  `password` int(8) NOT NULL COMMENT '数字8桁'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, '我管理者', 12345678);

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
(12, '7D');

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
(1, '村田よしあき');

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
(1, '求職者支援'),
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
(2, 2, 2, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `reservation_slots`
--

CREATE TABLE `reservation_slots` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `consultant_id` int(11) NOT NULL,
  `carecon_id` int(11) NOT NULL,
  `reserve_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `reservation_slots`
--

INSERT INTO `reservation_slots` (`id`, `date`, `time_id`, `class_id`, `consultant_id`, `carecon_id`, `reserve_status_id`) VALUES
(2, '2026-04-11', 1, 3, 1, 2, 1),
(3, '2026-04-11', 2, 3, 1, 2, 1);

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
  `graduation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `students`
--

INSERT INTO `students` (`id`, `name`, `number`, `class_id`, `course_id`, `status_id`, `password`, `admission_date`, `graduation_date`) VALUES
(2, '村田よしあき', '19', 3, 2, 1, 11111111, '2025-11-05', '2026-04-30');

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
-- テーブルのインデックス `carecons`
--
ALTER TABLE `carecons`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `slot_ibfk` (`slot_id`),
  ADD KEY `student_ibfk` (`student_id`),
  ADD KEY `method_ibfk` (`method_id`);

--
-- テーブルのインデックス `reservation_slots`
--
ALTER TABLE `reservation_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_ibfk` (`time_id`),
  ADD KEY `class_ibfk2` (`class_id`),
  ADD KEY `consultant_ibfk` (`consultant_id`),
  ADD KEY `carecon_ibfk` (`carecon_id`),
  ADD KEY `reserve_status_ibfk` (`reserve_status_id`);

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
  ADD UNIQUE KEY `password` (`password`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `carecons`
--
ALTER TABLE `carecons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルの AUTO_INCREMENT `consultants`
--
ALTER TABLE `consultants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `reservation_slots`
--
ALTER TABLE `reservation_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `reserve_status`
--
ALTER TABLE `reserve_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- テーブルの制約 `reservation_infos`
--
ALTER TABLE `reservation_infos`
  ADD CONSTRAINT `method_ibfk` FOREIGN KEY (`method_id`) REFERENCES `methods` (`id`),
  ADD CONSTRAINT `slot_ibfk` FOREIGN KEY (`slot_id`) REFERENCES `reservation_slots` (`id`),
  ADD CONSTRAINT `student_ibfk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- テーブルの制約 `reservation_slots`
--
ALTER TABLE `reservation_slots`
  ADD CONSTRAINT `carecon_ibfk` FOREIGN KEY (`carecon_id`) REFERENCES `carecons` (`id`),
  ADD CONSTRAINT `class_ibfk2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `consultant_ibfk` FOREIGN KEY (`consultant_id`) REFERENCES `consultants` (`id`),
  ADD CONSTRAINT `reserve_status_ibfk` FOREIGN KEY (`reserve_status_id`) REFERENCES `reserve_status` (`id`),
  ADD CONSTRAINT `time_ibfk` FOREIGN KEY (`time_id`) REFERENCES `times` (`id`);

--
-- テーブルの制約 `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `class_ibfk` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `course_ibfk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `status_ibfk` FOREIGN KEY (`status_id`) REFERENCES `student_status` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
