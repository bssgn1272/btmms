-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2020 at 09:59 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `napsa_market_sales`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_config`
--

CREATE TABLE `api_config` (
  `api_config_id` int(11) NOT NULL,
  `application_name` varchar(200) NOT NULL,
  `api_key` varchar(500) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `api_config`
--

INSERT INTO `api_config` (`api_config_id`, `application_name`, `api_key`, `date_created`, `date_modified`) VALUES
(1, 'test', '3aa0fa07553c7e3a86f46d3b35234620', '2019-12-17 15:03:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `bus_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `bus_reg` varchar(10) NOT NULL,
  `seat_layout` varchar(10) DEFAULT NULL,
  `total_seats` int(11) DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`bus_id`, `company_id`, `bus_reg`, `seat_layout`, `total_seats`, `activated`) VALUES
(1, 2, 'ALT3112', NULL, 50, 1),
(2, 2, 'ACL9617', NULL, 30, 1),
(3, 2, 'ABM967', NULL, 3, 1),
(4, 2, 'ABG7402', NULL, 0, 1),
(5, 2, 'ABM6210', NULL, 5, 1),
(6, 2, 'ADD5737', NULL, 0, 1),
(7, 2, 'ADD5736', NULL, 56, 1),
(8, 2, 'ADD3766', NULL, 0, 1),
(9, 2, 'ACX7617', NULL, 47, 1),
(10, 2, 'ACX7625', NULL, 0, 1),
(11, 2, 'ACV1358', NULL, 0, 1),
(12, 2, 'ACP441', NULL, 0, 1),
(13, 2, 'ACP440', NULL, 0, 1),
(14, 2, 'ACV1359', NULL, 0, 1),
(15, 2, 'BCA4079', NULL, 0, 1),
(16, 2, 'GML11', NULL, 0, 1),
(17, 2, 'BCA4082', NULL, 0, 1),
(18, 2, 'BCA4081', NULL, 0, 1),
(19, 2, 'BCA4080', NULL, 0, 1),
(20, 2, 'ABT2419', NULL, 0, 1),
(21, 2, 'ALT2165', NULL, 0, 1),
(22, 2, 'BCA5092', NULL, 0, 1),
(23, 2, 'ALT1807', NULL, 0, 1),
(24, 2, 'ABV2424', NULL, 0, 1),
(25, 2, 'BCA7847', NULL, 0, 1),
(26, 2, 'BCA7848', NULL, 0, 1),
(27, 2, 'BCA7849', NULL, 0, 1),
(28, 2, 'BCA7850', NULL, 0, 1),
(29, 2, 'BCA7651', NULL, 0, 1),
(30, 2, 'BCA7852', NULL, 0, 1),
(31, 2, 'ACL855', NULL, 0, 1),
(32, 2, 'ABE77', NULL, 0, 1),
(33, 2, 'ACP438', NULL, 0, 1),
(34, 2, 'ADE1271', NULL, 0, 1),
(35, 2, 'ACR4012', NULL, 0, 1),
(36, 2, 'ACR6314', NULL, 0, 1),
(37, 2, 'ACP445', NULL, 0, 1),
(38, 2, 'ADE1254', NULL, 0, 1),
(39, 2, 'ADE1272', NULL, 0, 1),
(40, 2, 'ACP439', NULL, 0, 1),
(41, 2, 'AHB42', NULL, 0, 1),
(42, 2, 'BAF6529', NULL, 0, 1),
(43, 2, 'AIB5615', NULL, 0, 1),
(44, 2, 'AIB5617', NULL, 0, 1),
(45, 2, 'AIB5614', NULL, 0, 1),
(46, 2, 'AIB5613', NULL, 0, 1),
(47, 2, 'AIB5618', NULL, 0, 1),
(48, 2, 'AIB5619', NULL, 0, 1),
(49, 2, 'AIB5620', NULL, 0, 1),
(50, 2, 'ACK143', NULL, 0, 1),
(51, 2, 'ALT2164', NULL, 0, 1),
(52, 2, 'ADD1476', NULL, 0, 1),
(53, 2, 'ADD1477', NULL, 0, 1),
(54, 2, 'ACR6313', NULL, 0, 1),
(55, 2, 'ACR6311', NULL, 0, 1),
(56, 2, 'ACK8940', NULL, 0, 1),
(57, 2, 'ACK8941', NULL, 0, 1),
(58, 2, 'AJB2827', NULL, 0, 1),
(59, 2, 'ADD4477', NULL, 0, 1),
(60, 2, 'ABG6158', NULL, 0, 1),
(61, 2, 'ALF4810', NULL, 0, 1),
(62, 2, 'ACX7616', NULL, 0, 1),
(63, 2, 'ACX7615', NULL, 0, 1),
(64, 2, 'ACX7614', NULL, 0, 1),
(65, 2, 'ABL3093', NULL, 0, 1),
(66, 2, 'BCA7853', NULL, 0, 1),
(67, 3, 'ABC1234', NULL, 0, 1),
(68, 3, 'CBA1234', NULL, 0, 1),
(69, 3, 'ABM9644', NULL, 0, 1),
(70, 3, 'ALX9671', NULL, 0, 1),
(71, 3, 'ALK9074', NULL, 0, 1),
(72, 3, 'UGL10', NULL, 0, 1),
(73, 3, 'MFB4', NULL, 0, 1),
(74, 3, 'ALC642', NULL, 0, 1),
(75, 3, 'ALH1009ZM', NULL, 0, 1),
(76, 3, 'BAF4883ZM', NULL, 0, 1),
(77, 3, 'BAF6593ZM', NULL, 0, 1),
(78, 3, 'ALP9241', NULL, 0, 1),
(79, 3, 'ALH1788', NULL, 0, 1),
(80, 3, 'BAB7084', NULL, 0, 1),
(81, 3, 'ALZ4559', NULL, 0, 1),
(82, 3, 'BAA9707', NULL, 0, 1),
(83, 3, 'ALH1010', NULL, 0, 1),
(84, 3, 'BAB9705', NULL, 0, 1),
(85, 3, 'ALZ4560', NULL, 0, 1),
(86, 3, 'ALV6421', NULL, 0, 1),
(87, 3, 'ABT8742', NULL, 0, 1),
(88, 3, 'ABZ6577', NULL, 0, 1),
(89, 3, 'ALZ4562', NULL, 0, 1),
(90, 3, 'ALX5613', NULL, 0, 1),
(91, 3, 'ALV6422', NULL, 0, 1),
(92, 3, 'ALP772', NULL, 0, 1),
(93, 3, 'ABV8322', NULL, 0, 1),
(94, 3, 'ALZ3161', NULL, 0, 1),
(95, 3, 'ALV9705', NULL, 0, 1),
(96, 3, 'ALP9124', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bus_companies`
--

CREATE TABLE `bus_companies` (
  `company_id` int(11) NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `customer_service_num_one` varchar(50) DEFAULT NULL,
  `customer_service_num_two` varchar(50) DEFAULT NULL,
  `observation_num` varchar(50) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bus_companies`
--

INSERT INTO `bus_companies` (`company_id`, `logo`, `name`, `customer_service_num_one`, `customer_service_num_two`, `observation_num`, `date_created`, `date_modified`) VALUES
(1, NULL, 'Jordan Motors', NULL, NULL, NULL, '2019-02-07 13:39:02', '2019-11-21 12:22:07'),
(2, 'PowerTools.jpeg', 'PowerTools', '09655880202', '02122217118', '09666785117', '2019-02-07 13:39:15', '2019-07-02 09:48:12'),
(3, 'Mazhandu.jpeg', 'Mazhandu', NULL, NULL, NULL, '2019-02-07 13:39:23', '2019-02-15 12:43:41'),
(4, NULL, 'Chembe', NULL, NULL, NULL, '2019-02-07 13:39:36', NULL),
(5, NULL, 'test', NULL, NULL, NULL, '2019-06-11 16:02:57', NULL),
(6, NULL, 'Euro ', NULL, NULL, NULL, '2019-06-17 08:52:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bus_departure_times`
--

CREATE TABLE `bus_departure_times` (
  `bus_departure_time_id` int(255) NOT NULL,
  `bus_departure_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bus_departure_times`
--

INSERT INTO `bus_departure_times` (`bus_departure_time_id`, `bus_departure_time`) VALUES
(1, '05:30:00'),
(2, '06:30:00'),
(3, '07:30:00'),
(4, '08:30:00'),
(5, '09:30:00'),
(6, '10:30:00'),
(7, '11:30:00'),
(8, '12:30:00'),
(9, '13:30:00'),
(10, '14:30:00'),
(11, '15:30:00'),
(12, '16:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `user_id`, `file`) VALUES
(1, 1, 'InbPkMJBkrKzJ-6lah5wrGRczUHPBF2r.png'),
(2, 5, 'Mfx2_oSH7kGyo25YmUbScdSG8k7dkFgq.png');

-- --------------------------------------------------------

--
-- Table structure for table `market_charges`
--

CREATE TABLE `market_charges` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `market_charges`
--

INSERT INTO `market_charges` (`id`, `name`, `amount`, `status`) VALUES
(1, 'Market levi', '2', 1),
(2, 'Other Market charge', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `market_notifications`
--

CREATE TABLE `market_notifications` (
  `id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL DEFAULT '1',
  `message` varchar(45) NOT NULL,
  `recipients` text,
  `status` int(11) NOT NULL,
  `notification_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `market_notifications`
--

INSERT INTO `market_notifications` (`id`, `type`, `message`, `recipients`, `status`, `notification_date`) VALUES
(1, '0', 'test message', '098xxxx,0976xxxxx', 0, '2019-11-15'),
(3, '1', 'Test message', '', 0, '2019-11-16');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'Manage Roles', 'Can create,edit, view and delete user roles. '),
(2, 'View Roles', NULL),
(3, 'Manage Users', 'Can manage system users'),
(4, 'View Users', 'Can view system users'),
(5, 'Manage products', 'Can create products per category'),
(6, 'View products', 'Can view products per category'),
(7, 'Manage product categories', 'Can create product categories'),
(8, 'View product categories', 'Can view product categories'),
(9, 'Manage permissions', NULL),
(10, 'View permissions', NULL),
(11, 'Manage product measures', 'Can create,update, view and delete product measures'),
(12, 'View product measures', 'Can view product measures'),
(13, 'Manage token procuments', ''),
(14, 'View token procuments', ''),
(15, 'Manage traders', ''),
(16, 'View traders', ''),
(17, 'Manage marketeer products', NULL),
(18, 'View marketeer products', NULL),
(19, 'Manage token redemptions', ''),
(20, 'View token redemptions', ''),
(21, 'View Trader sales', ''),
(22, 'View Trader sales', ''),
(23, 'Manage market charges', ''),
(24, 'View market charges', ''),
(25, 'Manage market nofications', ''),
(26, 'View market nofications', '');

-- --------------------------------------------------------

--
-- Table structure for table `permission_to_roles`
--

CREATE TABLE `permission_to_roles` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission_to_roles`
--

INSERT INTO `permission_to_roles` (`id`, `role_id`, `permission_id`) VALUES
(264, 2, 16),
(265, 2, 22),
(266, 1, 1),
(267, 1, 2),
(268, 1, 3),
(269, 1, 4),
(270, 1, 5),
(271, 1, 6),
(272, 1, 7),
(273, 1, 8),
(274, 1, 9),
(275, 1, 10),
(276, 1, 11),
(277, 1, 12),
(278, 1, 13),
(279, 1, 14),
(280, 1, 15),
(281, 1, 16),
(282, 1, 17),
(283, 1, 18),
(284, 1, 19),
(285, 1, 20),
(286, 1, 21),
(287, 1, 22),
(288, 1, 23),
(289, 1, 24),
(290, 1, 25),
(291, 1, 26);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `name`, `description`, `date_created`, `date_updated`, `created_by`, `updated_by`) VALUES
(1, 'Admin', NULL, '2019-11-05 00:00:00', '2019-11-13 14:04:23', 1, 1),
(2, 'Market Administrator', NULL, '2019-11-05 00:00:00', '2019-11-13 13:14:03', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `route_id` int(100) NOT NULL,
  `company_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `origin` varchar(20) NOT NULL,
  `destination` varchar(20) NOT NULL,
  `price` double NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`route_id`, `company_id`, `station_id`, `name`, `origin`, `destination`, `price`, `date_created`, `date_modified`) VALUES
(1, 2, 1, 'LSK-KTW', 'Lusaka', 'Kitwe', 150, '2018-06-21 11:14:02', '2019-07-03 09:45:00'),
(2, 2, 1, 'LSK-NDL', 'Lusaka', 'Ndola', 115, '2018-06-21 11:14:37', NULL),
(3, 2, 1, 'LSK-KPR', 'Lusaka', 'Kapiri', 110, '2018-06-21 11:15:16', NULL),
(4, 2, 1, 'LSK-KBW', 'Lusaka', 'Kabwe', 105, '2018-06-21 11:15:47', NULL),
(5, 2, 3, 'KTW-LSK', 'Kitwe', 'Lusaka', 150, '2018-07-11 09:00:12', '2019-11-19 08:35:15'),
(6, 2, 3, 'KTW-KBW', 'Kitwe', 'Kabwe', 110, '2018-07-11 09:00:26', '2019-11-19 08:34:58'),
(7, 2, 3, 'KTW-KPR', 'Kitwe', 'Kapiri', 105, '2018-07-11 09:00:49', '2019-11-19 08:35:02'),
(8, 2, 3, 'KTW-NDL', 'Kitwe', 'Ndola', 30, '2018-07-11 09:01:32', '2019-11-19 08:35:08'),
(9, 2, 2, 'NDL-LSK', 'Ndola', 'Lusaka', 110, '2018-08-02 09:26:38', '2019-11-19 08:34:30'),
(10, 2, 2, 'NDL-KBW', 'Ndola', 'Kabwe', 100, '2018-08-02 09:26:53', '2019-11-19 08:34:35'),
(11, 2, 2, 'NDL-KPR', 'Ndola', 'Kapiri', 90, '2018-08-02 09:27:17', '2019-11-19 08:34:40'),
(12, 2, 2, 'NDL-KTW', 'Ndola', 'Kitwe', 30, '2018-08-22 12:35:31', '2019-11-19 08:34:52'),
(14, 3, 1, 'LSK-KTW', 'Lusaka', 'Kitwe', 150, '2018-09-17 08:22:15', '2019-02-15 12:43:59'),
(15, 3, 1, 'LSK-NDL', 'Lusaka', 'Ndola', 120, '2018-09-17 08:22:47', '2019-02-15 12:44:03'),
(16, 3, 1, 'LSK-LST', 'Lusaka', 'Livingstone', 130, '2018-09-17 08:26:36', '2019-06-17 09:14:20'),
(17, 6, 1, 'LSK-KTW', 'Lusaka', 'Kitwe', 120, '2019-06-17 10:54:26', NULL),
(18, 6, 5, 'KTW-LSK', 'Kitwe', 'Lusaka', 120, '2019-06-17 10:55:28', '2019-06-17 08:56:51'),
(19, 2, 7, 'KSM-LSK', 'Kasama', 'Lusaka', 120, '2019-06-26 08:33:27', NULL),
(20, 2, 10, 'LSK-SOL', 'Lusaka', 'Solwezi', 200, '2019-06-26 06:34:24', '2019-06-26 06:34:30');

-- --------------------------------------------------------

--
-- Table structure for table `routes_times`
--

CREATE TABLE `routes_times` (
  `routes_times_id` int(255) NOT NULL,
  `route_id` int(255) NOT NULL,
  `bus_departure_time_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routes_times`
--

INSERT INTO `routes_times` (`routes_times_id`, `route_id`, `bus_departure_time_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1),
(15, 15, 1),
(16, 16, 1),
(17, 17, 1),
(18, 18, 1),
(32, 1, 2),
(33, 2, 2),
(34, 3, 2),
(35, 4, 2),
(36, 5, 2),
(37, 6, 2),
(38, 7, 2),
(39, 8, 2),
(40, 9, 2),
(41, 10, 2),
(42, 11, 2),
(43, 12, 2),
(44, 13, 2),
(45, 14, 2),
(46, 15, 2),
(47, 16, 2),
(48, 17, 2),
(49, 18, 2),
(63, 1, 3),
(64, 2, 3),
(65, 3, 3),
(66, 4, 3),
(67, 5, 3),
(68, 6, 3),
(69, 7, 3),
(70, 8, 3),
(71, 9, 3),
(72, 10, 3),
(73, 11, 3),
(74, 12, 3),
(75, 13, 3),
(76, 14, 3),
(77, 15, 3),
(78, 16, 3),
(79, 17, 3),
(80, 18, 3),
(94, 1, 4),
(95, 2, 4),
(96, 3, 4),
(97, 4, 4),
(98, 5, 4),
(99, 6, 4),
(100, 7, 4),
(101, 8, 4),
(102, 9, 4),
(103, 10, 4),
(104, 11, 4),
(105, 12, 4),
(106, 13, 4),
(107, 14, 4),
(108, 15, 4),
(109, 16, 4),
(110, 17, 4),
(111, 18, 4),
(125, 1, 5),
(126, 2, 5),
(127, 3, 5),
(128, 4, 5),
(129, 5, 5),
(130, 6, 5),
(131, 7, 5),
(132, 8, 5),
(133, 9, 5),
(134, 10, 5),
(135, 11, 5),
(136, 12, 5),
(137, 13, 5),
(138, 14, 5),
(139, 15, 5),
(140, 16, 5),
(141, 17, 5),
(142, 18, 5),
(156, 1, 6),
(157, 2, 6),
(158, 3, 6),
(159, 4, 6),
(160, 5, 6),
(161, 6, 6),
(162, 7, 6),
(163, 8, 6),
(164, 9, 6),
(165, 10, 6),
(166, 11, 6),
(167, 12, 6),
(168, 13, 6),
(169, 14, 6),
(170, 15, 6),
(171, 16, 6),
(172, 17, 6),
(173, 18, 6),
(187, 1, 7),
(188, 2, 7),
(189, 3, 7),
(190, 4, 7),
(191, 5, 7),
(192, 6, 7),
(193, 7, 7),
(194, 8, 7),
(195, 9, 7),
(196, 10, 7),
(197, 11, 7),
(198, 12, 7),
(199, 13, 7),
(200, 14, 7),
(201, 15, 7),
(202, 16, 7),
(203, 17, 7),
(204, 18, 7),
(218, 1, 8),
(219, 2, 8),
(220, 3, 8),
(221, 4, 8),
(222, 5, 8),
(223, 6, 8),
(224, 7, 8),
(225, 8, 8),
(226, 9, 8),
(227, 10, 8),
(228, 11, 8),
(229, 12, 8),
(230, 13, 8),
(231, 14, 8),
(232, 15, 8),
(233, 16, 8),
(234, 17, 8),
(235, 18, 8),
(249, 1, 9),
(250, 2, 9),
(251, 3, 9),
(252, 4, 9),
(253, 5, 9),
(254, 6, 9),
(255, 7, 9),
(256, 8, 9),
(257, 9, 9),
(258, 10, 9),
(259, 11, 9),
(260, 12, 9),
(261, 13, 9),
(262, 14, 9),
(263, 15, 9),
(264, 16, 9),
(265, 17, 9),
(266, 18, 9),
(280, 1, 10),
(281, 2, 10),
(282, 3, 10),
(283, 4, 10),
(284, 5, 10),
(285, 6, 10),
(286, 7, 10),
(287, 8, 10),
(288, 9, 10),
(289, 10, 10),
(290, 11, 10),
(291, 12, 10),
(292, 13, 10),
(293, 14, 10),
(294, 15, 10),
(295, 16, 10),
(296, 17, 10),
(297, 18, 10),
(311, 1, 11),
(312, 2, 11),
(313, 3, 11),
(314, 4, 11),
(315, 5, 11),
(316, 6, 11),
(317, 7, 11),
(318, 8, 11),
(319, 9, 11),
(320, 10, 11),
(321, 11, 11),
(322, 12, 11),
(323, 13, 11),
(324, 14, 11),
(325, 15, 11),
(326, 16, 11),
(327, 17, 11),
(328, 18, 11),
(342, 1, 12),
(343, 2, 12),
(344, 3, 12),
(345, 4, 12),
(346, 5, 12),
(347, 6, 12),
(348, 7, 12),
(349, 8, 12),
(350, 9, 12),
(351, 10, 12),
(352, 11, 12),
(353, 12, 12),
(354, 13, 12),
(355, 14, 12),
(356, 15, 12),
(357, 16, 12),
(358, 17, 12),
(359, 18, 12);

-- --------------------------------------------------------

--
-- Table structure for table `sms_history`
--

CREATE TABLE `sms_history` (
  `sms_history_id` int(11) NOT NULL,
  `alphanumeric` varchar(20) NOT NULL,
  `recipient_number` varchar(20) NOT NULL,
  `cost` varchar(10) NOT NULL,
  `messageId` varchar(50) NOT NULL,
  `messageParts` int(11) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `statusCode` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `traders`
--

CREATE TABLE `traders` (
  `trader_id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `nrc` varchar(50) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `QR_code` varchar(255) DEFAULT NULL,
  `token_balance` double(10,2) DEFAULT '0.00',
  `account_number` varchar(45) NOT NULL,
  `dob` date DEFAULT NULL,
  `stand_no` varchar(50) DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `traders`
--

INSERT INTO `traders` (`trader_id`, `role`, `firstname`, `lastname`, `nrc`, `gender`, `mobile_number`, `QR_code`, `token_balance`, `account_number`, `dob`, `stand_no`, `image`, `password`, `auth_key`, `verification_code`, `password_reset_token`, `status`, `created_by`, `updated_by`, `date_created`, `date_updated`) VALUES
(15, 'buyer', 'Chulu', 'Francs chishala', '10001', 'Male', '260978981572', 'trd0978981572', 100.00, '1234567', '1980-02-29', '24', NULL, '$2y$13$TQzSOdgNgzZsgV9NFGSXPO65vb3n.5j2lum7mxKQig9KvdLCFr.0u', 'Ba98OAJyTr0tByl3bgaL9I6eb_-g7UZJ', '1016', NULL, 0, NULL, NULL, '2019-11-07 17:56:11', '2019-12-25 06:09:12'),
(16, NULL, 'Chimuka', 'Moonde', '10002', 'Male', '260973297687', 'trd0978981571', 84.00, '1872726', '1982-08-20', '3', NULL, '$13$TQzSOdgNgzZsgV9NFGSXPO65vb3n.5j2lum7mxKQig9KvdLCFr.0u', 'Ba98OAJyTr0tByl3bgaL9I6eb_-g7UZJ', '1029', NULL, 1, NULL, NULL, '2019-11-13 14:12:41', '2019-12-25 06:09:11'),
(17, NULL, 'Simon', 'Chiwamba', '10003', 'Female', '260978981571', 'trd0960000000', 0.00, '198988', '1978-09-23', '45', NULL, '$13$TQzSOdgNgzZsgV9NFGSXPO65vb3n.5j2lum7mxKQig9KvdLCFr.0u', 'Ba98OAJyTr0tByl3bgaL9I6eb_-g7UZJ', '8787', NULL, 1, NULL, NULL, '2019-11-13 14:14:01', '2019-12-14 05:38:01');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_logs`
--

CREATE TABLE `transaction_logs` (
  `transaction_log_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `transaction_type_id` int(11) NOT NULL,
  `request_message` text,
  `request_time` datetime DEFAULT NULL,
  `response_message` text,
  `response_time` datetime DEFAULT NULL,
  `status_code` int(11) DEFAULT NULL,
  `status_description` varchar(300) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_summaries`
--

CREATE TABLE `transaction_summaries` (
  `cart_id` int(11) NOT NULL,
  `transaction_type_id` int(11) NOT NULL,
  `external_trans_id` varchar(255) DEFAULT NULL,
  `probase_status_code` int(11) NOT NULL DEFAULT '0',
  `probase_status_description` varchar(300) DEFAULT 'Sync Pending',
  `error_status_code` int(11) NOT NULL DEFAULT '0',
  `error_status_description` varchar(300) DEFAULT 'Pending',
  `status_code` int(11) DEFAULT NULL,
  `status_description` varchar(255) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `seller_name` varchar(100) DEFAULT NULL,
  `seller_mobile_number` varchar(20) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_mobile_number` varchar(20) DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `device_serial` varchar(200) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `credit_msg` varchar(200) DEFAULT NULL,
  `credit_reference` varchar(200) DEFAULT NULL,
  `credit_code` varchar(10) DEFAULT NULL,
  `credit_system_code` varchar(10) NOT NULL,
  `credit_transactionID` varchar(200) NOT NULL,
  `debit_msg` varchar(200) DEFAULT NULL,
  `debit_reference` varchar(200) DEFAULT NULL,
  `debit_code` varchar(10) DEFAULT NULL,
  `callback_msg` varchar(200) DEFAULT NULL,
  `callback_reference` varchar(200) DEFAULT NULL,
  `callback_code` varchar(10) DEFAULT NULL,
  `callback_system_code` varchar(10) DEFAULT NULL,
  `callback_transactionID` varchar(200) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaction_summaries`
--

INSERT INTO `transaction_summaries` (`cart_id`, `transaction_type_id`, `external_trans_id`, `probase_status_code`, `probase_status_description`, `error_status_code`, `error_status_description`, `status_code`, `status_description`, `seller_id`, `seller_name`, `seller_mobile_number`, `buyer_id`, `buyer_name`, `buyer_mobile_number`, `amount`, `device_serial`, `transaction_date`, `credit_msg`, `credit_reference`, `credit_code`, `credit_system_code`, `credit_transactionID`, `debit_msg`, `debit_reference`, `debit_code`, `callback_msg`, `callback_reference`, `callback_code`, `callback_system_code`, `callback_transactionID`, `date_created`, `date_modified`) VALUES
(1, 0, '52652542', 0, NULL, 0, NULL, 100, 'Transaction was successful', 15, NULL, NULL, 0, NULL, '097898900', 0.00, '', '2019-11-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-11-13 14:40:25', '2019-11-13 14:45:16'),
(2, 0, '52652543', 0, NULL, 0, NULL, 100, 'Transaction was successful', 16, NULL, NULL, 0, NULL, '098776766', 0.00, '', '0000-00-00 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-11-13 14:49:56', NULL),
(3, 0, '45555555', 0, NULL, 0, NULL, 100, 'Transaction was successful', 17, NULL, NULL, 0, NULL, '071000000', 0.00, '', '0000-00-00 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-11-13 14:49:56', NULL),
(4, 1, NULL, 0, 'Sync Pending', 0, NULL, NULL, NULL, 16, NULL, '0973297687', 17, NULL, '0978981572', 0.00, 'SAMSUNG-J5PRO', '2019-12-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-13 13:06:13', NULL),
(20200000, 1, NULL, 0, 'Sync Pending', 0, NULL, NULL, NULL, 16, NULL, '0973297687', 17, NULL, '0978981572', 0.00, 'SAMSUNG-J5PRO', '2019-12-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-14 07:20:54', NULL),
(20200001, 1, NULL, 0, 'Sync Pending', 0, NULL, NULL, NULL, 16, NULL, '0973297687', 17, NULL, '0978981572', 0.00, 'SAMSUNG-J5PRO', '2019-12-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-14 07:36:08', NULL),
(20200002, 1, NULL, 0, 'Sync Pending', 0, NULL, NULL, NULL, 16, NULL, '0973297687', 17, NULL, '0978981572', 0.00, 'SAMSUNG-J5PRO', '2019-12-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-14 07:36:32', NULL),
(20200003, 1, NULL, 0, 'Sync Pending', 0, NULL, NULL, NULL, 16, NULL, '0973297687', 17, NULL, '0978981572', 0.00, 'SAMSUNG-J5PRO', '2019-12-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-14 07:38:01', NULL),
(20200004, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '0973297687', 16, 'Chimuka Moonde', '0978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-17 14:56:45', NULL),
(20200005, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '0973297687', 16, 'Chimuka Moonde', '0978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-13 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-17 14:57:54', NULL),
(20200006, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '0973297687', 16, 'Chimuka Moonde', '0978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-17 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-17 16:35:12', NULL),
(20200007, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '0973297687', 16, 'Chimuka Moonde', '0978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-17 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-18 01:47:49', NULL),
(20200008, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '0973297687', 16, 'Chimuka Moonde', '0978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-17 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-18 01:50:51', NULL),
(20200009, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '0973297687', 16, 'Chimuka Moonde', '0978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-17 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-25 07:45:32', NULL),
(20200010, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '260973297687', 16, 'Chimuka Moonde', '260978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-25 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-25 07:46:45', NULL),
(20200011, 1, NULL, 0, 'Sync Pending', 0, 'Pending', NULL, NULL, 15, 'Francis Chulu', '260973297687', 16, 'Chimuka Moonde', '260978981572', 2.00, 'SAMSUNG-J5PRO', '2019-12-25 00:00:00', NULL, NULL, NULL, '', '', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '2019-12-25 08:09:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_types`
--

CREATE TABLE `transaction_types` (
  `transaction_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaction_types`
--

INSERT INTO `transaction_types` (`transaction_type_id`, `name`, `description`, `date_created`, `date_modified`) VALUES
(1, 'Make a sale', '', '2019-12-13 00:17:05', NULL),
(2, 'Order', '', '2019-12-13 00:17:05', NULL),
(3, 'Ticket Purchase', '', '2019-12-13 00:17:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `nrc` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` varchar(10) DEFAULT NULL,
  `mobile_number` varchar(12) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(400) NOT NULL,
  `token_balance` double(10,2) NOT NULL DEFAULT '0.00',
  `account_number` varchar(250) DEFAULT NULL,
  `verification_token` varchar(300) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `auth_key` varchar(300) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `firstname`, `lastname`, `nrc`, `gender`, `dob`, `mobile_number`, `email`, `password`, `token_balance`, `account_number`, `verification_token`, `password_reset_token`, `auth_key`, `status`, `created_by`, `updated_by`, `date_created`, `date_updated`) VALUES
(1, 1, 'Francis', 'Chulu C', '111111', 'Male', '1978-11-29', '260978981576', 'chulu1francis@gmail.com', '$2y$13$bvhLW881SbIuZvY9M1Rgf.E5exSZManCymyHWPF5Jw41wEcAO1wPS', 0.00, '111111', 'gHn4kQcKjfq55mM9kiEzVCir_3Rvs8VK_1562149047', NULL, 'CbART1y2XkwfYkCNCk_gCFi3hSXUx7U1', '1', 1, 1, '2019-11-05 00:00:00', '2019-11-08 13:07:19'),
(5, 2, 'Chishala', 'Chulu', '111111', 'Male', '2016-03-16', '260969240309', 'francis.chulu@unza.zm', '$2y$13$M9ceiZrYNhysoORQpLe75Oi/so058Nf8oAEt4Sy/T4yx4t2CgInFa', 0.00, NULL, NULL, NULL, 'D-Hf8AnNVt2dPtADnmJ8oq4e0syK_4nc', '1', 1, 1, '2019-11-08 12:35:16', '2019-12-25 05:48:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_config`
--
ALTER TABLE `api_config`
  ADD PRIMARY KEY (`api_config_id`),
  ADD UNIQUE KEY `application_name` (`application_name`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`bus_id`);

--
-- Indexes for table `bus_companies`
--
ALTER TABLE `bus_companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `bus_departure_times`
--
ALTER TABLE `bus_departure_times`
  ADD PRIMARY KEY (`bus_departure_time_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_charges`
--
ALTER TABLE `market_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_notifications`
--
ALTER TABLE `market_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_to_roles`
--
ALTER TABLE `permission_to_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `routes_times`
--
ALTER TABLE `routes_times`
  ADD PRIMARY KEY (`routes_times_id`);

--
-- Indexes for table `sms_history`
--
ALTER TABLE `sms_history`
  ADD PRIMARY KEY (`sms_history_id`);

--
-- Indexes for table `traders`
--
ALTER TABLE `traders`
  ADD PRIMARY KEY (`trader_id`),
  ADD UNIQUE KEY `firstname` (`firstname`,`lastname`),
  ADD UNIQUE KEY `mobile_number` (`mobile_number`),
  ADD UNIQUE KEY `firstname_2` (`firstname`,`lastname`,`mobile_number`);

--
-- Indexes for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD PRIMARY KEY (`transaction_log_id`);

--
-- Indexes for table `transaction_summaries`
--
ALTER TABLE `transaction_summaries`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `transaction_types`
--
ALTER TABLE `transaction_types`
  ADD PRIMARY KEY (`transaction_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `role_id` (`role_id`,`firstname`,`lastname`),
  ADD UNIQUE KEY `role_id_2` (`role_id`,`mobile_number`),
  ADD UNIQUE KEY `role_id_3` (`role_id`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_config`
--
ALTER TABLE `api_config`
  MODIFY `api_config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `bus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `bus_companies`
--
ALTER TABLE `bus_companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bus_departure_times`
--
ALTER TABLE `bus_departure_times`
  MODIFY `bus_departure_time_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `market_charges`
--
ALTER TABLE `market_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `market_notifications`
--
ALTER TABLE `market_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `permission_to_roles`
--
ALTER TABLE `permission_to_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `route_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `routes_times`
--
ALTER TABLE `routes_times`
  MODIFY `routes_times_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT for table `sms_history`
--
ALTER TABLE `sms_history`
  MODIFY `sms_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `traders`
--
ALTER TABLE `traders`
  MODIFY `trader_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `transaction_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_summaries`
--
ALTER TABLE `transaction_summaries`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20200012;

--
-- AUTO_INCREMENT for table `transaction_types`
--
ALTER TABLE `transaction_types`
  MODIFY `transaction_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
