-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 30, 2025 at 10:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `truevote_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int NOT NULL,
  `election_id` int NOT NULL,
  `candidate_code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `party_name` varchar(255) DEFAULT NULL,
  `symbol` varchar(255) DEFAULT NULL,
  `age` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `manifesto` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `election_id`, `candidate_code`, `name`, `gender`, `party_name`, `symbol`, `age`, `photo`, `manifesto`, `created_at`) VALUES
(2, 1, 'CAND_68d8d415cd40e', 'MODI', 'Male', 'BJP', 'uploads/candidates/symbols/1759040533_1758614260_8eb338b3ad4791289d56318188f1fcf1.jpg', 75, 'uploads/candidates/photos/1759040533_1758614260_download.png', 'jai kissan ', '2025-09-28 06:22:13'),
(3, 2, 'CAND_68d9c7871d2b2', 'Justin', 'Male', 'UDF', 'uploads/candidates/symbols/1759102855_download.jfif', 24, 'uploads/candidates/photos/1759102855_1758614260_download.png', 'jai kiassan', '2025-09-28 23:40:55'),
(4, 2, 'CAND_68db63dc4e8e4', 'Akash', 'Male', 'BJP', 'uploads/candidates/symbols/1759208412_1759040533_1758614260_8eb338b3ad4791289d56318188f1fcf1.jpg', 22, 'uploads/candidates/photos/1759208412_1758614848_download.png', 'jai kissan', '2025-09-30 05:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `id` int NOT NULL,
  `election_code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `election_type` enum('Lok Sabha Elections (National)','State Legislative Assembly Elections (Vidhan Sabha)') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `constituency` varchar(100) NOT NULL,
  `description` text,
  `announcement_date` date NOT NULL,
  `registration_start_date` date NOT NULL,
  `registration_end_date` date NOT NULL,
  `polling_start_date` date NOT NULL,
  `polling_end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `election_code`, `title`, `election_type`, `constituency`, `description`, `announcement_date`, `registration_start_date`, `registration_end_date`, `polling_start_date`, `polling_end_date`, `created_at`) VALUES
(1, 'NAT2026', 'National Presidential Election 2026', 'Lok Sabha Elections (National)', 'Kottayam', 'Election to elect the President of the country.', '2025-09-25', '2025-09-23', '2026-04-27', '2026-05-28', '2026-05-30', '2025-09-22 00:54:25'),
(2, 'SLA25', 'State Legislative Assembly Elections (Vidhan Sabha) 2025', 'State Legislative Assembly Elections (Vidhan Sabha)', 'Kottayam', 'Election to choose representatives for the State Legislative Assembly', '2025-09-01', '2025-09-22', '2025-09-27', '2025-09-28', '2025-09-30', '2025-09-28 15:14:19');

-- --------------------------------------------------------

--
-- Table structure for table `election_registrations`
--

CREATE TABLE `election_registrations` (
  `id` int NOT NULL,
  `election_id` int NOT NULL,
  `voter_id` int NOT NULL,
  `registered_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `election_registrations`
--

INSERT INTO `election_registrations` (`id`, `election_id`, `voter_id`, `registered_at`) VALUES
(1, 1, 1, '2025-09-28 09:51:53'),
(2, 2, 1, '2025-09-28 23:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `otp_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `expiry_time` datetime NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `otp_verification`
--

INSERT INTO `otp_verification` (`otp_id`, `user_id`, `email`, `otp_code`, `expiry_time`, `is_verified`, `verified_at`, `created_at`) VALUES
(1, NULL, 'ig.nova0707@gmail.com', '997507', '2025-09-06 14:56:28', 1, '2025-09-06 20:22:03', '2025-09-06 14:51:28'),
(2, NULL, 'amalshaji7cr7@gmail.com', '573562', '2025-09-07 06:56:54', 1, '2025-09-07 12:22:26', '2025-09-07 06:51:54'),
(3, NULL, 'justinjob095@gmail.com', '384945', '2025-09-09 08:56:33', 1, '2025-09-09 14:22:03', '2025-09-09 08:51:33'),
(4, NULL, 'ananthus952@gmail.com', '139271', '2025-09-16 09:46:03', 0, NULL, '2025-09-16 09:41:03'),
(5, NULL, 'ananthus952@gmail.com', '535109', '2025-09-16 09:47:28', 1, '2025-09-16 15:12:52', '2025-09-16 09:42:28'),
(7, NULL, 'subbzero070@gmail.com', '417890', '2025-09-18 00:58:06', 1, '2025-09-18 06:23:36', '2025-09-18 00:53:06'),
(8, NULL, 'ig.nova0707@gmail.com', '843749', '2025-09-24 09:11:31', 1, '2025-09-24 14:37:22', '2025-09-24 09:06:31'),
(9, NULL, 'subbzero070@gmail.com', '841712', '2025-09-24 09:14:01', 1, '2025-09-24 14:39:30', '2025-09-24 09:09:01'),
(10, NULL, 'amalshaji7cr7@gmail.com', '199304', '2025-09-25 09:44:26', 1, '2025-09-25 15:09:56', '2025-09-25 09:39:26'),
(11, NULL, 'ig.nova0707@gmail.com', '170099', '2025-09-27 08:09:59', 1, '2025-09-27 13:35:45', '2025-09-27 08:04:59'),
(13, NULL, 'justinjob19@gmail.com', '675191', '2025-09-29 08:22:07', 1, '2025-09-29 13:47:41', '2025-09-29 08:17:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `voter_id` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text,
  `state` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `constituency` varchar(150) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','voter') DEFAULT 'voter',
  `status` enum('pending','verified','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `voter_id`, `full_name`, `father_name`, `gender`, `dob`, `address`, `state`, `district`, `constituency`, `email`, `phone`, `profile_image`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'KLKT0001001', 'Ajith Kumar', 'K. Suresh', 'Male', '1992-03-12', 'Shanti Nilayam, Chandanapally, Kottayam,Kerala — 686001', 'Kerala', 'Kottayam', 'Kottayam', 'ig.nova0707@gmail.com', '9383837646', 'uploads/profile/1758960965_passport_size_m.jpg', '$2y$10$Bz6nRNiDCkwkK7Q7kU34T.6Oy6aL9t.aKgCJAE31Bsxe1KC95E03q', 'voter', 'approved', '2025-09-07 06:01:35'),
(2, '123456789112', 'amal shaji', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'amalshaji7cr7@gmail.com', '9383837645', NULL, '$2y$10$B7pykimiUI.SOIXIedRX3OgLIAxpuNTdXhpm4qz4sNuHDpEvHE1XC', 'voter', 'approved', '2025-09-07 06:53:12'),
(3, '133455554545', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin123@gmail.com', '9867656566', 'uploads/profile/default.jpg', '$2y$10$AEZADZh63V7wBM7gtzu5GemoUmDACNGpP0ytNzvSQszY9nwHeVfyC', 'admin', 'approved', '2025-09-07 10:29:09'),
(4, '123456789116', 'Justin Job', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'justinjob095@gmail.com', '9383837623', NULL, '$2y$10$4IDZiyofv4h1GkU9iPZkoe6HKRicOmr9v3qRgcX0N3iZmUO6tFhyi', 'voter', 'approved', '2025-09-09 09:05:41'),
(5, '32', 'aaa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ananthus952@gmail.com', '89', NULL, '$2y$10$G1R8lad0L1e4OhGFQHSRYuj0zhghrFBZhzTuyzYbPm1WLQJ92LG6q', 'voter', 'approved', '2025-09-16 09:43:23'),
(6, '123456789117', 'Akash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'subbzero070@gmail.com', '9383837645', 'uploads/profile/1758722697_download.png', '$2y$10$QC57bg1q.UKjnmCTsnljA.vMI1azC5P0febp21avTDBZMvqGlQObu', 'voter', 'approved', '2025-09-18 01:00:07'),
(7, 'KLKT0001002', 'Justin Job', 'P V Job', 'Male', '2001-07-31', '', 'Kerala', 'Kottayam', 'Kottayam', 'justinjob19@gmail.com', '9383837344', NULL, '$2y$10$7xyvRT/4b9LteWtVj9Vx9e3RIIIUgEDwU.LJGPg/OdO0iPmgSoHD.', 'voter', 'approved', '2025-09-29 08:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int NOT NULL,
  `election_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `vote_count` tinyint(1) NOT NULL DEFAULT '0'
) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_candidates_election` (`election_id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `election_code` (`election_code`);

--
-- Indexes for table `election_registrations`
--
ALTER TABLE `election_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_registration` (`election_id`,`voter_id`),
  ADD KEY `fk_voter` (`voter_id`);

--
-- Indexes for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD PRIMARY KEY (`otp_id`),
  ADD KEY `fk_otp_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `aadhaar_no` (`voter_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `unique_vote` (`election_id`,`candidate_id`),
  ADD KEY `fk_votes_candidate` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `election_registrations`
--
ALTER TABLE `election_registrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `otp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `fk_candidates_election` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `election_registrations`
--
ALTER TABLE `election_registrations`
  ADD CONSTRAINT `fk_election` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_voter` FOREIGN KEY (`voter_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD CONSTRAINT `fk_otp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_votes_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_votes_election` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
