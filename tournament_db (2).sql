-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 11:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tournament_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `activity_text` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `activity_text`, `timestamp`) VALUES
(1, 'Admin updated status/role of User ID: 2', '2025-12-25 19:25:30'),
(2, 'Admin updated status/role of User ID: 2', '2025-12-25 19:25:48'),
(3, 'Admin updated status/role of User ID: 3', '2025-12-25 19:25:54'),
(4, 'Tournament created: WUWA', '2025-12-25 19:26:44'),
(5, 'Admin updated User ID: 3 (Role: Player, Status: Active)', '2025-12-25 19:29:52'),
(6, 'Tournament created: ddddd', '2026-01-07 16:13:43'),
(7, 'Tournament created with attachments: AAAAAAAAA', '2026-01-07 16:35:08'),
(8, 'Tournament Deleted: ddddd (ID: 5) by admin', '2026-01-07 17:01:42'),
(9, 'Admin updated User ID: 3 (Role: Player, Status: Active)', '2026-01-07 17:02:53'),
(10, 'Tournament created with attachments: gffhbshs', '2026-01-07 17:03:49'),
(11, 'Tournament Deleted: gffhbshs (ID: 7) by user2', '2026-01-07 17:03:58'),
(12, 'New user registered: tanjim00', '2026-01-07 17:44:53'),
(13, 'Tournament Deleted: AAAAAAAAA (ID: 6) by tanjim00', '2026-01-07 17:45:34'),
(14, 'Tournament Deleted: WUWA (ID: 4) by tanjim00', '2026-01-07 17:45:43'),
(15, 'Tournament created with attachments: TANJIM', '2026-01-07 17:51:35'),
(16, 'New team formed: TANJIM SQUAD', '2026-01-07 18:07:47'),
(17, 'Match scheduled in Tournament ID: 8', '2026-01-07 18:30:17'),
(18, 'Tournament created with attachments: jwqdwdm', '2026-01-07 18:44:54'),
(19, 'Tournament Deleted: jwqdwdm (ID: 9) by tanjim00', '2026-01-07 18:45:02'),
(20, 'New user registered: testuser123', '2026-01-07 19:31:20'),
(21, 'New team formed: Test Team', '2026-01-07 19:31:51'),
(22, 'Tournament created: fdd', '2026-01-07 21:16:51'),
(23, 'New comment posted by admin', '2026-01-07 21:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(1) DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `tournament_id`, `username`, `comment`, `rating`, `created_at`) VALUES
(1, 3, 'admin', 'sdssd', 5, '2026-01-07 21:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) DEFAULT NULL,
  `team1_id` int(11) DEFAULT NULL,
  `team2_id` int(11) DEFAULT NULL,
  `match_date` datetime DEFAULT NULL,
  `winner_id` int(11) DEFAULT NULL,
  `status` enum('Scheduled','In Progress','Finished') DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `tournament_id`, `team1_id`, `team2_id`, `match_date`, `winner_id`, `status`) VALUES
(1, 1, 2, 1, '2025-12-27 01:07:00', 2, 'Finished'),
(2, 1, 2, 1, '2025-12-31 01:12:00', 1, 'Finished'),
(3, 8, 3, 2, '2026-01-09 15:31:00', 3, 'Finished');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `members` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `members`, `created_by`, `created_at`) VALUES
(1, 'Team Snezhnaya', 'Morshed,Milton', 'user2', '2025-12-25 18:51:37'),
(2, 'Team Sumeru', 'user', 'user2', '2025-12-25 18:56:56'),
(3, 'TANJIM SQUAD', 'tanjim00,user', 'user2', '2026-01-07 18:07:47'),
(4, 'Test Team', 'testuser123', 'testuser123', '2026-01-07 19:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `status` enum('Upcoming','Ongoing','Completed') DEFAULT 'Upcoming',
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `title`, `category`, `description`, `banner_image`, `status`, `created_by`, `created_at`) VALUES
(1, 'HONKAI STAR RAIL', 'E-Sports', 'RPG', NULL, 'Upcoming', 'user2', '2025-12-25 15:33:57'),
(3, 'GENSHIN IMPACT', 'Football', '', NULL, 'Upcoming', 'admin', '2025-12-25 19:21:22'),
(8, 'TANJIM', 'Cricket', '', 'banner_1767808295.jpg', 'Upcoming', 'tanjim00', '2026-01-07 17:51:35'),
(10, 'fdd', 'Cricket', 'fdfdf', '', 'Upcoming', 'admin', '2026-01-07 21:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `tournament_registrations`
--

CREATE TABLE `tournament_registrations` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournament_registrations`
--

INSERT INTO `tournament_registrations` (`id`, `tournament_id`, `team_id`, `registration_date`) VALUES
(1, 1, 2, '2025-12-25 18:58:51'),
(2, 1, 1, '2025-12-25 19:05:32'),
(3, 8, 3, '2026-01-07 18:28:46'),
(4, 8, 2, '2026-01-07 18:29:02'),
(5, 8, 1, '2026-01-07 18:29:09'),
(6, 8, 4, '2026-01-07 19:32:05'),
(7, 3, 4, '2026-01-07 19:33:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Organizer','Player') DEFAULT 'Player',
  `status` enum('Active','Blocked') DEFAULT 'Active',
  `profile_picture` varchar(255) DEFAULT 'default_user.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `status`, `profile_picture`) VALUES
(1, 'System Admin', 'admin', 'admin@mail.com', 'admin123', 'Admin', 'Active', 'default_user.png'),
(2, 'user', 'user', 'user@gmail.com', 'morshed123', 'Player', 'Active', 'default_user.png'),
(3, 'usertwo', 'user2', 'user2@gmail.com', 'morshed12345', 'Player', 'Active', 'user_3_1767807360.jpg'),
(4, 'TANJIM', 'tanjim00', 'tanjim@gmail.com', 'tanjim123', 'Player', 'Active', 'default_user.png'),
(5, 'Test User', 'testuser123', 'test@test.com', 'testpass123', 'Player', 'Active', 'default_user.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `team1_id` (`team1_id`),
  ADD KEY `team2_id` (`team2_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`team1_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`team2_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  ADD CONSTRAINT `tournament_registrations_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_registrations_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
