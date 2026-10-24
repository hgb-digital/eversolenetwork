-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 24, 2026 at 05:59 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
CREATE DATABASE IF NOT EXISTS `eversole-network` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
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
-- Table structure for table `email_verification`
--

CREATE TABLE `email_verification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  `type` enum('verify_email','reset_password') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(11, 'spaceADV', 9082891, 2, '2025-09-06 14:08:01'),
(12, 'Minecraft', 8552142, 1, '2025-10-18 10:25:12'),
(13, 'Minecraft43', 4449598, 2, '2025-10-18 14:17:29');

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
-- Table structure for table `game_saves`
--

CREATE TABLE `game_saves` (
  `id` int(11) NOT NULL,
  `save_data` longtext NOT NULL,
  `game_version_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_saves`
--

INSERT INTO `game_saves` (`id`, `save_data`, `game_version_id`, `user_id`, `timestamp`) VALUES
(1, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAI3N3Vg=', 2, 2, '2025-12-29 12:46:05'),
(2, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAJobo2k=', 2, 2, '2025-12-29 12:46:05'),
(3, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAG3Tqh0=', 2, 2, '2025-12-29 12:46:05'),
(4, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAPdm17UAAAAA', 2, 2, '2025-12-29 12:46:05'),
(5, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAA/rxMYAAAAA', 2, 2, '2025-12-29 12:48:12'),
(6, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABALzMu8MAAAAA', 2, 2, '2025-12-29 12:48:52'),
(7, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAI6UKCs=', 2, 2, '2025-12-29 12:49:44'),
(8, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAHkrWRU=', 2, 2, '2025-12-29 12:49:45'),
(9, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAPcNKkE=', 2, 2, '2025-12-29 12:49:45'),
(10, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAEju6M4AAAAA', 2, 2, '2025-12-29 12:49:45'),
(11, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAGp8Mik=', 2, 2, '2025-12-29 12:49:45'),
(12, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAALpJqg4=', 2, 2, '2025-12-29 12:49:45'),
(13, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAP1Pfr4AAAAA', 2, 2, '2025-12-29 12:49:45'),
(14, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAHvc3pgAAAAA', 2, 2, '2025-12-29 12:49:52'),
(15, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAIiDZMEAAAAA', 2, 2, '2025-12-29 12:49:52'),
(16, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAIsvf6IAAAAA', 2, 2, '2025-12-29 12:49:53'),
(17, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABABwxuLIAAAAA', 2, 2, '2025-12-29 12:56:08'),
(18, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAMQQy1I=', 2, 2, '2025-12-29 12:56:08'),
(19, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAIhQ4IoAAAAA', 2, 2, '2025-12-29 12:56:09'),
(20, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAKUuOhs=', 2, 2, '2025-12-29 12:56:09'),
(21, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAADwZM0U=', 2, 2, '2025-12-29 12:56:09'),
(22, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAFiWxKsAAAAA', 2, 2, '2025-12-29 12:56:21'),
(23, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAG/MgzE=', 2, 2, '2025-12-29 12:56:21'),
(24, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAMdC0/cAAAAA', 2, 2, '2025-12-29 12:56:21'),
(25, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAJbfK40AAAAA', 2, 2, '2025-12-29 12:56:21'),
(26, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABADImnfQAAAAA', 2, 2, '2025-12-29 12:56:22'),
(27, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAELkdJEAAAAA', 2, 2, '2025-12-29 12:56:22'),
(28, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAPH5vYsAAAAA', 2, 2, '2025-12-29 12:56:22'),
(29, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAADp+NBw=', 2, 2, '2025-12-29 12:56:22'),
(30, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAANsbdg0=', 2, 2, '2025-12-29 12:56:23'),
(31, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAAnu6OAAAAAA', 2, 2, '2025-12-29 12:56:23'),
(32, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAGJyjgA=', 2, 2, '2025-12-29 12:56:23'),
(33, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAKuqO90AAAAA', 2, 2, '2025-12-29 12:56:23'),
(34, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAIYvt/AAAAAA', 2, 2, '2025-12-29 12:56:23'),
(35, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAFJ08uMAAAAA', 2, 2, '2025-12-29 12:56:24'),
(36, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABACQG4vYAAAAA', 2, 2, '2025-12-29 12:56:24'),
(37, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAP8Z1b8AAAAA', 2, 2, '2025-12-29 12:56:24'),
(38, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAJxiICE=', 2, 2, '2025-12-29 12:56:24'),
(39, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAEKPIxM=', 2, 2, '2025-12-29 12:56:24'),
(40, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAKAqQ/0AAAAA', 2, 2, '2025-12-29 12:56:25'),
(41, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAByK25cAAAAA', 2, 2, '2025-12-29 12:57:01'),
(42, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAGpYhCQ=', 2, 2, '2025-12-29 12:57:01'),
(43, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAN+v37IAAAAA', 2, 2, '2025-12-29 12:57:01'),
(44, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAEK72r4AAAAA', 2, 2, '2025-12-29 12:57:01'),
(45, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAGiMxik=', 2, 2, '2025-12-29 12:59:27'),
(46, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAKMa5UQ=', 2, 2, '2025-12-29 13:04:19'),
(47, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABACX0Kt8AAAAA', 2, 2, '2025-12-29 13:04:19'),
(48, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABALTAvtAAAAAA', 2, 2, '2025-12-29 13:04:19'),
(49, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABANVqSLkAAAAA', 2, 2, '2025-12-29 13:29:18'),
(50, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAGPMsJsAAAAA', 2, 2, '2025-12-29 13:29:18'),
(51, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAIJJRlo=', 2, 2, '2025-12-29 13:29:18'),
(52, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAPa9WpkAAAAA', 2, 2, '2025-12-29 13:29:19'),
(53, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAAgO6hs=', 2, 2, '2025-12-29 13:29:19'),
(54, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAEW9/GI=', 2, 2, '2025-12-29 13:29:24'),
(55, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgABAAbHhMYAAAAA', 2, 2, '2025-12-29 13:29:24'),
(56, 'GwAAAAEAAAAEAAAABgAAAG51bWJlcgAAAgAAAFbDHAE=', 2, 2, '2025-12-29 13:29:24');

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
(18, 11, 1, '2025-09-06 19:08:01'),
(19, 12, 1, '2025-10-18 15:25:12'),
(20, 13, 1, '2025-10-18 19:17:29');

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
(6, 2, 3, 2, 150, '2026-01-08 20:02:35'),
(7, 2, 3, 2, 150, '2026-01-08 20:02:59'),
(8, 2, 3, 2, 150, '2026-01-08 20:03:13'),
(9, 2, 3, 2, 150, '2026-01-08 20:03:14'),
(10, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(11, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(12, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(13, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(14, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(15, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(16, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(17, 1, 3, 2, 150, '2025-09-01 13:50:11'),
(18, 2, 3, 2, 150, '2026-01-08 20:05:50'),
(19, 2, 13, 20, 150, '2026-01-08 20:05:50');

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
  `last_ping` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `playtime` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `session_identifier`, `user_id`, `game_version_id`, `logged_scores`, `earned_xp`, `earned_coins`, `ip`, `connected`, `last_ping`, `playtime`) VALUES
(1, 408569554, NULL, 2, 0, 201, 0, '::1', '2026-02-15 20:33:12', '2000-01-01 00:00:00', 30),
(2, 793537324, NULL, 2, 0, 2, 0, '::1', '2026-02-15 21:03:37', '2000-01-01 00:00:00', 0),
(3, 809652496, 1, 2, 0, 298, 540, '::1', '2026-02-15 21:06:38', '2000-01-01 00:00:00', 26),
(4, 251854491, 1, 18, 0, 216, 92, '::1', '2026-02-15 21:33:31', '2000-01-01 00:00:00', 18),
(5, 343038921, 2, 18, 0, 3, 0, '::1', '2026-02-15 22:07:49', '2000-01-01 00:00:00', 0),
(6, 705351910, NULL, 18, 0, 0, 0, '::1', '2026-02-15 22:10:34', '2000-01-01 00:00:00', 0),
(7, 195522165, 2, 18, 0, 40, 112, '::1', '2026-02-15 22:11:39', '2000-01-01 00:00:00', 5),
(8, 789174452, NULL, 18, 0, 2, 0, '::1', '2026-04-04 10:39:51', '2000-01-01 00:00:00', 0),
(9, 253000814, NULL, 2, 0, 8, 0, '::1', '2026-10-22 17:56:52', '2000-01-01 00:00:00', 2),
(10, 628796879, NULL, 18, 0, 0, 0, '::1', '2026-10-22 18:02:57', '2026-10-22 18:03:07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shop_items`
--

CREATE TABLE `shop_items` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `description` longtext NOT NULL,
  `cost` int(11) NOT NULL,
  `img_path` text DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_items`
--

INSERT INTO `shop_items` (`id`, `title`, `description`, `cost`, `img_path`, `date_added`) VALUES
(1, '3D Snake', 'green 3d printed snake', 50, 'meme.jpeg', '2026-04-04 16:06:42'),
(2, 'chrome browswer', 'don\'t ask', 50, 'meme.jpeg', '2026-04-04 16:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `vendor_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `value`, `time`, `vendor_id`, `user_id`, `session_id`) VALUES
(1, 56, '2026-02-16 02:46:59', 2, 2, 1),
(2, 56, '2026-02-16 02:47:01', 2, 2, 1),
(3, 56, '2026-02-16 03:03:55', 2, 2, 2),
(4, 56, '2026-02-16 03:03:56', 2, 2, 2),
(5, 56, '2026-02-16 03:03:56', 2, 2, 2),
(6, 56, '2026-02-16 03:03:56', 2, 2, 2),
(7, 56, '2026-02-16 03:06:48', 2, 2, 3),
(8, -20, '2026-02-16 03:30:12', 2, 2, 3),
(9, 56, '2026-02-16 03:31:12', 2, 1, 3),
(10, 56, '2026-02-16 03:31:13', 2, 1, 3),
(11, 56, '2026-02-16 03:31:29', 2, 1, 3),
(12, 56, '2026-02-16 03:31:29', 2, 1, 3),
(13, 56, '2026-02-16 03:31:29', 2, 1, 3),
(14, 56, '2026-02-16 03:31:29', 2, 1, 3),
(15, 56, '2026-02-16 03:31:30', 2, 1, 3),
(16, 56, '2026-02-16 03:31:30', 2, 1, 3),
(17, 56, '2026-02-16 03:31:30', 2, 1, 3),
(18, 56, '2026-02-16 03:33:49', 18, 1, 4),
(19, -20, '2026-02-16 03:33:52', 18, 1, 4),
(20, 56, '2026-02-16 03:33:53', 18, 1, 4),
(21, 56, '2026-02-16 04:17:03', 18, 2, 7),
(22, 56, '2026-02-16 04:17:03', 18, 2, 7);

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
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `authorized` tinyint(1) NOT NULL DEFAULT 1,
  `coins` mediumint(9) NOT NULL DEFAULT 0,
  `xp` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `permissions`, `created`, `email_verified`, `authorized`, `coins`, `xp`) VALUES
(1, 'admin', '$2y$12$rIirx.smXipXtFi7kIQZxuxe2GGHPmSDNb5gqOOD2eRv6mlIOoTGe', 'hudson.brackeen+e@gmail.com', 0, '2025-07-29 14:33:49', 1, 1, 1311, 7398),
(2, 'hgb', '$2y$12$hmacDZJQGIpMnTFuoi5O6u5xrKttqvIR0mWBccpeE3dayp8Iq02B.', 'hudson.brackeen@gmail.com', 1, '2025-07-30 19:28:10', 1, 1, 528, 6540),
(3, 'brb', '$2y$12$k94qTrr9b8r3b2T57BLEduTHZCda.ZIYwPMlGTGrBq.2oVjLY6ww.', 'hudson.brackeen+3@gmail.com', 1, '2025-08-09 21:12:27', 0, 1, 25, 901);

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
(7, 1, 1, '2025-09-13 16:07:57'),
(10, 3, 1, '2025-10-18 15:25:12'),
(11, 5, 2, '2026-01-09 02:02:35'),
(12, 4, 2, '2026-01-09 02:02:35');

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
-- Indexes for table `email_verification`
--
ALTER TABLE `email_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `game_saves`
--
ALTER TABLE `game_saves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_version_id` (`game_version_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `shop_items`
--
ALTER TABLE `shop_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_id` (`session_id`);

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
-- AUTO_INCREMENT for table `email_verification`
--
ALTER TABLE `email_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `game_requests`
--
ALTER TABLE `game_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `game_saves`
--
ALTER TABLE `game_saves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `game_versions`
--
ALTER TABLE `game_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `shop_items`
--
ALTER TABLE `shop_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_achieved`
--
ALTER TABLE `user_achieved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `email_verification`
--
ALTER TABLE `email_verification`
  ADD CONSTRAINT `email_verification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `game_saves`
--
ALTER TABLE `game_saves`
  ADD CONSTRAINT `game_saves_ibfk_1` FOREIGN KEY (`game_version_id`) REFERENCES `game_versions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_saves_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `game_versions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
