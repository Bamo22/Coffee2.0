-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2015 at 09:39 AM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffee`
--

-- --------------------------------------------------------

--
-- Table structure for table `coffee_sessions`
--

CREATE TABLE IF NOT EXISTS `coffee_sessions` (
  `session_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(26) NOT NULL,
  `joins` int(11) NOT NULL,
  `max_joins` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coffee_session_list`
--

CREATE TABLE IF NOT EXISTS `coffee_session_list` (
  `session_id` int(11) NOT NULL,
  `user_name` varchar(35) NOT NULL,
  `cups_consumed` int(11) NOT NULL,
  `joined_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expense_transactions`
--

CREATE TABLE IF NOT EXISTS `expense_transactions` (
  `id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `registration_tokens`
--

CREATE TABLE IF NOT EXISTS `registration_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(25) NOT NULL,
  `user_name` int(11) NOT NULL,
  `expiration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(14) NOT NULL,
  `person` int(11) NOT NULL,
  `expir_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priv_lvl` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `person`, `expir_date`, `priv_lvl`) VALUES
('14d242c233ce68', 2, '2015-11-23 10:56:52', 2);

-- --------------------------------------------------------

--
-- Table structure for table `usrlist`
--

CREATE TABLE IF NOT EXISTS `usrlist` (
  `id` int(11) NOT NULL,
  `user_name` varchar(35) NOT NULL,
  `user_profile_pic` varchar(36) NOT NULL,
  `user_hash` varchar(65) NOT NULL,
  `user_salt` varchar(70) NOT NULL,
  `coins` decimal(10,2) NOT NULL,
  `lates_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usrlist`
--

INSERT INTO `usrlist` (`id`, `user_name`, `user_profile_pic`, `user_hash`, `user_salt`, `coins`, `lates_login`) VALUES
(2, 'kevin', '6e0ade5d859d5d4aa5631c793.jpg', '$2a$08$375c7e793fde5d254c0acumRrJAwun/FC4mv.RKj2baW5QsM2RnbK', '375c7e793fde5d254c0ac9b6e7c27366fe42175de71580f4bcb2c6d293847dd7', '15.50', '2015-11-19 07:57:44'),
(3, 'testuser', 'default.png', '$2a$08$6b0a3f97f4a75d68b5b6ducu9KgulVg0jxfAO39O4i5zx1fvkll7.', '6b0a3f97f4a75d68b5b6d87a330d554468b68d59236825882e142bfb8d759231', '0.00', '2015-10-26 15:56:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coffee_sessions`
--
ALTER TABLE `coffee_sessions`
  ADD UNIQUE KEY `session_id_2` (`session_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `coffee_session_list`
--
ALTER TABLE `coffee_session_list`
  ADD KEY `session_id` (`session_id`),
  ADD KEY `user` (`user_name`);

--
-- Indexes for table `registration_tokens`
--
ALTER TABLE `registration_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`user_name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`person`),
  ADD KEY `person` (`person`);

--
-- Indexes for table `usrlist`
--
ALTER TABLE `usrlist`
  ADD PRIMARY KEY (`user_name`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registration_tokens`
--
ALTER TABLE `registration_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usrlist`
--
ALTER TABLE `usrlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `coffee_session_list`
--
ALTER TABLE `coffee_session_list`
  ADD CONSTRAINT `coffee_session_list_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `coffee_sessions` (`session_id`),
  ADD CONSTRAINT `coffee_session_list_ibfk_2` FOREIGN KEY (`user_name`) REFERENCES `usrlist` (`user_name`);

--
-- Constraints for table `registration_tokens`
--
ALTER TABLE `registration_tokens`
  ADD CONSTRAINT `registration_tokens_ibfk_1` FOREIGN KEY (`user_name`) REFERENCES `usrlist` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`person`) REFERENCES `usrlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
