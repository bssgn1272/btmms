-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2020 at 01:06 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `napsa`
--

-- --------------------------------------------------------

--
-- Table structure for table `Accounts`
--

CREATE TABLE `Accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Accounts`
--

INSERT INTO `Accounts` (`id`, `username`, `password`, `email`) VALUES
(1, 'test', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'test@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `Arrivals`
--

CREATE TABLE `Arrivals` (
  `bus_id` int(10) NOT NULL,
  `bus_number` varchar(20) NOT NULL,
  `time` time NOT NULL,
  `route_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `bay_id` int(10) NOT NULL,
  `status_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Arrivals`
--

INSERT INTO `Arrivals` (`bus_id`, `bus_number`, `time`, `route_id`, `company_id`, `bay_id`, `status_id`) VALUES
(1, 'MAN 9800', '08:00:00', 4, 2, 5, 1),
(2, 'NEX 3001', '09:20:00', 3, 1, 2, 1),
(3, 'CAX 5487', '08:30:00', 2, 5, 1, 1),
(4, 'GTR 7299', '10:00:00', 5, 4, 4, 1),
(5, 'ABT 3331', '11:00:00', 1, 3, 2, 4),
(6, 'RXX 2052', '09:35:00', 4, 1, 3, 1),
(7, 'TOP 1345', '14:00:00', 2, 2, 4, 1),
(8, 'RBY 1130', '14:30:00', 1, 4, 2, 1),
(9, 'AUC 5559', '14:50:00', 3, 3, 5, 1),
(10, 'ASJ 9992', '16:00:00', 5, 1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Bay`
--

CREATE TABLE `Bay` (
  `bay_id` int(10) NOT NULL,
  `bay_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Bay`
--

INSERT INTO `Bay` (`bay_id`, `bay_name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E');

-- --------------------------------------------------------

--
-- Table structure for table `Company`
--

CREATE TABLE `Company` (
  `company_id` int(10) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `type_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Company`
--

INSERT INTO `Company` (`company_id`, `company_name`, `type_id`) VALUES
(1, 'Mazhandu', 1),
(2, 'Power Tools', 1),
(3, 'Reno', 1),
(4, 'Eagle Way', 2),
(5, 'Good Motors', 2);

-- --------------------------------------------------------

--
-- Table structure for table `Departures`
--

CREATE TABLE `Departures` (
  `bus_id` int(10) NOT NULL,
  `bus_number` varchar(20) NOT NULL,
  `time` time NOT NULL,
  `route_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `bay_id` int(10) NOT NULL,
  `status_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Departures`
--

INSERT INTO `Departures` (`bus_id`, `bus_number`, `time`, `route_id`, `company_id`, `bay_id`, `status_id`) VALUES
(1, 'ALC 8330', '06:00:00', 5, 1, 1, 3),
(2, 'BAE 9863', '06:00:00', 4, 2, 2, 5),
(3, 'ABV 4536', '06:00:00', 3, 3, 3, 5),
(4, 'BAD 5321', '06:00:00', 2, 4, 4, 5),
(5, 'POR 6700', '06:00:00', 1, 5, 5, 5),
(6, 'ABJ 1122', '07:00:00', 5, 1, 3, 3),
(7, 'AXE 0043', '07:00:00', 4, 2, 2, 4),
(8, 'ABV 3702', '07:00:00', 3, 3, 1, 3),
(9, 'CAX 8731', '07:00:00', 2, 4, 2, 3),
(10, 'LOC 1110', '07:00:00', 1, 5, 3, 1),
(11, 'TEC 7876', '08:00:00', 5, 1, 5, 1),
(12, 'MEM 8092', '08:00:00', 4, 2, 5, 1),
(13, 'BOT 2014', '08:00:00', 3, 3, 1, 3),
(14, 'RAW 0145', '08:00:00', 2, 4, 1, 4),
(15, 'GOO 0009', '08:00:00', 1, 5, 2, 5),
(16, 'ALE 1902', '09:00:00', 5, 1, 4, 2),
(17, 'AWE 2213', '12:40:00', 4, 2, 4, 1),
(18, 'AOE 2346', '13:00:00', 3, 3, 4, 2),
(19, 'ASD 8713', '12:50:00', 2, 4, 2, 3),
(20, 'BOX 5355', '12:50:00', 1, 5, 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `r_id` int(10) UNSIGNED DEFAULT NULL,
  `slot` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'p',
  `route` varchar(255) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `reserved_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `created_at`, `updated_at`, `deleted_at`, `r_id`, `slot`, `status`, `route`, `user_id`, `time`, `reserved_time`) VALUES
(20, '2020-02-10 20:00:53', '2020-02-10 20:02:43', NULL, NULL, 'slot_one', 'R', 'Kasama', 2, '09:00', '2020-02-13 09:28:33'),
(21, '2020-02-10 20:03:39', '2020-02-10 20:04:02', NULL, NULL, 'slot_one', 'R', 'Kasama', 2, '09:00', '2020-02-13 09:28:33'),
(22, '2020-02-11 07:09:48', '2020-02-11 07:10:01', NULL, NULL, 'slot_two', 'R', 'Kasama', 2, '10:00', '2020-02-11 22:00:00'),
(23, '2020-02-11 07:11:44', '2020-02-11 07:11:56', NULL, NULL, 'slot_one', 'R', 'Kasama', 2, '10:00', '2020-02-11 22:00:00'),
(24, '2020-02-11 09:10:08', '2020-02-11 09:10:26', NULL, NULL, 'slot_one', 'R', 'Kasama', 1, '10:00', '2020-02-11 22:00:00'),
(25, '2020-02-11 09:25:25', '2020-02-11 09:25:32', NULL, NULL, 'slot_one', 'A', 'Kasama', 2, '10:00', '2020-02-15 22:00:00'),
(26, '2020-02-11 09:27:08', '2020-02-11 09:27:08', NULL, NULL, 'slot_one', 'A', 'Kasama', 2, '10:00', '2020-02-12 22:00:00'),
(27, '2020-02-11 09:29:48', '2020-02-11 09:29:48', NULL, NULL, 'slot_one', 'A', 'Kabwe', 2, '11:00', '2020-02-12 22:00:00'),
(28, '2020-02-11 09:34:18', '2020-02-11 09:34:52', NULL, NULL, 'slot_two', 'A', 'Kasama', 1, '10:00', '2020-02-15 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `route_id` int(10) NOT NULL,
  `route_destination` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`route_id`, `route_destination`) VALUES
(1, 'Lusaka'),
(2, 'Kabwe'),
(3, 'Kitwe'),
(4, 'Ndola'),
(5, 'Kafue');

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `slot_one` varchar(255) DEFAULT 'open',
  `slot_two` varchar(255) DEFAULT 'open',
  `slot_three` varchar(255) DEFAULT 'open',
  `slot_four` varchar(255) DEFAULT 'open',
  `slot_five` varchar(255) DEFAULT 'open',
  `time` varchar(255) DEFAULT NULL,
  `reservation_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(10) NOT NULL,
  `status_message` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_message`) VALUES
(1, 'On Time'),
(2, 'Boarding '),
(3, 'Canceled'),
(4, 'Delayed'),
(5, 'Departed');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `type_id` int(10) NOT NULL,
  `type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`type_id`, `type_name`) VALUES
(1, 'Aircon'),
(2, 'Economy');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created_at`, `updated_at`, `deleted_at`, `username`, `role`, `email`, `phone`, `password`, `token`) VALUES
(1, '2020-01-15 08:44:36', '2020-01-15 08:44:36', NULL, 'Operator2', 'operator', 'changa@gmail.com', '260971805486', '$2a$10$IqpRGl7Hs7M9LV1pvTomRuj1NV2LIFi9xu9Vhva6voESMP3ZFvamS', ''),
(2, '2020-01-15 16:18:33', '2020-01-15 16:18:33', NULL, 'Operator', 'operator', 'changa@lesa.dc', '', '$2a$10$gLsBrtT0BxqdP8OvQ6vpnuGhSpF9bsK/OuZ1jxlLJFOODf8W/RA66', ''),
(3, '2020-01-15 16:18:43', '2020-01-15 16:18:43', NULL, 'Admin', 'admin', 'changa@lesa.dc', '', '$2a$10$IqpRGl7Hs7M9LV1pvTomRuj1NV2LIFi9xu9Vhva6voESMP3ZFvamS', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Accounts`
--
ALTER TABLE `Accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Arrivals`
--
ALTER TABLE `Arrivals`
  ADD PRIMARY KEY (`bus_id`),
  ADD KEY `FK_acomKey` (`company_id`),
  ADD KEY `FK_arouKey` (`route_id`),
  ADD KEY `FK_abayKey` (`bay_id`),
  ADD KEY `FK_astaKey` (`status_id`);

--
-- Indexes for table `Bay`
--
ALTER TABLE `Bay`
  ADD PRIMARY KEY (`bay_id`);

--
-- Indexes for table `Company`
--
ALTER TABLE `Company`
  ADD PRIMARY KEY (`company_id`),
  ADD KEY `FK_typKey` (`type_id`);

--
-- Indexes for table `Departures`
--
ALTER TABLE `Departures`
  ADD PRIMARY KEY (`bus_id`),
  ADD KEY `FK_comKey` (`company_id`),
  ADD KEY `FK_rouKey` (`route_id`) USING BTREE,
  ADD KEY `FK_bayKey` (`bay_id`),
  ADD KEY `FK_staKey` (`status_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reservations_deleted_at` (`deleted_at`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slots_deleted_at` (`deleted_at`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Accounts`
--
ALTER TABLE `Accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Arrivals`
--
ALTER TABLE `Arrivals`
  MODIFY `bus_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Bay`
--
ALTER TABLE `Bay`
  MODIFY `bay_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Company`
--
ALTER TABLE `Company`
  MODIFY `company_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Departures`
--
ALTER TABLE `Departures`
  MODIFY `bus_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `route_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Arrivals`
--
ALTER TABLE `Arrivals`
  ADD CONSTRAINT `FK_abayKey` FOREIGN KEY (`bay_id`) REFERENCES `Bay` (`bay_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_acomKey` FOREIGN KEY (`company_id`) REFERENCES `Company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_arouKey` FOREIGN KEY (`route_id`) REFERENCES `Route` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_astaKey` FOREIGN KEY (`status_id`) REFERENCES `Status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Company`
--
ALTER TABLE `Company`
  ADD CONSTRAINT `FK_typKey` FOREIGN KEY (`type_id`) REFERENCES `Type` (`type_id`);

--
-- Constraints for table `Departures`
--
ALTER TABLE `Departures`
  ADD CONSTRAINT `FK_bayKey` FOREIGN KEY (`bay_id`) REFERENCES `Bay` (`bay_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_comKey` FOREIGN KEY (`company_id`) REFERENCES `Company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_rouKey` FOREIGN KEY (`route_id`) REFERENCES `Route` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_staKey` FOREIGN KEY (`status_id`) REFERENCES `Status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
