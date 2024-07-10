-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 10, 2024 at 11:50 PM
-- Server version: 10.3.39-MariaDB-cll-lve
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobw7774_api_faiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `election_id`, `name`, `description`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mas Anis', '01', 'https://i.imgur.com/x7XNwLN.jpeg', '2024-06-25 04:28:59', '2024-06-26 17:45:47'),
(3, 1, 'Mas Faiz', '02', 'https://i.imgur.com/RZP4L08.png', '2024-06-25 06:00:29', '2024-06-25 08:02:40'),
(4, 1, 'Mas Janggar', '03', 'https://i.imgur.com/A6SgamF.jpeg', '2024-06-25 08:01:51', '2024-06-25 08:01:51'),
(5, 2, 'Mas Gerrys', 'Ketua 1', 'https://i.imgur.com/fwqql6B.jpeg', '2024-06-25 08:12:29', '2024-06-25 09:00:54'),
(6, 3, 'Mas Mamat', '01', 'https://i.imgur.com/ULnuhRa.png', '2024-07-08 17:33:23', '2024-07-08 17:33:23'),
(7, 4, 'Pak Eko', '01', 'https://i.imgur.com/t3JamY3.png', '2024-07-10 13:29:03', '2024-07-10 13:29:03');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `title`, `description`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 'Pemilu', 'Pemilu 2029', '2024-06-24', '2024-07-10', '2024-06-24 12:37:04', '2024-06-30 17:39:01'),
(2, 'Pemilihan Ketua Kelas', 'Voting terbanyak akan terpilih', '2024-06-25', '2024-06-27', '2024-06-25 05:58:50', '2024-06-25 09:00:40'),
(3, 'Pemilihan Ketua RT', 'Ketua RT.001', '2024-07-09', '2024-07-10', '2024-07-08 17:29:20', '2024-07-08 17:29:20'),
(4, 'Pemilihan Ketua RW 002', 'Rw.002', '2024-07-10', '2024-07-11', '2024-07-10 13:26:28', '2024-07-10 13:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `level` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `created_at`, `updated_at`, `level`) VALUES
(1, 'faiz', '12345', 'izfaizback00@gmail.com', 'Muhammad Faiz', '2024-06-24 09:03:38', '2024-07-10 13:33:15', 1),
(2, 'temp', 'temp', 'temp@mail.com', 'temporary acc', '2024-06-24 13:20:34', '2024-06-26 18:48:04', 2),
(3, 'fakeacc', '123', 'fake@gmail.com', 'Dummy Account', '2024-06-25 09:17:01', '2024-06-25 09:17:01', 2),
(4, 'pseudo', '123', 'faiz_pseudo@gmail.com', 'Pseudo Account', '2024-06-27 06:39:17', '2024-06-27 06:39:17', 2),
(6, 'votingin', 'votingin', 'votingin@gmail.com', 'Voting In', '2024-06-27 07:03:13', '2024-06-27 07:03:13', 2),
(7, 'sadil', '123', 'sadil123@gmail.com', 'M Sadil', '2024-07-08 13:53:06', '2024-07-08 13:53:06', 2),
(8, 'dummy', '123', 'dummy2@gmail.com', 'Dummy Acc', '2024-07-10 13:21:42', '2024-07-10 13:21:42', 2);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `voted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `election_id`, `candidate_id`, `voted_at`) VALUES
(1, 1, 1, 3, '2024-06-25 06:04:29'),
(2, 1, 2, 5, '2024-06-25 08:13:45'),
(3, 2, 2, 5, '2024-06-25 08:23:49'),
(4, 2, 1, 1, '2024-06-25 08:58:56'),
(5, 3, 1, 4, '2024-06-25 09:22:45'),
(6, 3, 2, 5, '2024-06-25 09:23:37'),
(7, 6, 2, 5, '2024-06-27 07:03:43'),
(8, 1, 3, 6, '2024-07-08 17:33:37'),
(9, 2, 3, 6, '2024-07-08 17:35:41'),
(10, 1, 4, 7, '2024-07-10 13:31:04'),
(11, 8, 4, 7, '2024-07-10 13:52:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vote` (`user_id`,`election_id`),
  ADD KEY `election_id` (`election_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
