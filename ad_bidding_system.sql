-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 10:04 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ad_bidding_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `ad_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `advertiser_id` int(11) DEFAULT NULL,
  `time_slot_duration` decimal(3,1) NOT NULL DEFAULT 1.9,
  `title` text NOT NULL,
  `ad_type` text NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `paid` tinyint(1) DEFAULT 0,
  `ad_image_url` varchar(191) NOT NULL,
  `time_slot` time NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `js_code` varchar(9000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`ad_id`, `image_url`, `video_url`, `bid_amount`, `duration`, `start_time`, `end_time`, `advertiser_id`, `time_slot_duration`, `title`, `ad_type`, `width`, `height`, `paid`, `ad_image_url`, `time_slot`, `status`, `js_code`) VALUES
(9, 'http://localhost/dashboard/uploads/6600c0a58d4bc.jpg', NULL, '0.00', 0, '2025-01-30 07:15:44', '2025-01-31 07:15:44', 1, '1.9', 'Hello world', 'bar', 0, 0, 0, '', '00:00:00', 'Pending', ''),
(10, 'http://localhost/dashboard/uploads/6600c0a58d4bc.jpg', NULL, '0.00', 0, '2025-01-30 07:20:18', '2025-01-31 07:20:18', 1, '1.9', 'Hello world', 'bar', 240, 600, 0, '', '00:00:00', 'Pending', ''),
(11, NULL, NULL, '1.00', 2, '2025-01-30 08:09:57', '2025-01-31 08:09:57', 1, '1.9', '', 'bar', 240, 600, 0, 'http://localhost/dashboard/uploads/6600c0a58d4bc.jpg', '23:12:00', 'Pending', ''),
(12, NULL, NULL, '1.00', 3, '2025-01-30 08:39:46', '2025-01-31 08:39:46', 1, '1.9', '', 'bar', 240, 600, 0, 'http://localhost/dashboard/uploads/6600c0a58d4bc.jpg', '08:33:00', 'Pending', ''),
(13, NULL, NULL, '1.00', 3, '2025-01-30 08:43:16', '2025-01-31 08:43:16', 1, '1.9', '', 'bar', 240, 600, 0, 'http://localhost/dashboard/uploads/6600c0a58d4bc.jpg', '08:33:00', 'Pending', ''),
(14, NULL, NULL, '6.00', 5, '2025-01-30 09:07:53', '2025-01-31 09:07:53', 1, '1.9', '', 'bar', 240, 600, 0, 'http://localhost/dashboard/uploads/6600c0a58d4bc.jpg', '01:10:00', 'Pending', ''),
(15, NULL, NULL, '6.00', 5, '2025-01-30 09:16:35', '2025-01-31 09:16:35', 1, '1.9', '', 'bar', 240, 600, 0, 'http://localhost/dashboard/uploads/679188f126400.png', '01:10:00', 'Pending', ''),
(16, NULL, NULL, '6.00', 5, '2025-01-30 09:16:40', '2025-01-31 09:16:40', 1, '1.9', '', 'bar', 240, 600, 0, 'http://localhost/dashboard/uploads/679188f126400.png', '01:10:00', 'Pending', ''),
(17, NULL, NULL, '2.00', 5, '2025-01-30 09:28:53', '2025-01-31 09:28:53', 1, '1.9', '', 'horizontalbar', 240, 300, 0, 'http://localhost/dashboard/uploads/6600c0a58d4bc.jpg', '03:29:00', 'Pending', '');

-- --------------------------------------------------------

--
-- Table structure for table `advertisers`
--

CREATE TABLE `advertisers` (
  `advertiser_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `advertisers`
--

INSERT INTO `advertisers` (`advertiser_id`, `name`, `email`, `account_balance`) VALUES
(1, 'Test Advertiser', 'test@advertiser.com', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `ad_performance`
--

CREATE TABLE `ad_performance` (
  `performance_id` int(11) NOT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `impressions` int(11) DEFAULT 0,
  `clicks` int(11) DEFAULT 0,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bid_id` int(11) NOT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `advertiser_id` int(11) DEFAULT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `time_slot_start` datetime DEFAULT NULL,
  `time_slot_end` datetime DEFAULT NULL,
  `bid_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`ad_id`),
  ADD KEY `advertiser_id` (`advertiser_id`);

--
-- Indexes for table `advertisers`
--
ALTER TABLE `advertisers`
  ADD PRIMARY KEY (`advertiser_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ad_performance`
--
ALTER TABLE `ad_performance`
  ADD PRIMARY KEY (`performance_id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `ad_id` (`ad_id`),
  ADD KEY `advertiser_id` (`advertiser_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `advertisers`
--
ALTER TABLE `advertisers`
  MODIFY `advertiser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ad_performance`
--
ALTER TABLE `ad_performance`
  MODIFY `performance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ads`
--
ALTER TABLE `ads`
  ADD CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`advertiser_id`) REFERENCES `advertisers` (`advertiser_id`);

--
-- Constraints for table `ad_performance`
--
ALTER TABLE `ad_performance`
  ADD CONSTRAINT `ad_performance_ibfk_1` FOREIGN KEY (`ad_id`) REFERENCES `ads` (`ad_id`);

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`ad_id`) REFERENCES `ads` (`ad_id`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`advertiser_id`) REFERENCES `advertisers` (`advertiser_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
