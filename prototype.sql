-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 26, 2019 at 03:25 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prototype`
--

-- --------------------------------------------------------

--
-- Table structure for table `Accounts`
--

CREATE TABLE `Accounts` (
  `account_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Accounts`
--

INSERT INTO `Accounts` (`account_id`, `username`, `password`, `email`) VALUES
(1, 'test', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'test@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `Arrivals`
--

CREATE TABLE `Arrivals` (
  `bus_id` int(10) NOT NULL,
  `time` time NOT NULL,
  `route_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `bay_id` int(10) NOT NULL,
  `status_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Arrivals`
--

INSERT INTO `Arrivals` (`bus_id`, `time`, `route_id`, `company_id`, `bay_id`, `status_id`) VALUES
(1, '08:00:00', 4, 2, 5, 1),
(2, '09:20:00', 3, 1, 2, 1),
(3, '08:30:00', 2, 5, 1, 1),
(4, '10:00:00', 5, 4, 4, 1),
(5, '11:00:00', 1, 3, 2, 4),
(6, '09:35:00', 4, 1, 3, 1),
(7, '14:00:00', 2, 2, 4, 1),
(8, '14:30:00', 1, 4, 2, 1),
(9, '14:50:00', 3, 3, 5, 1),
(10, '16:00:00', 5, 1, 3, 1);

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
-- Table structure for table `Bus`
--

CREATE TABLE `Bus` (
  `bus_id` int(10) NOT NULL,
  `time` time NOT NULL,
  `route_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `bay_id` int(10) NOT NULL,
  `status_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Bus`
--

INSERT INTO `Bus` (`bus_id`, `time`, `route_id`, `company_id`, `bay_id`, `status_id`) VALUES
(1, '06:00:00', 5, 1, 1, 5),
(2, '06:00:00', 4, 2, 2, 5),
(3, '06:00:00', 3, 3, 3, 5),
(4, '06:00:00', 2, 4, 4, 5),
(5, '06:00:00', 1, 5, 5, 5),
(6, '07:00:00', 5, 1, 3, 3),
(7, '07:00:00', 4, 2, 2, 4),
(8, '07:00:00', 3, 3, 1, 3),
(9, '07:00:00', 2, 4, 2, 3),
(10, '07:00:00', 1, 5, 3, 1),
(11, '08:00:00', 5, 1, 5, 1),
(12, '08:00:00', 4, 2, 5, 1),
(13, '08:00:00', 3, 3, 1, 3),
(14, '08:00:00', 2, 4, 1, 4),
(15, '08:00:00', 1, 5, 2, 5),
(16, '09:00:00', 5, 1, 4, 2),
(17, '09:00:00', 4, 2, 4, 1),
(18, '09:00:00', 3, 3, 4, 2),
(19, '09:00:00', 2, 4, 2, 3),
(20, '09:00:00', 1, 5, 5, 4);

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
-- Table structure for table `Route`
--

CREATE TABLE `Route` (
  `route_id` int(10) NOT NULL,
  `route_destination` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Route`
--

INSERT INTO `Route` (`route_id`, `route_destination`) VALUES
(1, 'Lusaka'),
(2, 'Kabwe'),
(3, 'Kitwe'),
(4, 'Ndola'),
(5, 'Kafue');

-- --------------------------------------------------------

--
-- Table structure for table `Status`
--

CREATE TABLE `Status` (
  `status_id` int(10) NOT NULL,
  `status_message` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Status`
--

INSERT INTO `Status` (`status_id`, `status_message`) VALUES
(1, 'On Time'),
(2, 'Boarding '),
(3, 'Canceled'),
(4, 'Delayed'),
(5, 'Departed');

-- --------------------------------------------------------

--
-- Table structure for table `Type`
--

CREATE TABLE `Type` (
  `type_id` int(10) NOT NULL,
  `type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Type`
--

INSERT INTO `Type` (`type_id`, `type_name`) VALUES
(1, 'Aircon'),
(2, 'Economy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Accounts`
--
ALTER TABLE `Accounts`
  ADD PRIMARY KEY (`account_id`);

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
-- Indexes for table `Bus`
--
ALTER TABLE `Bus`
  ADD PRIMARY KEY (`bus_id`),
  ADD KEY `FK_comKey` (`company_id`),
  ADD KEY `FK_rouKey` (`route_id`) USING BTREE,
  ADD KEY `FK_bayKey` (`bay_id`),
  ADD KEY `FK_staKey` (`status_id`);

--
-- Indexes for table `Company`
--
ALTER TABLE `Company`
  ADD PRIMARY KEY (`company_id`),
  ADD KEY `FK_typKey` (`type_id`);

--
-- Indexes for table `Route`
--
ALTER TABLE `Route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `Status`
--
ALTER TABLE `Status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `Type`
--
ALTER TABLE `Type`
  ADD PRIMARY KEY (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Accounts`
--
ALTER TABLE `Accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
-- AUTO_INCREMENT for table `Bus`
--
ALTER TABLE `Bus`
  MODIFY `bus_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `Company`
--
ALTER TABLE `Company`
  MODIFY `company_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Route`
--
ALTER TABLE `Route`
  MODIFY `route_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Status`
--
ALTER TABLE `Status`
  MODIFY `status_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Type`
--
ALTER TABLE `Type`
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
-- Constraints for table `Bus`
--
ALTER TABLE `Bus`
  ADD CONSTRAINT `FK_bayKey` FOREIGN KEY (`bay_id`) REFERENCES `Bay` (`bay_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_comKey` FOREIGN KEY (`company_id`) REFERENCES `Company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_rouKey` FOREIGN KEY (`route_id`) REFERENCES `Route` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_staKey` FOREIGN KEY (`status_id`) REFERENCES `Status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Company`
--
ALTER TABLE `Company`
  ADD CONSTRAINT `FK_typKey` FOREIGN KEY (`type_id`) REFERENCES `Type` (`type_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
