-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 21, 2019 at 11:44 AM
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
-- Database: `demo`
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
(16, '11:00:00', 5, 1, 4, 2),
(17, '11:00:00', 4, 2, 4, 1),
(18, '11:00:00', 3, 3, 4, 2),
(19, '11:00:00', 2, 4, 2, 3),
(20, '11:00:00', 1, 5, 5, 4);

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
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(20) NOT NULL,
  `fname` varchar(35) NOT NULL,
  `lname` varchar(35) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `fname`, `lname`, `phone_number`) VALUES
(1, 'pas', 'senger', '2333333333'),
(2, 'pas', 'senger', '2333333333'),
(3, 'pas', 'senger', '2333333333'),
(4, 'pas', 'senger', '09888'),
(5, 'pas', 'senger', '09888'),
(6, 'pas', 'senger', '09888'),
(7, 'pas', 'senger', '09888'),
(8, 'jack', 'swagger', '78998789798'),
(9, 'jack', 'swagger', '78998789798'),
(10, 'jack', 'swagger', '78998789798'),
(11, 'jack', 'swagger', '78998789798'),
(12, 'jack', 'swagger', '78998789798'),
(13, 'jack', 'swagger', '78998789798'),
(14, 'jack', 'swagger', '78998789798'),
(15, 'jack', 'swagger', '78998789798'),
(16, 'poll', 'senger', '78998789798'),
(17, 'David', 'Tembo', '0987654554'),
(18, 'David', 'Tembo', '0987654554'),
(19, 'Jeff', 'bezos', '09876543223'),
(20, 'Jeff', 'bezos', '09876543223'),
(21, '', '', ''),
(22, 'tom', 'banda', '09777436665'),
(23, '', '', ''),
(24, 'David', 'tembo', '09876543356'),
(25, '', '', ''),
(26, '', '', ''),
(27, 'Lecon', 'kaft', '09876543'),
(28, 'Lecon', 'kaft', '09876543'),
(29, 'Lecon', 'kaft', '09876543'),
(30, 'david', 'tembo ', '0976543212345'),
(31, 'Agent ', 'Wulin', '0912343221'),
(32, 'Agent ', 'Wulin', '0912343221'),
(33, 'kid ', 'cudi', '0876543456'),
(34, 'david', 'tembo', '09876543');

-- --------------------------------------------------------

--
-- Table structure for table `Employee`
--

CREATE TABLE `Employee` (
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Dumping data for table `Employee`
--

INSERT INTO `Employee` (`employee_id`, `employee_name`, `company_id`) VALUES
(1, 'John Doe', 1),
(2, 'Peter Miles', 2),
(3, 'Michael Paul', 3),
(4, 'Kevin Smith', 4),
(5, 'Aaron Jones', 5),
(6, 'Tom Segan', 1),
(7, 'Paul Hall', 2),
(8, 'Charles Norman', 3),
(9, 'Yuri Highbee', 4),
(10, 'Earl Porter', 5);

-- --------------------------------------------------------

--
-- Table structure for table `luggage`
--

CREATE TABLE `luggage` (
  `luggage_id` int(15) NOT NULL,
  `customer_id` varchar(23) NOT NULL,
  `description` varchar(100) NOT NULL,
  `weight` varchar(15) NOT NULL,
  `Destination` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `luggage`
--

INSERT INTO `luggage` (`luggage_id`, `customer_id`, `description`, `weight`, `Destination`) VALUES
(1, '', 'little black bag', '100', 'lusaka'),
(2, '', 'little black bag', '100', 'lusaka'),
(3, '', 'little black bag', '100', 'lusaka'),
(4, '', 'little black bag', '100', 'lusaka'),
(5, '1', 'little black bag', '100', 'lusaka'),
(6, '2', 'bag', '100', 'lusaka'),
(7, '3', 'bag', '100', 'lusaka'),
(8, '4', 'we', '100', 'lusaka'),
(9, '5', 'we', '100', 'lusaka'),
(10, '6', 'we little bag', '100', 'lusaka'),
(11, '7', 'we little bag', '100', 'lusaka'),
(12, '8', 'big li', '200', 'lusaka'),
(13, '9', 'big li', '200', 'lusaka'),
(14, '10', 'big li', '200', 'lusaka'),
(15, '11', 'big li', '200', 'lusaka'),
(16, '12', 'big li', '200', 'lusaka'),
(17, '13', 'big li', '200', 'lusaka'),
(18, '14', 'big li', '200', 'lusaka'),
(19, '15', 'big li', '200', 'lusaka'),
(20, '16', 'the bigg bag', '300', 'kitwe'),
(21, '17', '6 bags ', '200', 'lusaka'),
(22, '18', '6 bags ', '200', 'lusaka'),
(23, '19', '8 bags', '200', 'livingstone '),
(24, '20', '8 bags', '200', 'livingstone '),
(25, '21', '', '', ''),
(26, '22', '1 bag', '200', 'monte'),
(27, '23', '', '', ''),
(28, '24', '1 bag', '300', 'lusaka'),
(29, '25', '', '', ''),
(30, '26', '', '', ''),
(31, '27', '1 bag', '68', 'lusaka'),
(32, '28', '1 bag', '68', 'lusaka'),
(33, '29', '1 bag', '68', 'lusaka'),
(34, '30', '5 bags ', '40', 'lusaka'),
(35, '31', '5 bags', '60', 'kite'),
(36, '32', '5 bags', '60', 'kitwe'),
(37, '33', '7 bags ', '60', 'lusaka'),
(38, '34', '5 bags', '80', 'lusaka');

-- --------------------------------------------------------

--
-- Table structure for table `pricerate`
--

CREATE TABLE `pricerate` (
  `id` int(15) UNSIGNED NOT NULL,
  `priceRate` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `luggage`
--
ALTER TABLE `luggage`
  ADD PRIMARY KEY (`luggage_id`);

--
-- Indexes for table `pricerate`
--
ALTER TABLE `pricerate`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `Employee`
--
ALTER TABLE `Employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `luggage`
--
ALTER TABLE `luggage`
  MODIFY `luggage_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `pricerate`
--
ALTER TABLE `pricerate`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
