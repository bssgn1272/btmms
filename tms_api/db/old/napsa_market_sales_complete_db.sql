-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2019 at 06:30 PM
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
-- Table structure for table `float_money`
--

CREATE TABLE `float_money` (
  `float_money_id` int(11) NOT NULL,
  `telco` varchar(50) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `float_money`
--

INSERT INTO `float_money` (`float_money_id`, `telco`, `amount`, `date_created`, `date_modified`) VALUES
(1, 'Airtel', 10070.00, '2019-11-23 14:47:04', '2019-12-09 19:03:04'),
(2, 'MTN', 10000.00, '2019-11-23 14:47:04', NULL),
(3, 'Zamtel', 10000.00, '2019-11-23 14:47:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `marketeer_products`
--

CREATE TABLE `marketeer_products` (
  `marketeer_products_id` int(11) NOT NULL,
  `trader_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_of_measure_id` int(11) DEFAULT NULL,
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `marketeer_products`
--

INSERT INTO `marketeer_products` (`marketeer_products_id`, `trader_id`, `product_id`, `unit_of_measure_id`, `price`, `date_created`, `date_modified`) VALUES
(1, 1, 1, 1, 80.00, '2019-11-08 16:29:10', '2019-11-08 16:42:45');

-- --------------------------------------------------------

--
-- Table structure for table `measures`
--

CREATE TABLE `measures` (
  `unit_of_measure_id` int(11) NOT NULL,
  `unit_name` varchar(20) NOT NULL,
  `unit_description` varchar(500) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `measures`
--

INSERT INTO `measures` (`unit_of_measure_id`, `unit_name`, `unit_description`, `date_created`, `date_modified`) VALUES
(1, 'KG', 'unit of measurement', '2019-11-08 16:17:17', '2019-11-08 16:19:31'),
(2, 'menda', 'one bucket', '2019-11-17 16:15:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `payment_method_id` int(11) NOT NULL,
  `payment_method_name` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`payment_method_id`, `payment_method_name`, `date_created`, `date_modified`) VALUES
(1, 'Cash', '2019-07-10 00:09:03', NULL),
(2, 'Bank Deposit', '2019-07-10 00:09:03', NULL),
(3, 'Debit Card', '2019-07-10 00:09:03', NULL),
(4, 'Cheque', '2019-07-10 00:09:03', '2019-07-15 21:55:21'),
(5, 'Bank Transfer', '2019-07-10 00:09:03', NULL),
(6, 'Money Order', '2019-07-10 00:09:03', NULL),
(7, 'Airtel money', '2019-07-10 00:14:47', '2019-11-06 18:40:55'),
(8, 'MTN money', '2019-07-10 00:14:47', NULL),
(9, 'Zamtel kwacha', '2019-07-10 00:14:47', '2019-11-06 18:40:55');

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
(1, 'Manage Roles', NULL),
(2, 'View Roles', NULL),
(3, 'Manage Users', 'Can manage system users'),
(4, 'View Users', 'Can view system users');

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
(128, 2, 2),
(163, 1, 1),
(164, 1, 2),
(165, 1, 3),
(166, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_category_id` int(11) NOT NULL,
  `product_image` varchar(200) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_description` varchar(500) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_category_id`, `product_image`, `product_name`, `product_description`, `date_created`, `date_modified`) VALUES
(1, 1, NULL, 'Onions', 'healthy fruits', '2019-11-08 16:05:54', '2019-11-08 16:12:20'),
(2, 1, NULL, 'Tomatoes', 'health fruits', '2019-11-17 16:15:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `product_category_id` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `category_description` varchar(500) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_category_id`, `category_name`, `category_description`, `date_created`, `date_modified`) VALUES
(1, 'Test Category Name', 'test category description', '2019-11-08 13:02:33', '2019-11-08 13:05:00'),
(2, 'Test', 'test description', '2019-11-17 16:14:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `redeemed_rewards`
--

CREATE TABLE `redeemed_rewards` (
  `redeemed_reward_id` int(11) NOT NULL,
  `reward_campaign_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points_used` double NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `date_earned` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_claimed` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reward_campaigns`
--

CREATE TABLE `reward_campaigns` (
  `reward_campaign_id` int(11) NOT NULL,
  `campaign_name` varchar(200) DEFAULT NULL,
  `campaign_description` varchar(500) DEFAULT NULL,
  `marketeer_points_required` int(11) NOT NULL,
  `buyer_points_required` int(11) NOT NULL,
  `marketeer_points_multiplier` int(11) NOT NULL DEFAULT '1',
  `buyer_points_multiplier` int(11) NOT NULL DEFAULT '1',
  `active_from` date NOT NULL,
  `active_to` date NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 'Admin', NULL, '2019-11-05 00:00:00', '2019-11-06 15:26:09', 1, 1),
(2, 'Agent', NULL, '2019-11-05 00:00:00', '2019-11-06 11:54:18', 1, 1),
(3, 'Marketeer', NULL, '2019-11-05 00:00:00', '2019-11-06 11:54:12', 1, 1),
(4, 'Buyer', NULL, '2019-11-05 00:00:00', '2019-11-06 11:54:24', 1, 1);

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
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Table structure for table `station`
--

CREATE TABLE `station` (
  `station_id` int(11) NOT NULL,
  `station_name` varchar(35) NOT NULL,
  `station_location` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `company_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`station_id`, `station_name`, `station_location`, `date_created`, `date_modified`, `company_id`) VALUES
(1, 'Intercity', 'Lusaka', '2018-05-10 13:58:06', '2019-11-19 08:30:44', 2),
(2, 'Kitwe', 'Kitwe', '2018-07-09 15:32:59', '2019-11-19 08:31:50', 2),
(3, 'Ndola', 'Ndola', '2018-07-11 09:01:08', '2019-11-19 08:31:56', 2),
(4, 'L/stone', 'Livingstone', '2019-11-19 10:32:32', '2019-11-19 10:32:33', 2),
(8, 'test station Mazhandu1', 'test', '2019-06-19 09:40:34', '2019-06-19 09:44:28', 3),
(9, 'test station Booknow1', 'test', '2019-06-19 09:41:06', '2019-06-19 09:44:35', 1),
(11, 'test station Booknow2', 'Lusaka', '2019-06-19 09:43:36', '2019-06-19 09:44:38', 1),
(12, 'test station Mazhandu2', 'Kabwe', '2019-06-19 09:43:54', '2019-08-13 09:28:27', 3),
(14, 'Test Station Powertools', 'Test', '2019-08-13 11:30:02', '2019-08-13 11:30:02', 2);

-- --------------------------------------------------------

--
-- Table structure for table `token_procurement`
--

CREATE TABLE `token_procurement` (
  `token_procurement_id` int(11) NOT NULL,
  `trader_id` int(11) NOT NULL,
  `amount_tendered` double(10,2) NOT NULL,
  `token_value` double(10,2) NOT NULL,
  `reference_number` varchar(200) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) NOT NULL,
  `procuring_msisdn` varchar(20) NOT NULL,
  `device_serial` varchar(200) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `token_procurement`
--

INSERT INTO `token_procurement` (`token_procurement_id`, `trader_id`, `amount_tendered`, `token_value`, `reference_number`, `agent_id`, `organisation_id`, `payment_method_id`, `procuring_msisdn`, `device_serial`, `transaction_date`, `date_created`, `date_modified`) VALUES
(1, 1, 70.00, 70.00, 'NAPSA123456', 1, 1, 1, '0973297682', 'ACCER3A315-51-57GY', '2019-11-08 00:00:00', '2019-11-08 16:52:25', NULL),
(2, 1, 70.00, 70.00, 'NAPSA123456', 1, 1, 1, '0973297682', 'ACCER3A315-51-57GY', '2019-11-08 00:00:00', '2019-11-08 17:04:52', NULL),
(3, 1, 70.00, 70.00, 'NAPSA123456', 1, 1, 1, '0973297682', 'ACCER3A315-51-57GY', '2019-11-08 00:00:00', '2019-11-08 17:05:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `token_redemption`
--

CREATE TABLE `token_redemption` (
  `token_redemption_id` int(11) NOT NULL,
  `trader_id` int(11) NOT NULL,
  `token_value_tendered` double(10,2) NOT NULL,
  `amount_redeemed` double(10,2) NOT NULL,
  `reference_number` varchar(200) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) NOT NULL,
  `recipient_msisdn` varchar(20) NOT NULL,
  `device_serial` varchar(200) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `token_redemption`
--

INSERT INTO `token_redemption` (`token_redemption_id`, `trader_id`, `token_value_tendered`, `amount_redeemed`, `reference_number`, `agent_id`, `organisation_id`, `payment_method_id`, `recipient_msisdn`, `device_serial`, `transaction_date`, `date_created`, `date_modified`) VALUES
(1, 16, 40.00, 40.00, 'NAPSA123456', NULL, NULL, 7, '0973297682', 'ACCER3A315-51-57GY', '2019-11-08 00:00:00', '2019-11-23 20:14:06', NULL),
(2, 16, 40.00, 40.00, 'NAPSA123456', NULL, NULL, 7, '0973297682', 'ACCER3A315-51-57GY', '2019-11-08 00:00:00', '2019-11-23 20:23:42', NULL);

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

INSERT INTO `traders` (`trader_id`, `role`, `firstname`, `lastname`, `nrc`, `gender`, `mobile_number`, `QR_code`, `token_balance`, `account_number`, `dob`, `image`, `password`, `auth_key`, `verification_code`, `password_reset_token`, `status`, `created_by`, `updated_by`, `date_created`, `date_updated`) VALUES
(15, 'buyer', 'Chulu', 'Francs chishala', '10001', 'Male', '0978981572', NULL, 30.00, '1234567', '1980-02-29', NULL, '$2y$13$TQzSOdgNgzZsgV9NFGSXPO65vb3n.5j2lum7mxKQig9KvdLCFr.0u', 'Ba98OAJyTr0tByl3bgaL9I6eb_-g7UZJ', '1016', NULL, 0, NULL, NULL, '2019-11-07 17:56:11', '2019-12-09 17:03:04'),
(16, 'marketeer', 'Chimuka', 'Moondea', '102610/10/1', 'male', '0973297682', NULL, 170.00, '201968490240', '1992-04-20', NULL, '$2y$13$/fJ9BNacsvyYA5TrtD6vSeMcyBIUBIpXigpwpcPJkG7GgqGKejn7y', '2_OY5l5TEpjnNPPjKHTgB8lHqUU3sezg', '4413', NULL, 0, NULL, NULL, '2019-11-08 17:28:08', '2019-12-09 17:03:04');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `transaction_details_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_price` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_summaries`
--

CREATE TABLE `transaction_summaries` (
  `cart_id` int(20) NOT NULL,
  `external_trans_id` varchar(250) DEFAULT NULL,
  `probase_status_code` int(11) DEFAULT NULL,
  `probase_status_description` varchar(300) DEFAULT NULL,
  `momo_status_code` int(11) DEFAULT NULL,
  `momo_status_description` varchar(300) DEFAULT NULL,
  `marketeer_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `buyer_mobile` varchar(15) DEFAULT NULL,
  `amount_due` double(10,2) NOT NULL,
  `token_tendered` double(10,2) NOT NULL,
  `device_serial` varchar(200) NOT NULL,
  `points_marketeer_earned` int(11) NOT NULL DEFAULT '0',
  `points_buyer_earned` int(11) NOT NULL DEFAULT '0',
  `transaction_date` datetime NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaction_summaries`
--

INSERT INTO `transaction_summaries` (`cart_id`, `external_trans_id`, `probase_status_code`, `probase_status_description`, `momo_status_code`, `momo_status_description`, `marketeer_id`, `buyer_id`, `buyer_mobile`, `amount_due`, `token_tendered`, `device_serial`, `points_marketeer_earned`, `points_buyer_earned`, `transaction_date`, `date_created`, `date_modified`) VALUES
(20200101, NULL, NULL, NULL, NULL, NULL, 16, NULL, '0978981572', 20.00, 20.00, 'SAMSUNG-J5PRO', 0, 0, '2019-11-09 00:00:00', '2019-12-09 19:02:53', NULL),
(20200102, NULL, NULL, NULL, NULL, NULL, 16, NULL, '0978981572', 30.00, 30.00, 'SAMSUNG-J5PRO', 0, 0, '2019-11-09 00:00:00', '2019-12-09 19:03:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
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

INSERT INTO `users` (`user_id`, `image`, `role_id`, `firstname`, `lastname`, `nrc`, `gender`, `dob`, `mobile_number`, `email`, `password`, `token_balance`, `account_number`, `verification_token`, `password_reset_token`, `auth_key`, `status`, `created_by`, `updated_by`, `date_created`, `date_updated`) VALUES
(1, NULL, 1, 'Francis', 'Chulu', '111111', 'Male', '1978-11-29', '260978981576', 'chulu1francis@gmail.com', '$2y$13$cGziIYsqtvoHhGkFq7AXOO2ZdRsxNg0YnSyWXsA8XIZnU2gPwJiZ6', 0.00, '111111', 'gHn4kQcKjfq55mM9kiEzVCir_3Rvs8VK_1562149047', NULL, 'CbART1y2XkwfYkCNCk_gCFi3hSXUx7U1', '1', 1, 1, '2019-11-05 00:00:00', '2019-11-06 15:38:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_config`
--
ALTER TABLE `api_config`
  ADD PRIMARY KEY (`api_config_id`);

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
-- Indexes for table `float_money`
--
ALTER TABLE `float_money`
  ADD PRIMARY KEY (`float_money_id`);

--
-- Indexes for table `marketeer_products`
--
ALTER TABLE `marketeer_products`
  ADD PRIMARY KEY (`marketeer_products_id`),
  ADD UNIQUE KEY `user_id` (`trader_id`,`product_id`,`unit_of_measure_id`,`price`);

--
-- Indexes for table `measures`
--
ALTER TABLE `measures`
  ADD PRIMARY KEY (`unit_of_measure_id`),
  ADD UNIQUE KEY `unit_name` (`unit_name`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`payment_method_id`);

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `redeemed_rewards`
--
ALTER TABLE `redeemed_rewards`
  ADD PRIMARY KEY (`redeemed_reward_id`);

--
-- Indexes for table `reward_campaigns`
--
ALTER TABLE `reward_campaigns`
  ADD PRIMARY KEY (`reward_campaign_id`),
  ADD UNIQUE KEY `campaign_name` (`campaign_name`);

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
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `sms_history`
--
ALTER TABLE `sms_history`
  ADD PRIMARY KEY (`sms_history_id`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`station_id`);

--
-- Indexes for table `token_procurement`
--
ALTER TABLE `token_procurement`
  ADD PRIMARY KEY (`token_procurement_id`);

--
-- Indexes for table `token_redemption`
--
ALTER TABLE `token_redemption`
  ADD PRIMARY KEY (`token_redemption_id`);

--
-- Indexes for table `traders`
--
ALTER TABLE `traders`
  ADD PRIMARY KEY (`trader_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`transaction_details_id`);

--
-- Indexes for table `transaction_summaries`
--
ALTER TABLE `transaction_summaries`
  ADD PRIMARY KEY (`cart_id`);

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
  MODIFY `api_config_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `float_money`
--
ALTER TABLE `float_money`
  MODIFY `float_money_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `marketeer_products`
--
ALTER TABLE `marketeer_products`
  MODIFY `marketeer_products_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `measures`
--
ALTER TABLE `measures`
  MODIFY `unit_of_measure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permission_to_roles`
--
ALTER TABLE `permission_to_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `product_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `redeemed_rewards`
--
ALTER TABLE `redeemed_rewards`
  MODIFY `redeemed_reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_campaigns`
--
ALTER TABLE `reward_campaigns`
  MODIFY `reward_campaign_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_history`
--
ALTER TABLE `sms_history`
  MODIFY `sms_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `station`
--
ALTER TABLE `station`
  MODIFY `station_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `token_procurement`
--
ALTER TABLE `token_procurement`
  MODIFY `token_procurement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `token_redemption`
--
ALTER TABLE `token_redemption`
  MODIFY `token_redemption_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `traders`
--
ALTER TABLE `traders`
  MODIFY `trader_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `transaction_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_summaries`
--
ALTER TABLE `transaction_summaries`
  MODIFY `cart_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20200103;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
