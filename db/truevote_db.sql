-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 08, 2025 at 12:12 AM
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
(1, 2, 'NOTA_68d8d415cd40e', 'None of the above', 'Other', 'No', NULL, NULL, NULL, NULL, '2025-10-02 13:08:06'),
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
  `publish_result` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `election_code`, `title`, `election_type`, `constituency`, `description`, `announcement_date`, `registration_start_date`, `registration_end_date`, `polling_start_date`, `polling_end_date`, `publish_result`, `created_at`) VALUES
(1, 'NAT2026', 'National Presidential Election 2026', 'Lok Sabha Elections (National)', 'Kottayam', 'Election to elect the President of the country.', '2025-09-25', '2025-09-23', '2026-04-27', '2026-05-28', '2026-05-30', '0', '2025-09-22 00:54:25'),
(2, 'SLA25', 'State Legislative Assembly Elections (Vidhan Sabha) 2025', 'State Legislative Assembly Elections (Vidhan Sabha)', 'Kottayam', 'Election to choose representatives for the State Legislative Assembly', '2025-09-01', '2025-09-22', '2025-09-27', '2025-09-28', '2025-10-03', '1', '2025-09-28 15:14:19');

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
(2, 2, 1, '2025-09-28 23:54:00'),
(3, 2, 8, '2025-10-03 10:34:24'),
(4, 1, 8, '2025-10-04 09:34:45');

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
(13, NULL, 'justinjob19@gmail.com', '675191', '2025-09-29 08:22:07', 1, '2025-09-29 13:47:41', '2025-09-29 08:17:07'),
(14, NULL, 'norepaly.dogspot@gmail.com', '755046', '2025-09-30 14:23:27', 0, NULL, '2025-09-30 14:18:27'),
(15, NULL, 'noreplay.dogspot@gmail.com', '738723', '2025-09-30 14:25:19', 1, '2025-09-30 19:51:05', '2025-09-30 14:20:19'),
(16, NULL, 'subbzero070@gmail.com', '689197', '2025-09-30 16:42:40', 1, '2025-09-30 22:09:27', '2025-09-30 16:37:40'),
(17, NULL, 'noreplay.dogspot@gmail.com', '323998', '2025-09-30 16:45:00', 0, NULL, '2025-09-30 16:40:00'),
(18, NULL, 'noreplay.dogspot@gmail.com', '637587', '2025-09-30 16:45:45', 0, NULL, '2025-09-30 16:40:45'),
(19, NULL, 'estbizone@gmail.com', '234526', '2025-09-30 16:50:44', 0, NULL, '2025-09-30 16:45:44'),
(20, NULL, 'estbiezone@gmail.com', '608689', '2025-09-30 16:52:15', 0, NULL, '2025-09-30 16:47:15');

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
(1, 'KLN0012367', 'Rohan Menon', 'K P Menon', 'Male', '1990-05-22', '21 Hill View', 'Kerala', 'Kottayam', 'Kottayam', 'ig.nova0707@gmail.com', '9383837646', 'uploads/profile/1758960965_passport_size_m.jpg', '$2y$10$Bz6nRNiDCkwkK7Q7kU34T.6Oy6aL9t.aKgCJAE31Bsxe1KC95E03q', 'voter', 'approved', '2025-09-07 06:01:35'),
(2, 'KLN0012373', 'amal shaji', NULL, NULL, NULL, NULL, 'Kerala', 'Kottayam', 'Kottayam', 'amalshaji7cr7@gmail.com', '9383837645', NULL, '$2y$10$B7pykimiUI.SOIXIedRX3OgLIAxpuNTdXhpm4qz4sNuHDpEvHE1XC', 'voter', 'rejected', '2025-09-07 06:53:12'),
(3, '601334555', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin123@gmail.com', '9867656566', 'uploads/profile/default.jpg', '$2y$10$AEZADZh63V7wBM7gtzu5GemoUmDACNGpP0ytNzvSQszY9nwHeVfyC', 'admin', 'approved', '2025-09-07 10:29:09'),
(4, 'KLN0012360', 'Aanya George', 'George Mathew', 'Female', '1995-11-19', '32 Lake Road', 'Kerala', 'Kottayam', 'Kottayam', 'justinjob095@gmail.com', '9383837623', NULL, '$2y$10$4IDZiyofv4h1GkU9iPZkoe6HKRicOmr9v3qRgcX0N3iZmUO6tFhyi', 'voter', 'approved', '2025-09-09 09:05:41'),
(5, 'KLN0012361', 'Ridhaan Rajesh', 'Rajesh Varma', 'Male', '1992-04-25', '11 MG Road', 'Kerala', 'Kottayam', 'Kottayam', 'ananthus952@gmail.com', '89', NULL, '$2y$10$G1R8lad0L1e4OhGFQHSRYuj0zhghrFBZhzTuyzYbPm1WLQJ92LG6q', 'voter', 'approved', '2025-09-16 09:43:23'),
(6, 'KLN0012363', 'Krishna Kurian', 'Kurian Nair', 'Male', '1991-12-09', '23 Hill View', 'Kerala', 'Kottayam', 'Kottayam', 'subbzero070@gmail.com', '9383837645', 'uploads/profile/1758722697_download.png', '$2y$10$bovfwths.F7hPUpYUBiB.Ojbd0hFIt3WXvjnMJ7lCklQlJ/cNQq62', 'voter', 'approved', '2025-09-18 01:00:07'),
(7, 'KLN0012370', 'Meera Pillai', 'Pillai Gopal', 'Female', '1991-01-08', '45 Church Street', 'Kerala', 'Kottayam', 'Kottayam', 'justinjob19@gmail.com', '9383837344', NULL, '$2y$10$7xyvRT/4b9LteWtVj9Vx9e3RIIIUgEDwU.LJGPg/OdO0iPmgSoHD.', 'voter', 'approved', '2025-09-29 08:48:30'),
(8, 'KLN0012345', 'Aarav Nair', 'Ramesh Nair', 'Male', '1990-03-12', '12 MG Road', 'Kerala', 'Kottayam', 'Kottayam', 'noreplay.dogspot@gmail.com', '9383837655', NULL, '$2y$10$61.IY5De7v/7GN0cpAUMCu5yDeG4oi7Jklk/7iYQMUPOuRsbIYpcy', 'voter', 'approved', '2025-09-30 14:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int NOT NULL,
  `election_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `voter_id` int NOT NULL,
  `transaction_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'vote3a8f9b64c1d72e49f6a9c83d27f51b8e914c5a63f2e7b9d1a84c7f93e05d',
  `contract_address` char(42) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'add9f3b7c82e1a4d56f890c12de34ab56789cd1234',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `vote_count` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `election_id`, `candidate_id`, `voter_id`, `transaction_hash`, `contract_address`, `created_at`, `vote_count`) VALUES
(15, 2, 3, 1, '0056a7b5b0098762da3a33609a96da7a46847e5706e1b8ee4bd8a25bbbd4d73a', '0x3A5900c41Bbf6fc26e167f65a431c7E77ED6744c', '2025-10-04 09:12:23', '1'),
(16, 2, 3, 8, 'd94637d9668e1984784911f62c69f703df3123788a8076c569e9614bb155d428', '0x0B0058b23371f241084FF5688AF9eFa0D2C0C69B', '2025-10-04 09:35:26', '1');

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
  ADD UNIQUE KEY `transaction_hash` (`transaction_hash`),
  ADD UNIQUE KEY `contract_address` (`contract_address`),
  ADD KEY `fk_votes_election` (`election_id`),
  ADD KEY `fk_votes_candidate` (`candidate_id`),
  ADD KEY `fk_votes_user` (`voter_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `election_registrations`
--
ALTER TABLE `election_registrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `otp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  ADD CONSTRAINT `fk_votes_election` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_votes_user` FOREIGN KEY (`voter_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
