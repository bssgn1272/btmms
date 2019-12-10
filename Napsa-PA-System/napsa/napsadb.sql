-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2019 at 03:59 PM
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
-- Database: `napsadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

use napsadb;

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `email`) VALUES
(1, 'test', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'test@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `id` int(11) NOT NULL,
  `company` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `seats` int(11) NOT NULL,
  `Bay` int(11) NOT NULL,
  `Time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`id`, `company`, `type`, `seats`, `Bay`, `Time`) VALUES
(1, 'Mazhandu', 'Aircon', 66, 2, '06:30:00'),
(2, 'Power Tools', 'Aircon', 50, 6, '06:30:00'),
(3, 'Reno', 'Economy', 20, 12, '06:30:00'),
(4, 'RoadWay', 'Economy', 20, 20, '06:30:00'),
(5, 'Jordan', 'Economy ', 60, 14, '06:30:00'),
(9, 'Mazhandu', 'Aircon', 66, 2, '05:30:00'),
(10, 'Power Tools', 'Aircon', 50, 6, '05:30:00'),
(11, 'Reno', 'Economy', 20, 12, '05:30:00'),
(12, 'RoadWay', 'Economy', 20, 20, '05:30:00'),
(13, 'Jordan', 'Economy ', 60, 14, '05:30:00'),
(14, 'Mazhandu', 'Aircon', 66, 2, '07:30:00'),
(15, 'Power Tools', 'Aircon', 50, 6, '07:30:00'),
(16, 'Reno', 'Economy', 20, 12, '07:30:00'),
(17, 'RoadWay', 'Economy', 20, 20, '07:30:00'),
(18, 'Jordan', 'Economy ', 60, 14, '07:30:00'),
(19, 'Mazhandu', 'Aircon', 66, 2, '11:30:00'),
(20, 'Power Tools', 'Aircon', 50, 6, '11:30:00'),
(21, 'Reno', 'Economy', 20, 12, '11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `city` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `city`) VALUES
(1, 'Lusaka'),
(2, 'Livingstone'),
(3, 'Kabwe'),
(4, 'Ndola'),
(5, 'Kitwe');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `company` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company`) VALUES
(1, 'Mazhandu'),
(2, 'Power Tools'),
(3, 'Reno'),
(4, 'RoadWay');

-- --------------------------------------------------------

--
-- Table structure for table `luggage`
--

CREATE TABLE `luggage` (
  `luggage_id` int(11) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `description` varchar(300) NOT NULL,
  `weight` varchar(15) NOT NULL,
  `cost` varchar(20) NOT NULL,
  `reciient_id` varchar(25) NOT NULL,
  `Destination` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `luggage`
--

INSERT INTO `luggage` (`luggage_id`, `fname`, `lname`, `description`, `weight`, `cost`, `reciient_id`, `Destination`) VALUES
(10007, 'test', 'test', 'testestestesrstrstrstrstrstrstrstrstrstrstestesttestestsetststeststeststeses', '500', '1000', 'test', 'livingstone'),
(2, 'David', 'tembo', 'bags ', '500', '1600', 'zulu', 'livingstone'),
(1, 'David', 'tembo', '2 bags 3 hand luggage', '300kg', '1200', 'Denise', 'kite'),
(3, 'chad', 'nashberg', '14 bags ', '700kg', '2000zmk', 'Alice ', 'lusaka'),
(3, 'chad', 'nashberg', '14 bags ', '700kg', '2000zmk', 'Alice ', 'lusaka'),
(8675, 'daditest', 'test', 'bags 5', '600kg', '90', '09777765445', 'lusaka'),
(8600, 'CHANDA', 'test', 'bags 5', '600kg', '90', '09777765445', 'UK'),
(3456, 'CHANDA01', 'test1', 'bags 7', '800', '9000', '09777765445', 'KITWE'),
(4765, 'Bat', 'Man', '3 black and 2 very very very dark grey bags', '600', '70', '07534245343', 'mansa '),
(4765, 'Bat', 'Man', '3 black and 2 very very very dark grey bags', '600', '70', '07534245343', 'mansa '),
(4765, 'Bat', 'Man', '3 black and 2 very very very dark grey bags', '600', '70', '07534245343', 'mansa '),
(4765, 'Bat', 'Man', '3 black and 2 very very very dark grey bags', '600', '70', '07534245343', 'mansa ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
