-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 24, 2026 at 03:57 AM
-- Server version: 11.8.3-MariaDB-0+deb13u1 from Debian
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eversole-network`
--
CREATE DATABASE IF NOT EXISTS `eversole-network` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `eversole-network`;

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `xp` int(11) NOT NULL,
  `coins` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `name`, `description`, `xp`, `coins`) VALUES
(1, 'New Beginnings', 'Create an account and login for the first time', 800, 20),
(2, 'Player', 'Play your first game', 800, 20),
(3, 'Creator', 'Create your first game', 1600, 40),
(4, 'Challanger', 'Log your first score', 800, 20),
(5, 'King', 'Log your first high score', 1600, 40);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `password`, `owner_id`, `created_at`) VALUES
(3, 'flappy', 6384860, 1, '2025-07-29 13:50:35'),
(9, 'ttyl', 3756299, 2, '2025-07-31 14:39:35'),
(11, 'spaceADV', 9082891, 2, '2025-09-06 14:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `game_requests`
--

CREATE TABLE `game_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_name` varchar(100) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `game_version` int(11) DEFAULT NULL,
  `type` enum('new_game','new_version') NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_versions`
--

CREATE TABLE `game_versions` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `version_number` int(11) NOT NULL,
  `released_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_versions`
--

INSERT INTO `game_versions` (`id`, `game_id`, `version_number`, `released_at`) VALUES
(2, 3, 1, '2025-07-29 20:50:35'),
(3, 3, 2, '2025-07-29 20:59:00'),
(5, 3, 3, '2025-07-29 21:24:46'),
(13, 9, 1, '2025-07-31 21:39:35'),
(14, 3, 4, '2025-08-01 16:32:14'),
(15, 3, 5, '2025-08-01 16:36:03'),
(17, 9, 2, '2025-07-31 21:39:35'),
(18, 11, 1, '2025-09-06 19:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` text NOT NULL,
  `link` text DEFAULT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `game_version_id` int(11) NOT NULL,
  `score` float NOT NULL,
  `achieved_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `game_id`, `game_version_id`, `score`, `achieved_at`) VALUES
(1, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(2, 1, 3, 2, 101, '2025-09-01 13:51:39'),
(3, 1, 3, 2, 98, '2025-09-01 14:20:57'),
(4, 1, 3, 2, 112, '2025-09-05 22:28:23'),
(5, 1, 3, 2, 2, '2025-09-06 10:21:35'),
(6, 1, 3, 2, 2, '2025-09-08 22:44:10'),
(7, 1, 3, 2, 2, '2025-09-08 22:46:20'),
(8, 1, 3, 2, 2, '2025-09-08 22:48:19'),
(9, 1, 3, 2, 2, '2025-09-08 22:57:56'),
(10, 1, 3, 2, 2, '2025-09-08 23:23:40'),
(11, 1, 3, 2, 2, '2025-09-08 23:24:49'),
(12, 1, 3, 2, 2, '2025-09-08 23:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session_identifier` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_version_id` int(11) NOT NULL,
  `logged_scores` int(11) NOT NULL DEFAULT 0,
  `earned_xp` int(11) NOT NULL DEFAULT 0,
  `earned_coins` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(45) NOT NULL,
  `connected` datetime NOT NULL DEFAULT current_timestamp(),
  `last_ping` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `session_identifier`, `user_id`, `game_version_id`, `logged_scores`, `earned_xp`, `earned_coins`, `ip`, `connected`, `last_ping`) VALUES
(1, 504294529, NULL, 2, 1, 0, 0, '::1', '2000-08-01 17:37:01', '2000-01-01 00:00:00'),
(2, 937484497, NULL, 2, 1, 0, 0, '::1', '2025-08-01 17:38:53', '2000-01-01 00:00:00'),
(3, 534207449, NULL, 2, 1, 0, 0, '::1', '2025-08-01 17:52:24', '2000-01-01 00:00:00'),
(4, 577968987, NULL, 2, 1, 0, 0, '::1', '2025-08-01 17:58:10', '2000-01-01 00:00:00'),
(5, 470515329, NULL, 2, 1, 0, 0, '::1', '2025-08-01 17:58:58', '2000-01-01 00:00:00'),
(6, 143267752, NULL, 2, 1, 0, 0, '::1', '2025-08-01 18:03:50', '2000-01-01 00:00:00'),
(7, 612958157, NULL, 2, 1, 0, 0, '::1', '2025-08-01 18:10:57', '2000-01-01 00:00:00'),
(8, 957072877, NULL, 2, 1, 0, 0, '::1', '2025-08-01 18:12:00', '2000-01-01 00:00:00'),
(9, 436698260, NULL, 2, 1, 0, 0, '::1', '2025-08-01 18:13:25', '2000-01-01 00:00:00'),
(10, 191046854, NULL, 2, 1, 0, 0, '::1', '2025-08-06 15:44:16', '2000-01-01 00:00:00'),
(11, 921314001, NULL, 2, 1, 0, 0, '::1', '2025-08-06 15:45:42', '2000-01-01 00:00:00'),
(12, 473586315, NULL, 2, 1, 0, 0, '::1', '2025-08-06 15:55:21', '2000-01-01 00:00:00'),
(13, 254979834, NULL, 2, 1, 0, 0, '::1', '2025-08-06 15:58:27', '2000-01-01 00:00:00'),
(14, 535042311, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:03:21', '2000-01-01 00:00:00'),
(15, 344929029, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:05:12', '2000-01-01 00:00:00'),
(16, 569087143, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:08:50', '2000-01-01 00:00:00'),
(17, 345451551, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:14:43', '2000-01-01 00:00:00'),
(18, 289843046, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:17:08', '2000-01-01 00:00:00'),
(19, 222877537, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:19:20', '2000-01-01 00:00:00'),
(20, 583919500, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:28:11', '2000-01-01 00:00:00'),
(21, 943565426, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:31:47', '2000-01-01 00:00:00'),
(22, 404645468, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:33:39', '2000-01-01 00:00:00'),
(23, 941430379, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:35:25', '2000-01-01 00:00:00'),
(24, 911909781, 1, 2, 1, 0, 0, '::1', '2025-08-06 16:36:12', '2000-01-01 00:00:00'),
(25, 528885992, 1, 2, 1, 0, 0, '::1', '2025-08-07 10:01:16', '2000-01-01 00:00:00'),
(26, 332342674, 1, 2, 1, 0, 0, '::1', '2025-08-07 10:07:54', '2000-01-01 00:00:00'),
(27, 796936224, 1, 2, 1, 0, 0, '::1', '2025-08-07 10:09:57', '2000-01-01 00:00:00'),
(28, 364176398, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:14:13', '2000-01-01 00:00:00'),
(29, 206887872, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:14:50', '2000-01-01 00:00:00'),
(30, 668959935, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:21:11', '2000-01-01 00:00:00'),
(31, 350615234, NULL, 2, 1, 0, 0, '::1', '2025-08-07 15:22:09', '2000-01-01 00:00:00'),
(32, 460541401, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:24:53', '2000-01-01 00:00:00'),
(33, 778934685, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:26:35', '2000-01-01 00:00:00'),
(34, 112709674, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:27:55', '2000-01-01 00:00:00'),
(35, 340285111, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:28:28', '2000-01-01 00:00:00'),
(36, 183004443, 2, 2, 1, 0, 0, '::1', '2025-08-07 15:29:14', '2000-01-01 00:00:00'),
(37, 365597117, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:30:13', '2000-01-01 00:00:00'),
(38, 655265133, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:33:18', '2000-01-01 00:00:00'),
(39, 250531081, NULL, 2, 1, 0, 0, '::1', '2025-08-07 15:34:13', '2000-01-01 00:00:00'),
(40, 245805758, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:36:40', '2000-01-01 00:00:00'),
(41, 736458802, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:37:31', '2000-01-01 00:00:00'),
(42, 434085943, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:39:26', '2000-01-01 00:00:00'),
(43, 854716353, 1, 2, 1, 0, 0, '::1', '2025-08-07 15:46:00', '2000-01-01 00:00:00'),
(44, 377714108, NULL, 2, 1, 0, 0, '::1', '2025-08-30 16:58:19', '2000-01-01 00:00:00'),
(45, 517391548, NULL, 2, 1, 0, 0, '::1', '2025-08-30 17:01:58', '2000-01-01 00:00:00'),
(46, 877391765, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:02:34', '2000-01-01 00:00:00'),
(47, 705700515, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:05:57', '2000-01-01 00:00:00'),
(48, 300081145, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:06:36', '2000-01-01 00:00:00'),
(49, 138812506, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:34:50', '2000-01-01 00:00:00'),
(50, 685603208, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:36:10', '2000-01-01 00:00:00'),
(51, 693978964, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:39:44', '2000-01-01 00:00:00'),
(52, 786072690, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:40:13', '2000-01-01 00:00:00'),
(53, 598041793, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:41:27', '2000-01-01 00:00:00'),
(54, 999015271, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:44:05', '2000-01-01 00:00:00'),
(55, 558790562, 1, 2, 1, 0, 0, '::1', '2025-08-30 17:45:48', '2000-01-01 00:00:00'),
(56, 434354939, 1, 2, 1, 0, 0, '::1', '2025-08-31 09:06:28', '2000-01-01 00:00:00'),
(57, 672904088, 1, 2, 1, 0, 0, '::1', '2025-08-31 09:09:48', '2000-01-01 00:00:00'),
(58, 296228741, 1, 2, 1, 0, 0, '::1', '2025-08-31 10:07:30', '2000-01-01 00:00:00'),
(59, 200876004, 1, 2, 1, 0, 0, '::1', '2025-08-31 10:08:44', '2000-01-01 00:00:00'),
(60, 934845490, 1, 2, 1, 0, 0, '::1', '2025-09-01 13:17:40', '2000-01-01 00:00:00'),
(61, 211300979, 1, 2, 1, 0, 0, '::1', '2025-09-01 13:22:27', '2000-01-01 00:00:00'),
(62, 234843532, 2, 2, 1, 0, 0, '::1', '2025-09-01 13:25:40', '2000-01-01 00:00:00'),
(63, 226797629, 1, 2, 1, 0, 0, '::1', '2025-09-01 13:45:19', '2000-01-01 00:00:00'),
(64, 464273645, 1, 2, 1, 0, 0, '::1', '2025-09-01 13:51:27', '2000-01-01 00:00:00'),
(65, 498402245, NULL, 2, 0, 0, 0, '::1', '2025-09-01 14:03:24', '2000-01-01 00:00:00'),
(66, 446184874, NULL, 2, 0, 0, 0, '::1', '2025-09-01 14:12:10', '2000-01-01 00:00:00'),
(67, 475864611, NULL, 2, 0, 0, 0, '::1', '2025-09-01 14:13:58', '2000-01-01 00:00:00'),
(68, 792545429, 1, 2, 0, 0, 0, '::1', '2025-09-01 14:15:02', '2000-01-01 00:00:00'),
(69, 883502424, 1, 2, 1, 823, 25, '::1', '2025-09-01 14:18:48', '2000-01-01 00:00:00'),
(70, 597566031, 1, 2, 0, 941, 20, '::1', '2025-09-01 14:59:18', '2000-01-01 00:00:00'),
(71, 201006728, NULL, 2, 0, 836, 20, '::1', '2025-09-01 15:13:09', '2000-01-01 00:00:00'),
(72, 502190955, NULL, 2, 0, 0, 0, '::1', '2025-09-04 22:05:21', '2000-01-01 00:00:00'),
(73, 483727358, NULL, 2, 0, 0, 0, '::1', '2025-09-04 22:05:49', '2000-01-01 00:00:00'),
(74, 721087578, NULL, 2, 0, 0, 0, '::1', '2025-09-04 22:06:34', '2000-01-01 00:00:00'),
(75, 212209294, NULL, 2, 0, 0, 0, '::1', '2025-09-04 22:06:43', '2000-01-01 00:00:00'),
(76, 161413348, 1, 2, 0, 3, 0, '::1', '2025-09-04 22:07:28', '2000-01-01 00:00:00'),
(77, 731645505, NULL, 2, 0, 0, 0, '::1', '2025-09-05 22:11:16', '2000-01-01 00:00:00'),
(78, 427566355, 1, 2, 0, 1, 0, '::1', '2025-09-05 22:14:45', '2000-01-01 00:00:00'),
(79, 767982185, 1, 2, 0, 0, 0, '::1', '2025-09-05 22:18:54', '2000-01-01 00:00:00'),
(80, 203937556, 1, 2, 0, 5, 0, '::1', '2025-09-05 22:19:41', '2000-01-01 00:00:00'),
(81, 870215359, 1, 2, 0, 1, 0, '::1', '2025-09-05 22:21:17', '2000-01-01 00:00:00'),
(82, 710211171, 1, 2, 0, 10, 0, '::1', '2025-09-05 22:23:36', '2000-01-01 00:00:00'),
(83, 454462522, 1, 2, 1, 17, 5, '::1', '2025-09-05 22:27:50', '2000-01-01 00:00:00'),
(84, 670388552, 3, 2, 0, 801, 20, '::1', '2025-09-05 22:35:31', '2000-01-01 00:00:00'),
(85, 451360620, NULL, 2, 0, 0, 0, '::1', '2025-09-05 22:36:51', '2000-01-01 00:00:00'),
(86, 444537153, 1, 2, 1, 11, 5, '::1', '2025-09-06 10:21:28', '2000-01-01 00:00:00'),
(87, 467758606, NULL, 2, 0, 6, 0, '::1', '2025-09-06 10:38:03', '2000-01-01 00:00:00'),
(88, 797738492, NULL, 2, 0, 0, 0, '::1', '2025-09-06 10:39:18', '2000-01-01 00:00:00'),
(89, 543113775, NULL, 2, 0, 1, 0, '::1', '2025-09-06 10:40:43', '2000-01-01 00:00:00'),
(90, 185260832, 1, 2, 0, 3, 0, '::1', '2025-09-08 22:35:08', '2000-01-01 00:00:00'),
(91, 755984374, 1, 2, 0, 7, 0, '::1', '2025-09-08 22:39:08', '2000-01-01 00:00:00'),
(92, 536019535, NULL, 2, 0, 0, 0, '::1', '2025-09-08 22:40:52', '2000-01-01 00:00:00'),
(93, 798179770, NULL, 2, 0, 0, 0, '::1', '2025-09-08 22:41:12', '2000-01-01 00:00:00'),
(94, 641366086, NULL, 2, 0, 0, 0, '::1', '2025-09-08 22:41:49', '2000-01-01 00:00:00'),
(95, 392037358, NULL, 2, 0, 0, 0, '::1', '2025-09-08 22:43:17', '2000-01-01 00:00:00'),
(96, 996258118, 1, 2, 1, 12, 5, '::1', '2025-09-08 22:43:48', '2000-01-01 00:00:00'),
(97, 951067053, 1, 2, 1, 12, 5, '::1', '2025-09-08 22:46:10', '2000-01-01 00:00:00'),
(98, 268827265, 1, 2, 1, 13, 5, '::1', '2025-09-08 22:47:58', '2000-01-01 00:00:00'),
(99, 289026941, NULL, 2, 0, 0, 0, '::1', '2025-09-08 22:50:54', '2000-01-01 00:00:00'),
(100, 225007170, 1, 2, 0, 3, 0, '::1', '2025-09-08 22:53:14', '2000-01-01 00:00:00'),
(101, 735543501, NULL, 2, 0, 0, 0, '::1', '2025-09-08 22:54:54', '2000-01-01 00:00:00'),
(102, 922195500, 1, 2, 1, 22, 5, '::1', '2025-09-08 22:56:57', '2000-01-01 00:00:00'),
(103, 522017130, 1, 2, 0, 9, 0, '::1', '2025-09-08 23:02:41', '2000-01-01 00:00:00'),
(104, 300645241, NULL, 2, 0, 0, 0, '::1', '2025-09-08 23:09:26', '2000-01-01 00:00:00'),
(105, 946127864, NULL, 2, 0, 0, 0, '::1', '2025-09-08 23:13:56', '2000-01-01 00:00:00'),
(106, 208236622, NULL, 2, 0, 0, 0, '::1', '2025-09-08 23:14:13', '2000-01-01 00:00:00'),
(107, 520134207, 1, 2, 1, 14, 5, '::1', '2025-09-08 23:23:29', '2000-01-01 00:00:00'),
(108, 705593286, 1, 2, 1, 13, 5, '::1', '2025-09-08 23:24:41', '2000-01-01 00:00:00'),
(109, 833429376, 1, 2, 1, 13, 5, '::1', '2025-09-08 23:26:57', '2000-01-01 00:00:00'),
(110, 900791383, NULL, 2, 0, 0, 0, '::1', '2025-09-09 22:59:26', '2000-01-01 00:00:00'),
(111, 721256161, 1, 13, 0, 4, 0, '::1', '2025-09-10 07:52:57', '2000-01-01 00:00:00'),
(112, 468193313, NULL, 13, 0, 0, 0, '::1', '2025-09-10 07:54:51', '2000-01-01 00:00:00'),
(113, 523649642, NULL, 13, 0, 0, 0, '::1', '2025-09-10 07:54:51', '2000-01-01 00:00:00'),
(114, 881971563, NULL, 13, 0, 0, 0, '::1', '2025-09-10 07:55:07', '2000-01-01 00:00:00'),
(115, 182031379, 1, 13, 0, 4, 0, '::1', '2025-09-10 07:57:31', '2025-09-10 07:59:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `permissions` int(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `authorized` tinyint(1) NOT NULL DEFAULT 0,
  `coins` mediumint(9) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `permissions`, `created`, `authorized`, `coins`, `xp`) VALUES
(1, 'admin', '$2y$12$mShleTCbrOzCl.VU/lRJDOS.C8Kn7E7L9ggSAGPaOrLoRgXQZ7Lz2', 'admin@localhost.com', 0, '2025-07-29 14:33:49', 1, 690, 4881),
(2, 'hgb', '$2y$12$HG336bbs.CVwgExgZZSMueYLsDE8iP2vLC84Yjt5K7.4NC8se/GsG', 'hudson.brackeen@gmail.com', 1, '2025-07-30 19:28:10', 1, 205, 3062),
(3, 'brb', '$2y$12$k94qTrr9b8r3b2T57BLEduTHZCda.ZIYwPMlGTGrBq.2oVjLY6ww.', 'brackbro0040@bisdmail.net', 1, '2025-08-09 21:12:27', 1, 25, 901),
(5, 'Magnes', '$2y$12$IxF3cOMEBaWQSP3/oyJsm.Ik4s1frBaTPxwdWXvunfuF1aTUMl3/K', 'magnes3d@live.com', 0, '2025-09-10 13:09:19', 1, 20, 800);

-- --------------------------------------------------------

--
-- Table structure for table `user_achieved`
--

CREATE TABLE `user_achieved` (
  `id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `achieved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_achieved`
--

INSERT INTO `user_achieved` (`id`, `achievement_id`, `user_id`, `achieved_at`) VALUES
(1, 4, 1, '2025-09-01 19:20:57'),
(2, 2, 1, '2025-09-01 19:59:22'),
(3, 2, 2, '2025-09-01 20:14:25'),
(4, 2, 3, '2025-09-06 03:35:35'),
(5, 3, 2, '2025-09-06 19:08:01'),
(7, 1, 5, '2025-09-10 13:09:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `description` (`description`) USING HASH;

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `game_requests`
--
ALTER TABLE `game_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `game_versions`
--
ALTER TABLE `game_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `game_version_id` (`game_version_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_version_id` (`game_version_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_achieved`
--
ALTER TABLE `user_achieved`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achievement_id` (`achievement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `game_requests`
--
ALTER TABLE `game_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `game_versions`
--
ALTER TABLE `game_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_achieved`
--
ALTER TABLE `user_achieved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `game_requests`
--
ALTER TABLE `game_requests`
  ADD CONSTRAINT `game_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `game_requests_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);

--
-- Constraints for table `game_versions`
--
ALTER TABLE `game_versions`
  ADD CONSTRAINT `game_versions_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scores_ibfk_3` FOREIGN KEY (`game_version_id`) REFERENCES `game_versions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`game_version_id`) REFERENCES `game_versions` (`id`);

--
-- Constraints for table `user_achieved`
--
ALTER TABLE `user_achieved`
  ADD CONSTRAINT `user_achieved_ibfk_1` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_achieved_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
