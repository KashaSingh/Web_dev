-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 30, 2021 at 07:08 AM
-- Server version: 5.7.23-23
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agadg79w_acadgenix`
--

-- --------------------------------------------------------

--
-- Table structure for table `additionalcourses`
--

CREATE TABLE `additionalcourses` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `code` varchar(10) NOT NULL,
  `filePath` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `additionalcourses`
--

INSERT INTO `additionalcourses` (`id`, `name`, `code`, `filePath`) VALUES
(3, 'Augmented Reality', 'AR101', 'uploads/syllabus/6069e712600b3ARSyllabus.pdf'),
(4, 'Block Chain Technology', 'BC101', 'uploads/syllabus/60768d5575790BlockChainTechnologySyllabus.pdf'),
(5, 'Additional Skill-Set', 'AS101', 'uploads/syllabus/60798d85de423AS101.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE `calendars` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `calendarType` varchar(10) NOT NULL,
  `filePath` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `title`, `calendarType`, `filePath`) VALUES
(32, 'Academic Calendar (2018-19)', 'archive', 'uploads/calendars/606955d978d3aAcademicCalendar2018-19.pdf'),
(33, 'Academic Calendar (2019-20)', 'archive', 'uploads/calendars/606955e868bd8AcademicCalendar2019-20.pdf'),
(36, 'Academic Calendar (2020-21)', 'archive', 'uploads/calendars/6069e1d2ba787AcademicCalender20-21.pdf'),
(39, 'Test', 'current', 'uploads/calendars/6089467fdf474AcademicCalender20-21.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `videoID` int(11) NOT NULL,
  `userID` varchar(22) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `commentDateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `videoID`, `userID`, `comment`, `commentDateTime`) VALUES
(88, 157, '101548720698023436733', 'Nice video ', '2021-04-21 13:29:59'),
(91, 160, '103124328123610365729', 'Nice Video! Informative', '2021-04-25 12:31:59'),
(92, 160, '103124328123610365729', 'Great!', '2021-04-25 12:35:38'),
(93, 160, '103124328123610365729', 'Nice Vide!', '2021-04-26 04:15:02'),
(94, 154, '103124328123610365729', 'Nice', '2021-04-26 18:13:51'),
(95, 152, '100378007681311121285', 'Nice', '2021-04-27 03:07:13'),
(96, 160, '100378007681311121285', 'Hi', '2021-04-27 03:18:42'),
(98, 154, '104896977425724769975', 'Super', '2021-04-27 12:44:16'),
(99, 160, '110053796366508420732', 'hi', '2021-04-27 13:37:28'),
(100, 154, '104037260863764937817', 'hello', '2021-04-27 13:43:50'),
(101, 152, '103124328123610365729', 'Informational!', '2021-04-28 11:07:13');

-- --------------------------------------------------------

--
-- Table structure for table `courseinstances`
--

CREATE TABLE `courseinstances` (
  `id` int(11) NOT NULL,
  `courseCode` varchar(10) NOT NULL,
  `userID` varchar(22) NOT NULL,
  `learners` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courseinstances`
--

INSERT INTO `courseinstances` (`id`, `courseCode`, `userID`, `learners`) VALUES
(37, 'AS101', '103124328123610365729', 0),
(38, 'CS101', '103124328123610365729', 1),
(39, 'CS204', '103124328123610365729', 1),
(40, 'CS210', '114045962829284596457', 0),
(41, 'CS209', '103124328123610365729', 0),
(42, 'MA201', '103124328123610365729', 2),
(44, 'AS101', '114045962829284596457', 0),
(45, 'HS205', '100378007681311121285', 1),
(46, 'CS210', '100378007681311121285', 0),
(59, 'HS205', '103124328123610365729', 1),
(60, 'MA201', '104896977425724769975', 3),
(61, 'CS210', '104896977425724769975', 0),
(62, 'CS203', '100378007681311121285', 0),
(63, 'CS210', '110053796366508420732', 0),
(64, 'CS101', '110053796366508420732', 0),
(65, 'CS204', '110053796366508420732', 1),
(66, 'CS204', '110967665276235660274', 0),
(67, 'HS205', '110967665276235660274', 0),
(68, 'CS204', '104037260863764937817', 0),
(69, 'AR101', '103124328123610365729', 0);

-- --------------------------------------------------------

--
-- Table structure for table `examinations`
--

CREATE TABLE `examinations` (
  `id` int(11) NOT NULL,
  `instanceID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `noQuestions` int(11) NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `released` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `examinations`
--

INSERT INTO `examinations` (`id`, `instanceID`, `name`, `noQuestions`, `startTime`, `endTime`, `released`) VALUES
(51, 42, 'Quiz - 1', 3, '2021-04-25 19:55:00', '2021-04-25 20:00:00', 1),
(52, 59, 'Quiz', 2, '2021-04-26 10:10:00', '2021-04-26 10:15:00', 1),
(53, 60, 'Quiz 1', 3, '2021-04-27 18:26:00', '2021-04-28 18:26:00', 0),
(54, 38, 'Exam Test 1', 3, '2021-04-28 16:41:00', '2021-04-28 16:54:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `examsubmissions`
--

CREATE TABLE `examsubmissions` (
  `id` int(11) NOT NULL,
  `userID` varchar(22) NOT NULL,
  `examID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `answer` varchar(2) NOT NULL,
  `correct` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `examsubmissions`
--

INSERT INTO `examsubmissions` (`id`, `userID`, `examID`, `questionID`, `answer`, `correct`) VALUES
(28, '114045962829284596457', 51, 64, 'D', 1),
(29, '114045962829284596457', 51, 66, 'A', 1),
(30, '114045962829284596457', 52, 68, 'D', 0),
(31, '114045962829284596457', 52, 69, 'C', 0),
(32, '101548720698023436733', 53, 71, 'C', 0),
(33, '101548720698023436733', 53, 72, 'A', 0),
(34, '101548720698023436733', 53, 73, 'C', 0),
(35, '100378007681311121285', 53, 71, 'A', 0),
(36, '100378007681311121285', 53, 72, 'A', 0),
(37, '100378007681311121285', 53, 73, 'B', 0),
(38, '114045962829284596457', 54, 74, 'B', 0),
(39, '114045962829284596457', 54, 75, 'C', 0),
(40, '114045962829284596457', 54, 76, 'C', 0);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `userID` varchar(22) NOT NULL,
  `video_id` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `userID`, `video_id`, `time`) VALUES
(985, '103124328123610365729', 160, '2021-04-25 12:09:30'),
(986, '103124328123610365729', 160, '2021-04-25 12:09:37'),
(987, '103124328123610365729', 160, '2021-04-25 12:28:56'),
(988, '103124328123610365729', 160, '2021-04-25 12:29:09'),
(989, '103124328123610365729', 160, '2021-04-25 12:29:13'),
(990, '103124328123610365729', 160, '2021-04-25 12:29:17'),
(991, '103124328123610365729', 160, '2021-04-25 12:29:35'),
(992, '103124328123610365729', 160, '2021-04-25 12:30:47'),
(993, '103124328123610365729', 160, '2021-04-25 12:31:11'),
(994, '103124328123610365729', 160, '2021-04-25 12:31:15'),
(995, '103124328123610365729', 160, '2021-04-25 12:33:56'),
(996, '103124328123610365729', 160, '2021-04-25 12:33:59'),
(997, '103124328123610365729', 160, '2021-04-25 12:34:22'),
(998, '103124328123610365729', 160, '2021-04-25 12:35:15'),
(1002, '103124328123610365729', 158, '2021-04-25 13:36:37'),
(1004, '103124328123610365729', 160, '2021-04-26 04:12:22'),
(1005, '103124328123610365729', 160, '2021-04-26 04:13:04'),
(1006, '103124328123610365729', 160, '2021-04-26 04:13:10'),
(1007, '103124328123610365729', 160, '2021-04-26 04:13:15'),
(1008, '103124328123610365729', 160, '2021-04-26 04:13:49'),
(1009, '103124328123610365729', 160, '2021-04-26 04:13:52'),
(1010, '103124328123610365729', 154, '2021-04-26 04:14:10'),
(1011, '103124328123610365729', 160, '2021-04-26 04:14:18'),
(1012, '103124328123610365729', 160, '2021-04-26 04:15:10'),
(1013, '103124328123610365729', 154, '2021-04-26 04:16:01'),
(1014, '103124328123610365729', 156, '2021-04-26 04:18:53'),
(1015, '103124328123610365729', 154, '2021-04-26 04:54:46'),
(1016, '103124328123610365729', 154, '2021-04-26 04:55:01'),
(1017, '103124328123610365729', 154, '2021-04-26 04:58:36'),
(1018, '103124328123610365729', 158, '2021-04-26 05:00:30'),
(1019, '114708933468509424611', 154, '2021-04-26 17:13:45'),
(1020, '114708933468509424611', 156, '2021-04-26 17:18:08'),
(1021, '114708933468509424611', 157, '2021-04-26 17:18:16'),
(1022, '114708933468509424611', 153, '2021-04-26 17:18:22'),
(1023, '103124328123610365729', 154, '2021-04-26 18:13:36'),
(1024, '103124328123610365729', 156, '2021-04-26 18:14:52'),
(1025, '103124328123610365729', 154, '2021-04-26 18:21:36'),
(1026, '103124328123610365729', 154, '2021-04-26 18:21:43'),
(1027, '103124328123610365729', 156, '2021-04-26 18:30:52'),
(1028, '100378007681311121285', 152, '2021-04-27 03:05:52'),
(1029, '100378007681311121285', 152, '2021-04-27 03:07:18'),
(1030, '100378007681311121285', 152, '2021-04-27 03:07:23'),
(1031, '100378007681311121285', 152, '2021-04-27 03:07:30'),
(1032, '100378007681311121285', 152, '2021-04-27 03:08:06'),
(1033, '100378007681311121285', 152, '2021-04-27 03:08:43'),
(1034, '100378007681311121285', 160, '2021-04-27 03:18:19'),
(1035, '100378007681311121285', 160, '2021-04-27 03:19:17'),
(1036, '100378007681311121285', 160, '2021-04-27 03:19:30'),
(1037, '100378007681311121285', 160, '2021-04-27 03:27:19'),
(1038, '100378007681311121285', 160, '2021-04-27 03:27:23'),
(1039, '100378007681311121285', 160, '2021-04-27 03:27:32'),
(1040, '100378007681311121285', 160, '2021-04-27 03:27:45'),
(1041, '101548720698023436733', 160, '2021-04-27 03:34:45'),
(1042, '101548720698023436733', 160, '2021-04-27 03:34:50'),
(1043, '100378007681311121285', 158, '2021-04-27 03:47:23'),
(1044, '100378007681311121285', 160, '2021-04-27 09:47:01'),
(1045, '100378007681311121285', 160, '2021-04-27 09:47:50'),
(1046, '100378007681311121285', 160, '2021-04-27 09:48:19'),
(1047, '100378007681311121285', 160, '2021-04-27 09:48:28'),
(1048, '100378007681311121285', 160, '2021-04-27 09:48:43'),
(1049, '100378007681311121285', 160, '2021-04-27 09:48:59'),
(1050, '103124328123610365729', 160, '2021-04-27 10:16:27'),
(1054, '104896977425724769975', 172, '2021-04-27 12:42:31'),
(1055, '104896977425724769975', 154, '2021-04-27 12:43:59'),
(1056, '100378007681311121285', 157, '2021-04-27 12:48:27'),
(1057, '110053796366508420732', 175, '2021-04-27 13:14:43'),
(1058, '101548720698023436733', 175, '2021-04-27 13:17:54'),
(1059, '110053796366508420732', 176, '2021-04-27 13:21:28'),
(1061, '110053796366508420732', 160, '2021-04-27 13:36:29'),
(1064, '104037260863764937817', 176, '2021-04-27 13:43:13'),
(1065, '104037260863764937817', 154, '2021-04-27 13:43:36'),
(1066, '104037260863764937817', 174, '2021-04-27 13:44:12'),
(1067, '104037260863764937817', 176, '2021-04-27 13:44:27'),
(1068, '104037260863764937817', 180, '2021-04-27 13:55:22'),
(1069, '104037260863764937817', 180, '2021-04-27 13:55:29'),
(1070, '104037260863764937817', 181, '2021-04-27 13:55:37'),
(1071, '104037260863764937817', 182, '2021-04-27 13:58:08'),
(1072, '104896977425724769975', 157, '2021-04-28 09:56:05'),
(1073, '103124328123610365729', 152, '2021-04-28 10:00:40'),
(1074, '100378007681311121285', 179, '2021-04-28 10:07:59'),
(1075, '100378007681311121285', 173, '2021-04-28 10:11:14'),
(1076, '100378007681311121285', 173, '2021-04-28 10:11:25'),
(1077, '104896977425724769975', 153, '2021-04-28 10:17:03'),
(1078, '104896977425724769975', 180, '2021-04-28 10:17:10'),
(1079, '104896977425724769975', 180, '2021-04-28 10:18:17'),
(1080, '104896977425724769975', 157, '2021-04-28 10:19:25'),
(1081, '104896977425724769975', 155, '2021-04-28 10:19:32'),
(1083, '103124328123610365729', 152, '2021-04-28 11:04:37'),
(1084, '103124328123610365729', 152, '2021-04-28 11:05:31'),
(1085, '103124328123610365729', 152, '2021-04-28 11:06:16'),
(1086, '103124328123610365729', 152, '2021-04-28 11:07:22'),
(1087, '103124328123610365729', 156, '2021-04-28 11:11:28');

-- --------------------------------------------------------

--
-- Table structure for table `includedcontent`
--

CREATE TABLE `includedcontent` (
  `id` int(11) NOT NULL,
  `userID` varchar(22) NOT NULL,
  `instanceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `includedcontent`
--

INSERT INTO `includedcontent` (`id`, `userID`, `instanceID`) VALUES
(10, '114045962829284596457', 42),
(11, '114045962829284596457', 59),
(12, '114045962829284596457', 45),
(13, '100378007681311121285', 39),
(14, '100378007681311121285', 60),
(15, '101548720698023436733', 60),
(16, '101548720698023436733', 42),
(17, '114045962829284596457', 60),
(18, '114045962829284596457', 65),
(19, '114045962829284596457', 38);

-- --------------------------------------------------------

--
-- Table structure for table `institutecourses`
--

CREATE TABLE `institutecourses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `filePath` varchar(256) NOT NULL,
  `type` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `institutecourses`
--

INSERT INTO `institutecourses` (`id`, `name`, `code`, `filePath`, `type`) VALUES
(4, 'Ethics', 'HS205', 'uploads/syllabus/6085509d76765EthicsSyllabus.pdf', 'Regular'),
(5, 'Linear Algebra', 'MA201', 'uploads/syllabus/6069e3b17f216LASyllabus.pdf', 'Regular'),
(7, 'Operating Systems', 'CS204', 'uploads/syllabus/6069e3ed4b631OSSyllabus.pdf', 'Regular'),
(8, 'Software Engineering', 'CS209', 'uploads/syllabus/6069e402970fcSESyllabus.pdf', 'Regular'),
(9, 'Database Management Systems', 'CS210', 'uploads/syllabus/6069e631d41a0DBMSSyllabus.pdf', 'Regular'),
(10, 'C Programming', 'CS101', 'uploads/syllabus/607ae09e2514aCSyllabus.pdf', 'Regular'),
(12, 'Theory of computation', 'CS203', 'uploads/syllabus/6088091a06402THEORYOFCOMPUTATION(1).pdf', 'Regular');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `userID` varchar(22) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `userID`, `video_id`) VALUES
(177, '103124328123610365729', 160),
(179, '100378007681311121285', 152),
(180, '100378007681311121285', 160),
(181, '100378007681311121285', 158),
(183, '104896977425724769975', 154),
(184, '101548720698023436733', 175),
(185, '104037260863764937817', 154),
(186, '104037260863764937817', 174),
(187, '104037260863764937817', 176),
(188, '104037260863764937817', 180),
(189, '104037260863764937817', 181),
(190, '104037260863764937817', 182),
(191, '100378007681311121285', 179),
(192, '100378007681311121285', 173),
(193, '103124328123610365729', 152);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `sno` int(11) NOT NULL,
  `filePath` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `sno`, `filePath`) VALUES
(6, 'News 1', 1, 'uploads/news/60853014a2e18News1.pdf'),
(7, 'News 2', 2, 'uploads/news/6069e82d6eb0fNews2.pdf'),
(8, 'News 3', 3, 'uploads/news/6069e835d7dfcNews3.pdf'),
(9, 'News 4', 4, 'uploads/news/6069e83fdcc5dNews4.pdf'),
(10, 'News 5', 5, 'uploads/news/6069e84a71f00News5.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `videoID` int(11) NOT NULL,
  `userID` varchar(22) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `videoID`, `userID`, `title`, `content`) VALUES
(18, 160, '103124328123610365729', 'Operating Systems', 'You can edit this text!<br>Operating Systems<div>sth</div><div>sth<br>\n            E = mc^2<br>\n            E: Energy;\n            m: mass;\n            c: speed of light\n                        </div>'),
(19, 154, '114708933468509424611', 'Title', 'You can edit this text!<br><br>\n            E = mc^2<br>\n            E: Energy;\n            m: mass;\n            c: speed of light'),
(20, 176, '104037260863764937817', 'Title', 'You can edit this text!<br><br>\n            E = mc^2<br>\n            E: Energy;\n            m: mass;\n            c: speed of light'),
(21, 152, '103124328123610365729', 'GATE Tips notes', 'GATE Pattern');

-- --------------------------------------------------------

--
-- Table structure for table `questionandanswers`
--

CREATE TABLE `questionandanswers` (
  `id` int(11) NOT NULL,
  `assignment` varchar(50) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `optionA` varchar(100) NOT NULL,
  `optionB` varchar(100) NOT NULL,
  `optionC` varchar(100) NOT NULL,
  `optionD` varchar(100) NOT NULL,
  `answer` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questionandanswers`
--

INSERT INTO `questionandanswers` (`id`, `assignment`, `question`, `optionA`, `optionB`, `optionC`, `optionD`, `answer`) VALUES
(64, '51', 'Q1', 'A', 'B', 'C', 'D', 'D'),
(66, '51', 'Q3', 'A', 'B', 'C', 'D', 'A'),
(67, '51', 'Q4', 'A', 'B', 'C', 'D', 'B'),
(68, '52', 'Q1', 'ABCD', 'EFGH', 'IJKL', 'MNOP', 'C'),
(69, '52', 'Q2', 'QRST', 'UVWX', 'YZ12', '3456', 'B'),
(70, '52', 'Q3', 'A', 'B', 'C', 'D', 'A'),
(71, '53', 'Linear algebra ', 'A', 'B', 'C', 'D', 'A'),
(72, '53', 'Unitary matrix', '1', 'W', '45', '2', 'A'),
(73, '53', 'Modulus', '23', '3', '44', '34', 'B'),
(74, '54', 'Q1', 'A', 'B', 'C', 'D', 'A'),
(75, '54', 'Q2', 'A', 'B', 'C', 'D', 'D'),
(76, '54', 'Q3', 'A', 'B', 'C', 'D', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `year` int(1) NOT NULL,
  `filePath` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `year`, `filePath`) VALUES
(25, 2, 'uploads/schedules/6069e2f8db3d0TimeTable2ndYear.pdf'),
(26, 3, 'uploads/schedules/6069e30121a5aTimeTable3rdYear.pdf'),
(27, 4, 'uploads/schedules/6069e30937eaaTimeTable4thYear.pdf'),
(28, 1, 'uploads/schedules/608530905cabbTT1.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(22) NOT NULL,
  `name` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `signUpDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imageURL` varchar(255) NOT NULL,
  `cvFilePath` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `signUpDate`, `imageURL`, `cvFilePath`) VALUES
('100378007681311121285', 'KARTHIK SAJJAN IIIT Dharwad', 'Student', '19bcs049@iiitdwd.ac.in', '2021-04-21 11:40:16', 'https://lh3.googleusercontent.com/a-/AOh14Gg3DLO9E2IPJ6xatotcTN_nzfKzNijzElxwFyRZkQ=s96-c', ''),
('100849867991110553827', 'VANGA MANOJ SAHIT REDDY IIIT Dharwad', 'Student', '19bcs110@iiitdwd.ac.in', '2021-04-27 13:41:12', 'https://lh6.googleusercontent.com/-gn2XpMYh-Cs/AAAAAAAAAAI/AAAAAAAAAAA/AMZuucmPv5dcXRwG23ix1mKrPibo6r7EJw/s96-c/photo.jpg', ''),
('101548720698023436733', 'KARUSALA DEEPAK CHOWDARY IIIT Dharwad', 'Student', '19bcs050@iiitdwd.ac.in', '2021-04-21 13:29:11', 'https://lh3.googleusercontent.com/-g-E-W-wqvk0/AAAAAAAAAAI/AAAAAAAAAAA/AMZuucngaa97GFKaRVcEGPp83Msir8icxw/s96-c/photo.jpg', ''),
('103124328123610365729', 'Krishna Paanchajanya', 'Faculty', 'krishnapaanchajanya1966@gmail.com', '2021-04-17 18:37:35', 'https://lh3.googleusercontent.com/a-/AOh14GgwwFLDyOQFhq4R0gKOmGyflzChzZYIfkw3-B71_A=s96-c', 'uploads/cv/60858de29f95eCV-KrishnaPaanchajanyadate17032021.pdf'),
('104037260863764937817', 'manoj reddy', 'Faculty', 'vanga.manojsahith@gmail.com', '2021-04-27 13:41:57', 'https://lh3.googleusercontent.com/a-/AOh14Gi2kYVa1A_WTalcHo7e1kHxdNXDHK9TAs6KoXy3iQ=s96-c', ''),
('104896977425724769975', 'KARTHIK SAJJAN', 'Faculty', 'karthiksajjan1@gmail.com', '2021-04-21 10:25:22', 'https://lh3.googleusercontent.com/a-/AOh14Gi97MKfOxB6UxqWfsg2DhlPpvFxPw6IFRBvKlA7vA=s96-c', ''),
('106125962438658205754', 'Team Octette', 'Faculty', 'team.octette@gmail.com', '2021-04-23 09:05:51', 'https://lh3.googleusercontent.com/a-/AOh14Ggc71jWx3hodRtjDYk3sagH46lP7h4Ha3grZ8tF=s96-c', ''),
('107124245109282463034', 'Sunder Ram', 'Faculty', 'sunderprr@gmail.com', '2021-04-27 10:41:47', 'https://lh5.googleusercontent.com/-uelpn8m5Ioc/AAAAAAAAAAI/AAAAAAAAqu4/AMZuucmzRt8pGCCKujtkkJIgOYJdi89zlA/s96-c/photo.jpg', ''),
('108043022126742343384', 'Project AcadGenix', 'Admin', 'acadgenix@gmail.com', '2021-04-17 18:49:31', 'https://lh3.googleusercontent.com/a-/AOh14Gj2Hg57kkQrYMjMR6sl8WtdoSF3RuKSeeqzo_gB=s96-c', ''),
('110053796366508420732', 'KILLADI VENKATA JAI SANTESWAR IIIT Dharwad', 'Student', '19bcs053@iiitdwd.ac.in', '2021-04-27 13:03:03', 'https://lh6.googleusercontent.com/-zax8aRQ1Akw/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuclnBcv8A6zN_dzHu6lPzKlsHloncQ/s96-c/photo.jpg', ''),
('110967665276235660274', 'DHYAN M.G IIIT Dharwad', 'Student', '19bcs038@iiitdwd.ac.in', '2021-04-27 13:32:25', 'https://lh3.googleusercontent.com/-lGxTYkl3nkg/AAAAAAAAAAI/AAAAAAAAAAA/AMZuucmU7c1y8LuGWxOSbucS-wH7KPMtRg/s96-c/photo.jpg', ''),
('112045201988029005534', 'Suvarna Kuppa', 'Faculty', 'suvarnaramk@gmail.com', '2021-04-02 20:44:48', 'https://lh3.googleusercontent.com/a-/AOh14GiYf99joIeSjl0jvI25lDKaVxJdp7iX8G8hJn70Yg0=s96-c', ''),
('114045962829284596457', 'KUPPA VENKATA KRISHNA PAANCHAJANYA IIIT Dharwad', 'Student', '19bcs063@iiitdwd.ac.in', '2021-04-17 19:41:06', 'https://lh4.googleusercontent.com/-n822hDJ3OIc/AAAAAAAAAAI/AAAAAAAAAD0/AMZuucn4294s7Zybnx7luuSkwFxVMj7bnw/s96-c/photo.jpg', ''),
('114085146368814742800', 'kkm_282', 'Faculty', 'ksheermanu@gmail.com', '2021-04-02 20:41:06', 'https://lh3.googleusercontent.com/a-/AOh14GhLOoCWRvXc6WUUTCCTd_08oj2WshKscEm0lUT6WQ=s96-c', ''),
('114708933468509424611', 'DASARI RISHIKESH', 'Faculty', 'drishikesh884@gmail.com', '2021-04-26 17:11:26', 'https://lh3.googleusercontent.com/a-/AOh14Gh0mcwuVuuCg8_eHEOYCClJ-5v1dNWoN8NNTvsUaQ=s96-c', ''),
('115940869514708638743', 'TARUN VARMA', 'Faculty', 'datlatarun2001@gmail.com', '2021-04-27 01:42:10', 'https://lh3.googleusercontent.com/-5AcAknLLTd4/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuclGqsQMDj75tgnkawaEig6EEhgNkQ/s96-c/photo.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `uploadedBy` varchar(22) NOT NULL,
  `course` varchar(7) NOT NULL,
  `title` varchar(70) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `filePath` varchar(250) NOT NULL,
  `uploadDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(11) NOT NULL DEFAULT '0',
  `duration` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `uploadedBy`, `course`, `title`, `description`, `filePath`, `uploadDate`, `views`, `duration`) VALUES
(152, '103124328123610365729', 'AS101', 'My Tips for GATE', 'Here are some tips I advice you to come out of GATE with flying colors!!!', 'uploads/videos/607adddf768da.mp4', '2021-04-17 18:40:44', 3, '20:37'),
(153, '103124328123610365729', 'AS101', 'How to host a website on Firebase', 'Here are the steps involved in hosting your website on firebase!', 'uploads/videos/607adf3e4c143.mp4', '2021-04-17 18:47:15', 2, '10:36'),
(154, '103124328123610365729', 'CS101', 'C Programming History', 'C programming is a general-purpose, procedural, imperative computer programming language developed in 1972 by Dennis M. Ritchie at the Bell Telephone Laboratories to develop the UNIX operating system. C is the most widely used computer language.', 'uploads/videos/607ae3ac9eb23.mp4', '2021-04-17 19:11:33', 6, '32:19'),
(155, '103124328123610365729', 'CS204', 'Introduction to Operating Systems', 'An operating system (OS) is system software that manages computer hardware, software resources, and provides common services for computer programs.', 'uploads/videos/607aeb0b32046.mp4', '2021-04-17 19:39:55', 2, '25:29'),
(156, '114045962829284596457', 'CS210', 'Firebase and PHP Installation', 'Firebase helps you build\r\nand run successful apps\r\nBacked by Google and loved by app development\r\nteams - from startups to global enterprises', 'uploads/videos/607aecbd89775.mp4', '2021-04-17 19:43:18', 4, '10:44'),
(157, '114045962829284596457', 'CS210', 'Database Management Systems', 'Database Management System (DBMS) is a software for storing and retrieving users\' data while considering appropriate security measures. It consists of a group of programs which manipulate the database.', 'uploads/videos/607aed94c365c.mp4', '2021-04-17 19:46:59', 5, '05:34'),
(158, '103124328123610365729', 'CS209', 'SOFTWARE ENGINEERING OBJECTIVES AND GOALS', 'Software engineering is a branch of computer science which includes the development and building of computer systems software and applications software. Computer systems software is composed of programs that include computing utilities and operations systems.', 'uploads/videos/607aee7176c03.mp4', '2021-04-17 19:58:36', 3, '52:27'),
(160, '103124328123610365729', 'CS204', 'Understanding Operating Systems', 'Computer Basics', 'uploads/videos/607b105114852.mp4', '2021-04-17 22:14:28', 6, '01:30'),
(172, '104896977425724769975', 'CS210', 'Dbms introduction animated', 'Database management system', 'uploads/videos/6088069285f09.mp4', '2021-04-27 12:41:54', 1, NULL),
(173, '104896977425724769975', 'MA201', 'Gate mathematics', 'Solution of gate mathematics exam', 'uploads/videos/608807bb42a9f.mp4', '2021-04-27 12:46:51', 1, NULL),
(174, '100378007681311121285', 'CS203', 'What is TOC', 'Theory of computation brief introduction', 'uploads/videos/608809c9757d7.mp4', '2021-04-27 12:55:37', 1, NULL),
(175, '110053796366508420732', 'CS210', 'keys in DBMS', 'Keys concept', 'uploads/videos/60880dd2c14fa.mp4', '2021-04-27 13:12:50', 2, NULL),
(176, '110053796366508420732', 'CS101', 'C programming features ', 'some important features of C and a basic code', 'uploads/videos/60880fb228a22.mp4', '2021-04-27 13:20:50', 2, NULL),
(179, '110053796366508420732', 'CS204', 'Operating Systems', 'basics of operating systems', 'uploads/videos/60881530c600a.mp4', '2021-04-27 13:44:16', 1, NULL),
(180, '104037260863764937817', 'CS204', 'os-1', 'first video in os', 'uploads/videos/608816e20e8fd.mp4', '2021-04-27 13:51:30', 2, NULL),
(181, '104037260863764937817', 'CS204', 'os-2', 'second video in os', 'uploads/videos/608817bcd4ca2.mp4', '2021-04-27 13:55:08', 1, NULL),
(182, '104037260863764937817', 'CS204', 'os-3', 'third video in os', 'uploads/videos/60881853752cd.mp4', '2021-04-27 13:57:39', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additionalcourses`
--
ALTER TABLE `additionalcourses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courseinstances`
--
ALTER TABLE `courseinstances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examinations`
--
ALTER TABLE `examinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examsubmissions`
--
ALTER TABLE `examsubmissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `includedcontent`
--
ALTER TABLE `includedcontent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutecourses`
--
ALTER TABLE `institutecourses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questionandanswers`
--
ALTER TABLE `questionandanswers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additionalcourses`
--
ALTER TABLE `additionalcourses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `courseinstances`
--
ALTER TABLE `courseinstances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `examinations`
--
ALTER TABLE `examinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `examsubmissions`
--
ALTER TABLE `examsubmissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1088;

--
-- AUTO_INCREMENT for table `includedcontent`
--
ALTER TABLE `includedcontent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `institutecourses`
--
ALTER TABLE `institutecourses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `questionandanswers`
--
ALTER TABLE `questionandanswers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
