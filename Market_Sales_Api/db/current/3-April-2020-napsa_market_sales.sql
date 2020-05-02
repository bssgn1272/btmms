-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2020 at 05:45 PM
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

INSERT INTO unza_api_config (`api_config_id`, `application_name`, `api_key`, `date_created`, `date_modified`) VALUES
(1, 'test', '3aa0fa07553c7e3a86f46d3b35234620', '2019-12-17 15:03:03', NULL);

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

INSERT INTO unza_image (`id`, `user_id`, `file`) VALUES
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
-- Table structure for table `market_charges_details`
--

CREATE TABLE `market_charges_details` (
  `transaction_details_id` int(11) NOT NULL,
  `tx_summary_id` int(11) NOT NULL,
  `market_charge_id` int(11) NOT NULL,
  `market_charge_fee` double(10,2) NOT NULL DEFAULT '0.00',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `market_charges_summaries`
--

CREATE TABLE `market_charges_summaries` (
  `tx_summary_id` int(11) NOT NULL,
  `transaction_type_id` int(11) NOT NULL,
  `external_trans_id` varchar(255) DEFAULT NULL,
  `probase_status_code` int(11) NOT NULL DEFAULT '0',
  `probase_status_description` varchar(300) DEFAULT 'Sync Pending',
  `seller_id` varchar(250) DEFAULT NULL,
  `seller_firstname` varchar(100) DEFAULT NULL,
  `seller_lastname` varchar(100) DEFAULT NULL,
  `seller_mobile_number` varchar(20) DEFAULT NULL,
  `amount_due` double(10,2) NOT NULL DEFAULT '0.00',
  `amount_tendered` double(10,2) NOT NULL DEFAULT '0.00',
  `device_serial` varchar(200) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `debit_msg` varchar(200) DEFAULT NULL,
  `debit_reference` varchar(200) DEFAULT NULL,
  `debit_code` varchar(10) DEFAULT NULL,
  `callback_msg` varchar(200) DEFAULT NULL,
  `callback_reference` varchar(200) DEFAULT NULL,
  `callback_code` varchar(10) DEFAULT NULL,
  `callback_system_code` varchar(10) DEFAULT NULL,
  `callback_transactionID` varchar(200) DEFAULT NULL,
  `credit_msg` varchar(200) DEFAULT NULL,
  `credit_reference` varchar(200) DEFAULT NULL,
  `credit_code` varchar(10) DEFAULT NULL,
  `credit_system_code` varchar(10) NOT NULL,
  `credit_transactionID` varchar(200) NOT NULL,
  `sms_seller` text,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

INSERT INTO unza_market_notifications (`id`, `type`, `message`, `recipients`, `status`, `notification_date`) VALUES
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

INSERT INTO unza_permissions (`id`, `name`, `description`) VALUES
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

INSERT INTO unza_permission_to_roles (`id`, `role_id`, `permission_id`) VALUES
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

INSERT INTO unza_roles (`role_id`, `name`, `description`, `date_created`, `date_updated`, `created_by`, `updated_by`) VALUES
(1, 'Admin', NULL, '2019-11-05 00:00:00', '2019-11-13 12:04:23', 1, 1),
(2, 'Market Administrator', NULL, '2019-11-05 00:00:00', '2019-11-13 11:14:03', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) NOT NULL,
  `sender_id` varchar(200) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `message` varchar(350) NOT NULL,
  `status` varchar(200) NOT NULL,
  `code` int(11) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
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

INSERT INTO unza_traders (`trader_id`, `role`, `firstname`, `lastname`, `nrc`, `gender`, `mobile_number`, `QR_code`, `token_balance`, `account_number`, `dob`, `stand_no`, `image`, `password`, `auth_key`, `verification_code`, `password_reset_token`, `status`, `created_by`, `updated_by`, `date_created`, `date_updated`) VALUES
(15, 'buyer', 'Chulu', 'Francis', '10001', 'Male', '260969240309', 'trd0978981572', 56.00, '1234567', '1980-02-29', '24', NULL, '$2y$13$TQzSOdgNgzZsgV9NFGSXPO65vb3n.5j2lum7mxKQig9KvdLCFr.0u', 'Ba98OAJyTr0tByl3bgaL9I6eb_-g7UZJ', '1016', NULL, 1, NULL, NULL, '2019-11-07 17:56:11', '2020-01-21 14:34:40'),
(16, NULL, 'Chimuka', 'Moonde', '10002', 'Male', '260973297687', 'trd0978981571', 51.00, '1872726', '1982-08-20', '3', NULL, '$13$TQzSOdgNgzZsgV9NFGSXPO65vb3n.5j2lum7mxKQig9KvdLCFr.0u', 'Ba98OAJyTr0tByl3bgaL9I6eb_-g7UZJ', '1029', NULL, 1, NULL, NULL, '2019-11-13 14:12:41', '2019-12-26 17:28:34'),
(17, NULL, 'Simon', 'Chiwamba', '10003', 'Female', '260967485331', 'trd0960000000', 0.00, '198988', '1978-09-23', '45', NULL, '$13$TQzSOdgNgzZsgV9NFGSXPO65vb3n.5j2lum7mxKQig9KvdLCFr.0u', 'Ba98OAJyTr0tByl3bgaL9I6eb_-g7UZJ', '8787', NULL, 1, NULL, NULL, '2019-11-13 14:14:01', '2020-01-20 13:52:59'),
(18, NULL, 'MTNZM', 'Nsano', '10004', 'Male', '260968580098  ', NULL, 0.00, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2020-01-26 00:52:23', NULL),
(19, NULL, 'MTNZM two', 'Nsano', '10005', 'Male', '260762025989', NULL, 0.00, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2020-01-26 00:53:15', NULL),
(20, NULL, 'Airtel', 'Nsano', '10006', 'Male', '260978580098  ', NULL, 0.00, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2020-01-26 00:53:35', '2020-01-25 22:54:15'),
(21, NULL, 'Airtel two', 'Nsano', '10007', 'Male', '260972025989', NULL, 0.00, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2020-01-26 00:54:45', '2020-01-25 23:00:25');

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
  `route_code` varchar(150) DEFAULT NULL,
  `transaction_channel` varchar(150) DEFAULT NULL,
  `id_type` varchar(150) DEFAULT NULL,
  `passenger_id` varchar(150) DEFAULT NULL,
  `bus_schedule_id` varchar(50) DEFAULT NULL,
  `travel_date` varchar(150) DEFAULT NULL,
  `travel_time` varchar(150) DEFAULT NULL,
  `seller_id` varchar(250) DEFAULT NULL,
  `seller_firstname` varchar(100) DEFAULT NULL,
  `seller_lastname` varchar(100) DEFAULT NULL,
  `seller_mobile_number` varchar(20) DEFAULT NULL,
  `buyer_id` varchar(250) DEFAULT NULL,
  `buyer_firstname` varchar(100) DEFAULT NULL,
  `buyer_lastname` varchar(100) DEFAULT NULL,
  `buyer_mobile_number` varchar(20) DEFAULT NULL,
  `buyer_email` varchar(250) DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `transaction_fee` double(10,2) NOT NULL DEFAULT '0.00',
  `device_serial` varchar(200) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `debit_msg` varchar(200) DEFAULT NULL,
  `debit_reference` varchar(200) DEFAULT NULL,
  `debit_code` varchar(10) DEFAULT NULL,
  `callback_msg` varchar(200) DEFAULT NULL,
  `callback_reference` varchar(200) DEFAULT NULL,
  `callback_code` varchar(10) DEFAULT NULL,
  `callback_system_code` varchar(10) DEFAULT NULL,
  `callback_transactionID` varchar(200) DEFAULT NULL,
  `credit_msg` varchar(200) DEFAULT NULL,
  `credit_reference` varchar(200) DEFAULT NULL,
  `credit_code` varchar(10) DEFAULT NULL,
  `credit_system_code` varchar(10) NOT NULL,
  `credit_transactionID` varchar(200) NOT NULL,
  `fina_status` varchar(10) DEFAULT NULL,
  `fina_desc` varchar(350) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaction_summaries`
--

INSERT INTO unza_transactions (`cart_id`, `transaction_type_id`, `external_trans_id`, `probase_status_code`, `probase_status_description`, `route_code`, `transaction_channel`, `id_type`, `passenger_id`, `bus_schedule_id`, `travel_date`, `travel_time`, `seller_id`, `seller_firstname`, `seller_lastname`, `seller_mobile_number`, `buyer_id`, `buyer_firstname`, `buyer_lastname`, `buyer_mobile_number`, `buyer_email`, `amount`, `transaction_fee`, `device_serial`, `transaction_date`, `debit_msg`, `debit_reference`, `debit_code`, `callback_msg`, `callback_reference`, `callback_code`, `callback_system_code`, `callback_transactionID`, `credit_msg`, `credit_reference`, `credit_code`, `credit_system_code`, `credit_transactionID`, `fina_status`, `fina_desc`, `date_created`, `date_modified`) VALUES
(1, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e21fc0e87726709379d1d34', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:25:17', '2020-01-17 18:25:18'),
(2, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e21ff3dc619242a54cf6cd7', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:38:52', '2020-01-17 18:38:53'),
(3, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e220004c619242a54cf6cdd', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:42:12', '2020-01-17 18:42:12'),
(4, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e220103c619242a54cf6ce2', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:46:27', '2020-01-17 18:46:28'),
(5, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e220153c619242a54cf6ce4', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:47:47', '2020-01-17 18:47:47'),
(6, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e2201bfc619242a54cf6ce6', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:49:34', '2020-01-17 18:49:35'),
(7, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e22027987726709379d1d61', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:52:40', '2020-01-17 18:52:41'),
(8, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260969240309', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e2202fd87726709379d1d67', '00', 'Transaction failed', '5e2202fd87726709379d1d67', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 18:54:53', '2020-01-17 19:31:54'),
(9, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e220c5dc619242a54cf6d39', '00', 'Transaction successful', '5e220c5dc619242a54cf6d39', '00', '01', 'e6538f7d0d73454396ca6c9cdcc6037f', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 19:34:53', '2020-01-17 19:34:54'),
(10, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e220dbbc619242a54cf6d42', '00', 'Transaction successful', '5e220dbbc619242a54cf6d42', '00', '01', '7ee14d3a3f244f22938876b691899160', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 19:40:42', '2020-01-17 19:40:44'),
(11, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e220ebfc619242a54cf6d4c', '00', 'Transaction successful', '5e220ebfc619242a54cf6d4c', '00', '01', 'a8958e7b06f6456baaf53edb0e15952c', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 19:45:02', '2020-01-17 19:45:03'),
(12, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e220f73c619242a54cf6d51', '00', 'Transaction successful', '5e220f73c619242a54cf6d51', '00', '01', '81e341e4b7a74262b80b1a112c49c356', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 19:48:03', '2020-01-17 19:48:04'),
(13, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e2210a387726709379d1dd8', '00', 'Transaction successful', '5e2210a387726709379d1dd8', '00', '01', '4c0719011d0e4331b53af6b47f4b8c3d', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 19:53:07', '2020-01-17 19:53:08'),
(14, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e221135c619242a54cf6d66', '00', 'Transaction successful', '5e221135c619242a54cf6d66', '00', '01', '76f876f512ff4fb5a2fad6cdcdbafe60', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 19:55:33', '2020-01-17 19:55:34'),
(15, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e221209c619242a54cf6d6f', '00', 'Transaction successful', '5e221209c619242a54cf6d6f', '00', '01', 'c15a03d5124b4ceb810d24abf571f283', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 19:59:04', '2020-01-17 19:59:05'),
(16, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e221277c619242a54cf6d75', '00', 'Transaction successful', '5e221277c619242a54cf6d75', '00', '01', 'e0540260bf3c40c0a72a0e39fa7db5aa', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 20:00:54', '2020-01-17 20:00:55'),
(17, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e2213a987726709379d1dfd', '00', 'Transaction successful', '5e2213a987726709379d1dfd', '00', '01', '0ea4411dfd844585ae5adc5563e7ed54', 'Transaction successful', '5e2213a9c619242a54cf6d81', '00', '01', 'ead604ed267848e7927348d282e866ac', NULL, NULL, '2020-01-17 20:06:00', '2020-01-17 20:06:01'),
(18, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e221cedc619242a54cf6dbe', '00', 'Transaction successful', '5e221cedc619242a54cf6dbe', '00', '01', '93f3edcb97604bc782e7f468c153b5ff', 'Transaction successful', '5e221cee87726709379d1e3a', '00', '01', '4b868fa2cb2442e5a781da113cf9d5d5', NULL, NULL, '2020-01-17 20:45:33', '2020-01-17 20:45:35'),
(19, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e221d5787726709379d1e3d', '00', 'Transaction successful', '5e221d5787726709379d1e3d', '00', '01', 'd8cdf19b73cb4f6b9a32af51d72343ee', 'Transaction successful', '5e221d57c619242a54cf6dc1', '00', '01', '7bc23b53111940719d05e17855d34cc4', NULL, NULL, '2020-01-17 20:47:18', '2020-01-17 20:47:20'),
(20, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260978580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e2226dd87726709379d1e79', '00', 'Transaction failed', '5e2226dd87726709379d1e79', '01', '', '', 'Transaction successful', '5e2226dec619242a54cf6dfe', '00', '01', '87fe727015df419787199025abc8206e', NULL, NULL, '2020-01-17 21:27:57', '2020-01-17 21:27:58'),
(21, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260978580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e22273bc619242a54cf6e03', '00', 'Transaction failed', '5e22273bc619242a54cf6e03', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 21:29:30', '2020-01-17 21:29:31'),
(22, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260972025989', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e22277ac619242a54cf6e05', '00', 'Transaction failed', '5e22277ac619242a54cf6e05', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-17 21:30:33', '2020-01-17 21:30:34'),
(23, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e22288587726709379d1e87', '00', 'Transaction successful', '5e22288587726709379d1e87', '00', '01', '4ed50f01232246c69df2722b7b660122', 'Transaction successful', '5e222887c619242a54cf6e0d', '00', '01', 'c367eb161f3a4c5280eaab962f44df0c', NULL, NULL, '2020-01-17 21:35:01', '2020-01-17 21:35:04'),
(24, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260762025989', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e2228c987726709379d1e89', '00', 'Transaction successful', '5e2228c987726709379d1e89', '00', '01', '6f9f3b2813044f10baf7b6fec7ee843b', 'Transaction successful', '5e2228cac619242a54cf6e10', '00', '01', '0f8e8b078cb4415f8abb023b8f6ecffb', NULL, NULL, '2020-01-17 21:36:09', '2020-01-17 21:36:11'),
(25, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e25699f87726709379d2837', '00', 'Transaction successful', '5e25699f87726709379d2837', '00', '01', '6d63d026ded244c48316c79b6f37139a', 'Transaction successful', '5e2569a3f564c02c85075054', '00', '01', 'd5a3b156fecd4798a72aa254c0c5aa32', NULL, NULL, '2020-01-20 08:49:35', '2020-01-20 08:49:40'),
(26, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '260978981572', '16', NULL, NULL, '260973297687', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e258479f564c02c850750c5', '00', 'Transaction failed', '5e258479f564c02c850750c5', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 10:44:08', '2020-01-20 10:44:12'),
(27, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '26078981576', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-18 00:00:00', 'Transaction in progress', '5e2586eaf564c02c850750d1', '00', 'Transaction successful', '5e2586eaf564c02c850750d1', '00', '01', '07076f322c494d22b52b91e45576fd81', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 10:54:34', '2020-01-20 10:54:36'),
(28, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e258e9287726709379d28e3', '00', 'Transaction successful', '5e258e9287726709379d28e3', '00', '01', '63dbab99ea484dd3859c83f829376aaf', 'Transaction successful', '5e258e94f564c02c85075102', '00', '01', '93fed39a563d47b291716f05d9bfd29b', NULL, NULL, '2020-01-20 11:27:14', '2020-01-20 11:27:17'),
(29, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260762025989', '16', NULL, NULL, '260968580098', NULL, 10.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-17 00:00:00', 'Transaction in progress', '5e2590ec87726709379d28ed', '00', 'Transaction successful', '5e2590ec87726709379d28ed', '00', '01', '906acbc52845489685fbed7388fc059f', 'Transaction successful', '5e2590edf564c02c8507510d', '00', '01', '636b5cfe048541e2acc924503531ecfd', NULL, NULL, '2020-01-20 11:37:15', '2020-01-20 11:37:18'),
(30, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260978981572', '0', NULL, NULL, '260978981576', NULL, 20.00, 0.00, '1111111', '2020-01-20 00:00:00', 'Transaction in progress', '5e2592b7f564c02c85075117', '00', 'Transaction failed', '5e2592b7f564c02c85075117', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 11:44:55', '2020-01-20 11:44:56'),
(31, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260978981572', '0', NULL, NULL, '260978981576', NULL, 2.00, 0.00, '1111111', '2020-01-20 00:00:00', 'Transaction in progress', '5e2592f2f564c02c85075118', '00', 'Transaction failed', '5e2592f2f564c02c85075118', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 11:45:54', '2020-01-20 11:45:55'),
(32, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260978981572', '0', NULL, NULL, '260978981576', NULL, 3.00, 0.00, '1111111', '2020-01-20 00:00:00', 'Transaction in progress', '5e25932af564c02c85075119', '00', 'Transaction failed', '5e25932af564c02c85075119', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 11:46:50', '2020-01-20 11:46:51'),
(33, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260978981572', '0', NULL, NULL, '260968580098', NULL, 4.00, 0.00, '1111111', '2020-01-20 00:00:00', 'Transaction in progress', '5e2593df87726709379d28fe', '00', 'Transaction successful', '5e2593df87726709379d28fe', '00', '01', 'c76426640a054ffeaae58570af4c2f5b', 'Transaction failed', '5e2593e2f564c02c8507511f', '01', '', '', NULL, NULL, '2020-01-20 11:49:50', '2020-01-20 11:49:54'),
(34, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260978981572', '0', NULL, NULL, '260968580098', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e259eb3f564c02c8507514c', '00', 'Transaction successful', '5e259eb3f564c02c8507514c', '00', '01', 'dde793bde43c46c18b4baf8eb124a563', 'Transaction failed', '5e259eb487726709379d292c', '01', '', '', NULL, NULL, '2020-01-20 12:36:02', '2020-01-20 12:36:05'),
(35, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260978981572', '0', NULL, NULL, '260978580098', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e259ee887726709379d292f', '00', 'Transaction failed', '5e259ee887726709379d292f', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 12:36:55', '2020-01-20 12:36:57'),
(36, 2, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '0978981571', '15', NULL, NULL, '260978981572', NULL, 20.00, 0.00, '1111111', '2020-01-20 00:00:00', 'Transaction in progress', '5e25afb7f564c02c85075199', '00', 'Transaction failed', '5e25afb7f564c02c85075199', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 13:48:38', '2020-01-20 13:48:39'),
(37, 2, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '0978981571', '15', NULL, NULL, '260978981572', NULL, 20.00, 0.00, '1111111', '2020-01-20 00:00:00', 'Transaction in progress', '5e25b06687726709379d297e', '00', 'Transaction failed', '5e25b06687726709379d297e', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 13:51:34', '2020-01-20 13:51:35'),
(38, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260979463748', '16', NULL, NULL, '260969240309', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-20 00:00:00', 'No virtual account found for NAPSA.', NULL, '02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 15:45:09', '2020-01-20 15:45:09'),
(39, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260978981576', '0', NULL, NULL, '260978981572', NULL, 1.00, 0.00, '1111111', '2020-01-20 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 15:52:03', '2020-01-20 15:52:03'),
(40, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 16:13:38', '2020-01-20 16:13:38'),
(41, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'No virtual account found for NAPSA.', NULL, '02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 16:16:10', '2020-01-20 16:16:11'),
(42, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 17:06:28', '2020-01-20 17:06:31'),
(43, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 17:06:31', '2020-01-20 17:06:32'),
(44, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260978981576', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 17:08:14', '2020-01-20 17:08:15'),
(45, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260972114496', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 17:43:58', '2020-01-20 17:43:59'),
(46, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260977617777', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-20 17:44:45', '2020-01-20 17:44:46'),
(47, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 09:45:35', '2020-01-21 09:45:36'),
(48, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260968309959', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'Transaction in progress', '5e26c8da63074740965dcebc', '00', 'Successfully processed transaction.', '5e26c8da63074740965dcebc', '00', '01', '1220176062', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-21 09:48:09', '2020-01-21 09:53:16'),
(49, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260979463748', '16', NULL, NULL, '260969240309', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 00:00:00', 'Transaction in progress', '5e26c9322ec74d235a370858', '00', 'Successfully processed transaction.', '5e26c9322ec74d235a370858', '00', '01', '1220178633', 'Requested service is not supported on AIRTELZM', NULL, '01', '', '', NULL, NULL, '2020-01-21 09:49:38', '2020-01-21 09:50:12'),
(50, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260977617777', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 09:53:44', '2020-01-21 09:53:45'),
(51, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:14:41', '2020-01-21 10:14:42'),
(52, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260968309959', '16', NULL, NULL, '260969240309', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 00:00:00', 'Transaction in progress', '5e26d062089ecd1f0e26ae81', '00', 'Transaction couldn\'t be completed', '5e26d062089ecd1f0e26ae81', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:20:17', '2020-01-21 10:26:37'),
(53, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:21:53', '2020-01-21 10:21:54'),
(54, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260968309959', '16', NULL, NULL, '260969240309', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:26:00', 'Transaction in progress', '5e26d1d78d46fc471b784a24', '00', 'Successfully processed transaction.', '5e26d1d78d46fc471b784a24', '00', '01', '1220241547', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-21 10:26:30', '2020-01-21 10:27:07'),
(55, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260968309959', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:26:00', 'Transaction in progress', '5e26d2b8ce09043017f553ff', '00', 'General failure.', '5e26d2b8ce09043017f553ff', '01', '100', '1220247767', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:30:16', '2020-01-21 10:36:16'),
(56, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260967485331', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:34:00', 'Transaction in progress', '5e26d3b751a3310bdac1270a', '00', 'Transaction couldn\'t be completed', '5e26d3b751a3310bdac1270a', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:34:30', '2020-01-21 10:40:50'),
(57, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260967485331', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:34:00', 'Transaction in progress', '5e26d43bab905e0b3aa6debb', '00', 'Successfully processed transaction.', '5e26d43bab905e0b3aa6debb', '00', '01', '1220258441', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-21 10:36:43', '2020-01-21 10:37:28'),
(58, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260968309959', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:38:00', 'Transaction in progress', '5e26d493089ecd1f0e26aff4', '00', 'Transaction couldn\'t be completed', '5e26d493089ecd1f0e26aff4', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:38:10', '2020-01-21 10:44:29'),
(59, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260968309959', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:42:00', 'Transaction in progress', '5e26d5aeab905e0b3aa6df3c', '00', 'Transaction couldn\'t be completed', '5e26d5aeab905e0b3aa6df3c', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:42:54', '2020-01-21 10:49:13'),
(60, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260968309959', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:42:00', 'Transaction in progress', '5e26d5ee089ecd1f0e26b084', '00', 'Transaction couldn\'t be completed', '5e26d5ee089ecd1f0e26b084', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:43:58', '2020-01-21 10:50:17'),
(61, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260968309959', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 12:47:00', 'Transaction in progress', '5e26d6b1089ecd1f0e26b0c3', '00', 'Transaction couldn\'t be completed', '5e26d6b1089ecd1f0e26b0c3', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 10:47:13', '2020-01-21 10:53:32'),
(62, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260968902755', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 13:11:00', 'Transaction in progress', '5e26dc807ff80910d6f595a9', '00', 'Transaction couldn\'t be completed', '5e26dc807ff80910d6f595a9', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 11:12:00', '2020-01-21 11:12:16'),
(63, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260973297682', '16', NULL, NULL, '260968902755', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 13:11:00', 'Transaction in progress', '5e26dd3863074740965dd61e', '00', 'Transaction couldn\'t be completed', '5e26dd3863074740965dd61e', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 11:15:03', '2020-01-21 11:15:20'),
(64, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260973297682', '16', NULL, NULL, '260968902755', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 13:11:00', 'Transaction in progress', '5e26ddd951a3310bdac12a7d', '00', 'Transaction couldn\'t be completed', '5e26ddd951a3310bdac12a7d', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 11:17:44', '2020-01-21 11:18:01'),
(65, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260973297682', '16', NULL, NULL, '260968902755', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 13:11:00', 'Transaction in progress', '5e26ddf663074740965dd66e', '00', 'Transaction couldn\'t be completed', '5e26ddf663074740965dd66e', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 11:18:13', '2020-01-21 11:18:30'),
(66, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '16', NULL, NULL, '260968309959', NULL, 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-21 13:11:00', 'Transaction in progress', '5e26fcfe63074740965de1cf', '00', 'General failure.', '5e26fcfe63074740965de1cf', '01', '100', '1220552296', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-21 13:30:38', '2020-01-21 13:36:16'),
(67, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260969240309', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'Transaction in progress', '5e2721208d46fc471b7867d4', '00', 'Successfully processed transaction.', '5e2721208d46fc471b7867d4', '00', '01', '1220860836', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-21 16:04:47', '2020-01-21 16:05:17'),
(68, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '0', NULL, NULL, '260967485331', NULL, 10.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'Transaction in progress', '5e2728c92ec74d235a372c29', '00', 'Successfully processed transaction.', '5e2728c92ec74d235a372c29', '00', '01', '1220932708', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-21 16:37:28', '2020-01-21 16:38:01'),
(69, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', NULL, NULL, '260969240309', '0', NULL, NULL, '260967485331', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'Transaction in progress', '5e27293051a3310bdac146e2', '00', 'Successfully processed transaction.', '5e27293051a3310bdac146e2', '00', '01', '1220936589', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-21 16:39:11', '2020-01-21 16:39:52'),
(70, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '260969240309', '17', NULL, NULL, '260967485331', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'Transaction in progress', '5e2b24977ff80910d6f69607', '00', 'Transaction couldn\'t be completed', '5e2b24977ff80910d6f69607', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-24 17:08:38', '2020-01-24 17:14:57'),
(71, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '260969240309', '17', NULL, NULL, '260967485331', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'Transaction in progress', '5e2b24e87ff80910d6f69626', '00', 'Successfully processed transaction.', '5e2b24e87ff80910d6f69626', '00', '01', '1225948154', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-24 17:10:00', '2020-01-24 17:10:31'),
(72, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '260969240309', '17', NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e2b2bc4ab905e0b3aa7e529', '00', 'Transaction couldn\'t be completed', '5e2b2bc4ab905e0b3aa7e529', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-24 17:39:15', '2020-01-24 17:45:35'),
(73, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '260969240309', '17', NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e2b2dca87726709379d3aa7', '00', 'Transaction failed', '5e2b2dca87726709379d3aa7', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-24 17:47:53', '2020-01-24 17:47:58'),
(74, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-25 19:30:01', '2020-01-25 19:30:02'),
(75, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-25 19:34:06', '2020-01-25 19:34:07'),
(76, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-25 19:34:09', '2020-01-25 19:34:10'),
(77, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '16', NULL, NULL, '260973297687', '17', NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e2cb4c187726709379d4076', '00', 'Transaction failed', '5e2cb4c187726709379d4076', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-25 21:36:00', '2020-01-25 21:36:06'),
(78, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-26 11:54:09', '2020-01-26 11:54:13'),
(79, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-26 11:54:12', '2020-01-26 11:54:13'),
(80, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020', NULL, NULL, '260967485331', NULL, NULL, NULL, '260955924419', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-27 15:08:28', '2020-01-27 15:08:29'),
(81, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020', NULL, NULL, '260967485331', NULL, NULL, NULL, '260979463748', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e2f064d87726709379d4753', '00', 'Transaction failed', '5e2f064d87726709379d4753', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-27 15:48:28', '2020-01-27 15:48:29'),
(82, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-6010222039-1-7800-26', NULL, NULL, '260967485331', NULL, NULL, NULL, '260955924419', NULL, 20.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-27 16:01:55', '2020-01-27 16:01:56'),
(83, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-6010222039-1-7800-26', NULL, NULL, '260967485331', NULL, NULL, NULL, '260979463748', NULL, 20.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e2f0b22f564c02c85076f81', '00', 'Transaction failed', '5e2f0b22f564c02c85076f81', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-27 16:09:05', '2020-01-27 16:09:06'),
(84, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-6010222039-1-7800-26', NULL, NULL, '260967485331', NULL, NULL, NULL, '260979463748', NULL, 20.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e2f0b2287726709379d476f', '00', 'Transaction failed', '5e2f0b2287726709379d476f', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-27 16:09:06', '2020-01-27 16:09:07'),
(85, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', 'Simon', 'Chiwamba', '260967485331', NULL, NULL, NULL, '260955924419', NULL, 2.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'ZAMTEL  services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 06:46:29', '2020-01-28 06:46:31'),
(86, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', 'Thomas', 'Hawatichke', '260979463748', '2020-5507753624-1-6071-27', 'Simon H.', 'Chiwamba', '260967485331', NULL, 10.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e30016ff564c02c8507723c', '00', 'Transaction failed', '5e30016ff564c02c8507723c', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 09:39:58', '2020-01-28 09:40:09'),
(87, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', NULL, '', NULL, '2020-2413931702-1-6177-28', 'Francis', 'UNZA', '260978981576', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e30117587726709379d4a6a', '00', 'Transaction failed', '5e30117587726709379d4a6a', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 10:48:21', '2020-01-28 10:48:25'),
(88, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', NULL, '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '0969240309', NULL, 'Francis', 'UNZA', '260978981576', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e3023aff564c02c850772ca', '00', 'Transaction failed', '5e3023aff564c02c850772ca', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 12:06:06', '2020-01-28 12:06:07'),
(89, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', NULL, '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '0969240309', '2020-2413931702-1-6177-28', 'Francis', 'UNZA', '260978981576', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e3024bbf564c02c850772d0', '00', 'Transaction failed', '5e3024bbf564c02c850772d0', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 12:10:34', '2020-01-28 12:10:36'),
(90, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', NULL, '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '0969240309', '2020-2413931702-1-6177-28', 'Francis', 'UNZA', '260978981576', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e30254f87726709379d4ac1', '00', 'Transaction failed', '5e30254f87726709379d4ac1', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 12:13:02', '2020-01-28 12:13:03'),
(91, 3, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260969240309', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e30646cbb4d160966767343', '00', 'Transaction successful.', '5e30646cbb4d160966767343', '00', '01', 'bb0de17ffc0a45c3a274125138aa3c1e', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 16:42:19', '2020-01-28 16:42:24'),
(92, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260973297682', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e3065819c1f58096542545e', '00', 'Transaction successful.', '5e3065819c1f58096542545e', '00', '01', '4ffee3486f0946f5a8b2923f059b7d5a', 'Transaction failed', '5e30658a9c1f58096542545f', '01', '', '', NULL, NULL, '2020-01-28 16:46:56', '2020-01-28 16:47:07'),
(93, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260968580098  ', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e3066669c1f580965425464', '00', 'Transaction successful.', '5e3066669c1f580965425464', '00', '01', '25d91b2005d34f3e98faa06e5022943e', 'Transaction failed', '5e3066679c1f580965425465', '01', '', '', NULL, NULL, '2020-01-28 16:50:46', '2020-01-28 16:50:51'),
(94, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260968580098', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e3066e69c1f580965425467', '00', 'Transaction successful.', '5e3066e69c1f580965425467', '00', '01', '5b0bedf3537448afaa068bcb5fac7227', 'Transaction successful', '5e3066e9bb4d16096676734f', '00', '01', 'cf7f24a821574064800a7ebb8f0e593d', NULL, NULL, '2020-01-28 16:52:53', '2020-01-28 16:53:00'),
(95, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260968580098', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e306825bb4d160966767354', '00', 'Transaction successful.', '5e306825bb4d160966767354', '00', '01', '7bbd7e781f1e45e5a9fad3c318986f05', 'Transaction successful', '5e30682ebb4d160966767355', '00', '01', 'f6748439b3914d708926f50f2cb84453', NULL, NULL, '2020-01-28 16:58:12', '2020-01-28 16:58:25'),
(96, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260968580098', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e306b1dbb4d160966767364', '00', 'Transaction successful.', '5e306b1dbb4d160966767364', '00', '01', '7c315ea4952f42b59e07e901d98354c2', 'Transaction successful', '5e306b22bb4d160966767365', '00', '01', '6978ca7c189449a88cc7012453c02673', NULL, NULL, '2020-01-28 17:10:53', '2020-01-28 17:11:02'),
(97, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'USSD', 'NRC', '111111/12/1', NULL, '27/01/2020', NULL, '', '', '', '', '', 'Francis', 'Chichi', '260978981576', '', 150.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e307239bb4d16096676737f', '00', 'Transaction failed', '5e307239bb4d16096676737f', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 17:41:12', '2020-01-28 17:41:13'),
(98, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'USSD', 'NRC', '111111/12/1', '5542479', '27/01/2020', NULL, '', '', '', '', '', 'Danny', 'Leza', '260977617777', '', 150.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e307779bb4d16096676739c', '00', 'Transaction failed', '5e307779bb4d16096676739c', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 18:03:36', '2020-01-28 18:03:37'),
(99, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-9203917562-1-8973-28', 'UNZA', 'TESTER', '260977617777', '', '', '', '260978981576', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e3078279c1f5809654254b6', '00', 'Transaction failed', '5e3078279c1f5809654254b6', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 18:06:30', '2020-01-28 18:06:31'),
(100, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-2413931702-1-6177-28', 'Francis', 'UNZA', '0978981576', '2020-9203917562-1-8973-28', 'UNZA', 'TESTER', '260977617777', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e3078689c1f5809654254b9', '00', 'Transaction failed', '5e3078689c1f5809654254b9', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 18:07:35', '2020-01-28 18:07:36'),
(101, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', 'Simon', 'Chiwamba', '260967485331', NULL, NULL, NULL, '260977981576', NULL, 20.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e30849bbb4d1609667673e8', '00', 'Transaction failed', '5e30849bbb4d1609667673e8', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 18:59:39', '2020-01-28 18:59:39'),
(102, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-2413931702-1-6177-28', 'Francis', 'UNZA', '260978981576', '2020-5507753624-1-6071-27', 'Simon H.', 'Chiwamba', '260967485331', NULL, 20.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3088edbb4d1609667673fe', '00', 'Transaction failed', '5e3088edbb4d1609667673fe', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 19:18:04', '2020-01-28 19:18:10'),
(103, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'USSD', 'NRC', '111111/12/1', '5542479', '27/01/2020', NULL, '', '', '', '', '', 'Francis', 'Chichi', '260977617777', '', 150.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e30a56bbb4d160966767474', '00', 'Transaction failed', '5e30a56bbb4d160966767474', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 21:19:39', '2020-01-28 21:19:39'),
(104, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'USSD', 'NRC', '111111/12/1', '5542479', '27/01/2020', NULL, '', '', '', '', '', 'Francis', 'Chichi', '260977617777', '', 150.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e30a651bb4d160966767477', '00', 'Transaction failed', '5e30a651bb4d160966767477', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 21:23:28', '2020-01-28 21:23:29'),
(105, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-9203917562-1-8973-28', 'UNZA', 'TESTER', '260977617777', '', '', '', '260978981576', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e30a73fbb4d16096676747b', '00', 'Transaction failed', '5e30a73fbb4d16096676747b', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 21:27:27', '2020-01-28 21:27:28'),
(106, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-2413931702-1-6177-28', 'Francis', 'UNZA', '0978981576', '2020-9203917562-1-8973-28', 'UNZA', 'TESTER', '260977617777', '', 1.00, 0.00, '1111111', '2020-01-28 00:00:00', 'Transaction in progress', '5e30a819bb4d16096676747f', '00', 'Transaction failed', '5e30a819bb4d16096676747f', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-28 21:31:04', '2020-01-28 21:31:05'),
(107, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260968580098', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e3139359c1f580965425659', '00', 'Transaction successful.', '5e3139359c1f580965425659', '00', '01', '4233d5c780b4445b8eacafe60026afdd', 'Transaction successful', '5e31393dbb4d160966767544', '00', '01', 'e7f0b1d6591e475cbcf661cef6949511', NULL, NULL, '2020-01-29 07:50:13', '2020-01-29 07:50:26'),
(108, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260968580097', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e3139719c1f58096542565c', '00', 'Transaction successful.', '5e3139719c1f58096542565c', '00', '01', '4068dc709d444755b6e4c0ac2a133fdf', 'Transaction failed', '5e3139769c1f58096542565d', '01', '', '', NULL, NULL, '2020-01-29 07:51:13', '2020-01-29 07:51:22'),
(109, 1, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260978580098', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e3139979c1f58096542565e', '00', 'Transaction successful.', '5e3139979c1f58096542565e', '00', '01', 'd138d76e010a443a8dfaa2279bb3ff4d', 'Transaction successful', '5e3139a09c1f58096542565f', '00', '01', '32b0630b08844d47a7ee32de3f1a4a10', NULL, NULL, '2020-01-29 07:51:50', '2020-01-29 07:52:03'),
(110, 3, NULL, 0, 'Sync Pending', 'LusakaLivingstone', 'API', 'NRC', '123456/00/1', NULL, '2020-01-28', NULL, '15', 'Simon', 'Marketeer', '260978580098', '16', 'Makonde', 'Buyer', '260762025989', 'makonde@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-01-27 11:17:00', 'Transaction in progress', '5e3139c8bb4d160966767549', '00', 'Transaction successful.', '5e3139c8bb4d160966767549', '00', '01', '1c067852a8664f6886b4d64fcf37976f', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-29 07:52:39', '2020-01-29 07:52:47'),
(111, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'USSD', 'NRC', '111111/12/1', '5542479', '27/01/2020', NULL, '', '', '', '', '', 'Francis', 'Chichi', '260977617777', '', 150.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e31563fbb4d1609667675d8', '00', 'Transaction failed', '5e31563fbb4d1609667675d8', '01', '', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-29 09:54:06', '2020-01-29 09:54:08');
INSERT INTO unza_transactions (`cart_id`, `transaction_type_id`, `external_trans_id`, `probase_status_code`, `probase_status_description`, `route_code`, `transaction_channel`, `id_type`, `passenger_id`, `bus_schedule_id`, `travel_date`, `travel_time`, `seller_id`, `seller_firstname`, `seller_lastname`, `seller_mobile_number`, `buyer_id`, `buyer_firstname`, `buyer_lastname`, `buyer_mobile_number`, `buyer_email`, `amount`, `transaction_fee`, `device_serial`, `transaction_date`, `debit_msg`, `debit_reference`, `debit_code`, `callback_msg`, `callback_reference`, `callback_code`, `callback_system_code`, `callback_transactionID`, `credit_msg`, `credit_reference`, `credit_code`, `credit_system_code`, `credit_transactionID`, `fina_status`, `fina_desc`, `date_created`, `date_modified`) VALUES
(112, 3, NULL, 0, 'Sync Pending', 'LVMG', 'USSD', 'NRC', '111111/12/1', '86500562', '29/01/2020', NULL, '', '', '', '', '', 'Francis', 'Chulu', '260969240309', '', 1.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e31682063074740966040ac', '00', 'Successfully processed transaction.', '5e31682063074740966040ac', '00', '01', '1233569618', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-29 11:10:24', '2020-01-29 11:11:00'),
(113, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-1806426821-1-4291-29', 'UNZA', 'TESTER', '260977948729', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e316a43089ecd1f0e291e6f', '00', 'Successfully processed transaction.', '5e316a43089ecd1f0e291e6f', '00', '01', '1233585633', 'Requested service is not supported on AIRTELZM', NULL, '01', '', '', NULL, NULL, '2020-01-29 11:19:30', '2020-01-29 11:20:03'),
(114, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '260968309959', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e316b468d46fc471b7ab9ee', '00', 'Successfully processed transaction.', '5e316b468d46fc471b7ab9ee', '00', '01', '1233593124', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-29 11:23:50', '2020-01-29 11:24:20'),
(115, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '0968309959', '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', 1.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e316f2d51a3310bdac39757', '00', 'Transaction couldn\'t be completed', '5e316f2d51a3310bdac39757', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-29 11:40:29', '2020-01-29 11:46:48'),
(116, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '0968309959', '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', 1.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e3170396307474096604368', '00', 'Successfully processed transaction.', '5e3170396307474096604368', '00', '01', '1233630196', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-29 11:44:57', '2020-01-29 11:45:26'),
(117, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'USSD', 'NRC', '111111/12/1', '96256327', '29/01/2020', NULL, '', '', '', '', '', 'Francis', 'Chulu', '260969240309', '', 1.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e317ce16307474096604828', '00', 'Successfully processed transaction.', '5e317ce16307474096604828', '00', '01', '1233724651', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-29 12:38:56', '2020-01-29 12:39:34'),
(118, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260968309959', '', 2.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e317e572ec74d235a3980a5', '00', 'Successfully processed transaction.', '5e317e572ec74d235a3980a5', '00', '01', '1233735677', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-01-29 12:45:11', '2020-01-29 12:46:25'),
(119, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '0968309959', '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', 2.00, 0.00, '1111111', '2020-01-29 00:00:00', 'Transaction in progress', '5e317f126307474096604909', '00', 'Transaction couldn\'t be completed', '5e317f126307474096604909', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-29 12:48:18', '2020-01-29 12:54:37'),
(120, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '2255555555555', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '0955924419', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-30 11:04:05', NULL),
(121, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '2566336', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '0967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-30 11:15:21', NULL),
(122, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '2566366', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Thomas', 'Chiwamba', '0967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-30 11:18:35', NULL),
(123, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '256666', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamb', '0967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-30 17:18:34', NULL),
(124, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '266336', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3311ad2ec74d235a39f0c2', '00', 'Transaction couldn\'t be completed', '5e3311ad2ec74d235a39f0c2', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-01-30 17:26:04', '2020-01-30 17:32:24'),
(125, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '0967485331', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3adca1089ecd1f0e2b8695', '00', 'Transaction couldn\'t be completed', '5e3adca1089ecd1f0e2b8695', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-05 15:17:53', '2020-02-05 15:24:12'),
(126, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '0967485331', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3ade827ff80910d6fa6aa0', '00', 'Successfully processed transaction.', '5e3ade827ff80910d6fa6aa0', '00', '01', '1246176909', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-05 15:25:54', '2020-02-05 15:26:34'),
(127, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', 'Simon', 'Chiwamba', '260979463748', NULL, NULL, NULL, '260967485331', NULL, 10.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3adf262ec74d235a3be41f', '00', 'Transaction couldn\'t be completed', '5e3adf262ec74d235a3be41f', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-05 15:28:38', '2020-02-05 15:34:57'),
(128, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-05 15:44:49', '2020-02-05 15:44:50'),
(129, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-05 15:57:22', '2020-02-05 15:57:23'),
(130, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '0967485331', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3aeca1630747409662b0a4', '00', 'Transaction couldn\'t be completed', '5e3aeca1630747409662b0a4', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-05 16:26:08', '2020-02-05 16:32:28'),
(131, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-05 16:56:02', '2020-02-05 16:56:03'),
(132, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3c01e67ff80910d6fab232', '00', 'Transaction couldn\'t be completed', '5e3c01e67ff80910d6fab232', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 12:09:09', '2020-02-06 12:15:29'),
(133, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, NULL, NULL, '260967485331', NULL, 0.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3c13feab905e0b3aac044b', '00', 'Transaction couldn\'t be completed', '5e3c13feab905e0b3aac044b', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 13:26:22', '2020-02-06 13:26:38'),
(134, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3c14698d46fc471b7d70f6', '00', 'Successfully processed transaction.', '5e3c14698d46fc471b7d70f6', '00', '01', '1247683760', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 13:28:09', '2020-02-06 13:28:58'),
(135, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, 0.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 13:37:18', '2020-02-06 13:37:20'),
(136, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3c177851a3310bdac64e8d', '00', 'Successfully processed transaction.', '5e3c177851a3310bdac64e8d', '00', '01', '1247708052', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 13:41:11', '2020-02-06 13:41:57'),
(137, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '099974585', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3c1ac87ff80910d6fabc67', '00', 'Successfully processed transaction.', '5e3c1ac87ff80910d6fabc67', '00', '01', '1247734354', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 13:55:20', '2020-02-06 13:56:03'),
(138, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3c1dfbce09043017fa7f60', '00', 'General failure.', '5e3c1dfbce09043017fa7f60', '01', '100', '1247760739', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 14:08:59', '2020-02-06 14:15:16'),
(139, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e3ca6b37ff80910d6fae7c9', '00', 'Transaction couldn\'t be completed', '5e3ca6b37ff80910d6fae7c9', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 23:52:19', '2020-02-06 23:58:38'),
(140, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 23:56:45', '2020-02-06 23:56:47'),
(141, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-06 23:56:46', '2020-02-06 23:56:47'),
(142, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '0979463748', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'SIMON', 'CHIWAMBA', '260979463748', 'JJJJJJ.VMMV', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-07 07:01:28', '2020-02-07 07:01:30'),
(143, 3, NULL, 0, 'Sync Pending', 'LVMZ', 'API', 'NRC', '09099999', NULL, '27/01/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'simon', 'simon', '260979463748', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'AIRTELZM services are not supported', NULL, '01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-07 07:28:45', '2020-02-07 07:28:46'),
(144, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-19 00:00:00', 'Transaction in progress', '5e4cf22d089ecd1f0e2fd97f', '00', 'Successfully processed transaction.', '5e4cf22d089ecd1f0e2fd97f', '00', '01', '1268611040', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 08:30:36', '2020-02-19 08:31:11'),
(145, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4cf3552ec74d235a4035bb', '00', 'Successfully processed transaction.', '5e4cf3552ec74d235a4035bb', '00', '01', '1268619724', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 08:35:33', '2020-02-19 08:35:59'),
(146, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4cf585ce09043017fe80c5', '00', 'Successfully processed transaction.', '5e4cf585ce09043017fe80c5', '00', '01', '1268635787', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 08:44:53', '2020-02-19 08:46:01'),
(147, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-1140070440-2-0796-19', 'null', 'null', '260969240309', '2020-0385313085-2-5165-19', NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d150ece09043017fe8c1c', '00', 'Successfully processed transaction.', '5e4d150ece09043017fe8c1c', '00', '01', '1268859245', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 10:59:25', '2020-02-19 11:00:02'),
(148, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-1140070440-2-0796-19', 'null', 'null', '260969240309', '2020-0385313085-2-5165-19', NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d175b089ecd1f0e2fe6a1', '00', 'Transaction couldn\'t be completed', '5e4d175b089ecd1f0e2fe6a1', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 11:09:15', '2020-02-19 11:15:34'),
(149, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-1140070440-2-0796-19', 'null', 'null', '260969240309', '2020-0385313085-2-5165-19', NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d3515ab905e0b3ab0242c', '00', 'Transaction couldn\'t be completed', '5e4d3515ab905e0b3ab0242c', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 13:16:05', '2020-02-19 13:16:21'),
(150, 3, NULL, 0, 'Sync Pending', 'LV2LSK', 'API', 'NRC', '236975681', NULL, '20/02/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 200.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d35d2ce09043017fe9828', '00', 'Transaction couldn\'t be completed', '5e4d35d2ce09043017fe9828', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 13:19:13', '2020-02-19 13:19:30'),
(151, 3, NULL, 0, 'Sync Pending', 'LV2LSK', 'API', 'NRC', '236974681', NULL, '20/02/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 200.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d36427ff80910d6fed8d5', '00', 'Transaction couldn\'t be completed', '5e4d36427ff80910d6fed8d5', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 13:21:06', '2020-02-19 13:21:22'),
(152, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 2.00, 0.00, '1111111', '2020-02-19 00:00:00', 'Transaction in progress', '5e4d3c3ece09043017fe9a39', '00', 'Transaction couldn\'t be completed', '5e4d3c3ece09043017fe9a39', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 13:46:37', '2020-02-19 13:46:54'),
(153, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '260968309959', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-02-19 00:00:00', 'Transaction in progress', '5e4d3cdb7ff80910d6fedb23', '00', 'Transaction couldn\'t be completed', '5e4d3cdb7ff80910d6fedb23', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 13:49:14', '2020-02-19 13:49:31'),
(154, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d3d2fab905e0b3ab026fc', '00', 'Transaction couldn\'t be completed', '5e4d3d2fab905e0b3ab026fc', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 13:50:38', '2020-02-19 13:50:55'),
(155, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d3f0d51a3310bdaca6dc0', '00', 'Successfully processed transaction.', '5e4d3f0d51a3310bdaca6dc0', '00', '01', '1269050043', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 13:58:37', '2020-02-19 13:59:35'),
(156, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4d3f61089ecd1f0e2ff599', '00', 'Successfully processed transaction.', '5e4d3f61089ecd1f0e2ff599', '00', '01', '1269054199', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 14:00:01', '2020-02-19 14:00:33'),
(157, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-19 00:00:00', 'Transaction in progress', '5e4d3f9a7ff80910d6fedc1e', '00', 'Successfully processed transaction.', '5e4d3f9a7ff80910d6fedc1e', '00', '01', '1269057009', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-19 14:00:58', '2020-02-19 14:01:33'),
(158, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e45a663074740966753c3', '00', 'Transaction couldn\'t be completed', '5e4e45a663074740966753c3', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 08:39:01', '2020-02-20 08:45:21'),
(159, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e46697ff80910d6ff17b4', '00', 'Transaction couldn\'t be completed', '5e4e46697ff80910d6ff17b4', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 08:42:17', '2020-02-20 08:48:36'),
(160, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e46a5ab905e0b3ab06306', '00', 'General failure.', '5e4e46a5ab905e0b3ab06306', '01', '100', '1270140031', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 08:43:16', '2020-02-20 08:49:18'),
(161, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e473f51a3310bdacaaa15', '00', 'Transaction couldn\'t be completed', '5e4e473f51a3310bdacaaa15', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 08:45:51', '2020-02-20 08:52:10'),
(162, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e47ef8d46fc471b81ce44', '00', 'Transaction couldn\'t be completed', '5e4e47ef8d46fc471b81ce44', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 08:48:46', '2020-02-20 08:55:06'),
(163, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', NULL, NULL, '260967485331', NULL, NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e4a8b7ff80910d6ff1944', '00', 'Transaction couldn\'t be completed', '5e4e4a8b7ff80910d6ff1944', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 08:59:55', '2020-02-20 09:06:14'),
(164, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', NULL, NULL, '260967485331', NULL, NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e4d8d8d46fc471b81d097', '00', 'Successfully processed transaction.', '5e4e4d8d8d46fc471b81d097', '00', '01', '1270191882', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:12:45', '2020-02-20 09:13:11'),
(165, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', NULL, NULL, '260967485331', NULL, NULL, NULL, '26260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:24:27', NULL),
(166, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e511b7ff80910d6ff1c4c', '00', 'Transaction couldn\'t be completed', '5e4e511b7ff80910d6ff1c4c', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:27:55', '2020-02-20 09:34:14'),
(167, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', NULL, NULL, '260967485331', NULL, NULL, NULL, '26260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:28:41', NULL),
(168, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e51e46307474096675911', '00', 'Successfully processed transaction.', '5e4e51e46307474096675911', '00', '01', '1270224120', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:31:16', '2020-02-20 09:31:42'),
(169, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', NULL, NULL, '260967485331', NULL, NULL, NULL, '260967240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e520a089ecd1f0e3035d6', '00', 'Transaction couldn\'t be completed', '5e4e520a089ecd1f0e3035d6', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:31:53', '2020-02-20 09:32:10'),
(170, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5507753624-1-6071-27', NULL, NULL, '260967485331', NULL, NULL, NULL, '260967240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e524a7ff80910d6ff1cb9', '00', 'Transaction couldn\'t be completed', '5e4e524a7ff80910d6ff1cb9', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:32:58', '2020-02-20 09:33:15'),
(171, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e557e6307474096675a81', '00', 'Successfully processed transaction.', '5e4e557e6307474096675a81', '00', '01', '1270250547', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 09:46:38', '2020-02-20 09:47:11'),
(172, 3, NULL, 0, 'Sync Pending', 'LVMG', 'API', 'NRC', '967485681', NULL, '20/02/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 45.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e59bd7ff80910d6ff1f76', '00', 'Transaction couldn\'t be completed', '5e4e59bd7ff80910d6ff1f76', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 10:04:45', '2020-02-20 10:11:04'),
(173, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e5a5d2ec74d235a40963e', '00', 'Successfully processed transaction.', '5e4e5a5d2ec74d235a40963e', '00', '01', '1270286495', 'Successfully processed transaction.', '5e4e5a8a7ff80910d6ff1fd0', '00', '01', '1270287339', NULL, NULL, '2020-02-20 10:07:24', '2020-02-20 10:08:14'),
(174, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '260968309959', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e5aed2ec74d235a40967f', '00', 'Successfully processed transaction.', '5e4e5aed2ec74d235a40967f', '00', '01', '1270290146', 'Successfully processed transaction.', '5e4e5b1b7ff80910d6ff2010', '00', '01', '1270291418', NULL, NULL, '2020-02-20 10:09:49', '2020-02-20 10:10:39'),
(175, 3, NULL, 0, 'Sync Pending', 'LVCH', 'API', 'NRC', '967485681', NULL, '20/02/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e5c927ff80910d6ff20b1', '00', 'Successfully processed transaction.', '5e4e5c927ff80910d6ff20b1', '00', '01', '1270301912', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 10:16:50', '2020-02-20 10:17:29'),
(176, 3, NULL, 0, 'Sync Pending', 'LVCH', 'API', 'NRC', '967485681', NULL, '20/02/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e5e43ab905e0b3ab06cdb', '00', 'Transaction couldn\'t be completed', '5e4e5e43ab905e0b3ab06cdb', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 10:24:02', '2020-02-20 10:30:22'),
(177, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3202220565-1-4518-27', NULL, NULL, '260979463748', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e618a51a3310bdacab4a9', '00', 'Successfully processed transaction.', '5e4e618a51a3310bdacab4a9', '00', '01', '1270337416', 'Requested service is not supported on AIRTELZM', NULL, '01', '', '', NULL, NULL, '2020-02-20 10:38:01', '2020-02-20 10:38:35'),
(178, 3, NULL, 0, 'Sync Pending', 'LVMG', 'API', 'NRC', '967485681', NULL, '20/02/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 45.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e627751a3310bdacab504', '00', 'Transaction couldn\'t be completed', '5e4e627751a3310bdacab504', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 10:41:59', '2020-02-20 10:48:18'),
(179, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 10.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e67938d46fc471b81db35', '00', 'Successfully processed transaction.', '5e4e67938d46fc471b81db35', '00', '01', '1270380145', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-20 11:03:46', '2020-02-20 11:04:20'),
(180, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e6885ce09043017fee3b0', '00', 'Successfully processed transaction.', '5e4e6885ce09043017fee3b0', '00', '01', '1270386920', 'Successfully processed transaction.', '5e4e68a3ce09043017fee3bd', '00', '01', '1270387763', NULL, NULL, '2020-02-20 11:07:49', '2020-02-20 11:08:23'),
(181, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260968309959', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e697c7ff80910d6ff25d7', '00', 'Successfully processed transaction.', '5e4e697c7ff80910d6ff25d7', '00', '01', '1270393915', 'Successfully processed transaction.', '5e4e699a51a3310bdacab7b6', '00', '01', '1270394810', NULL, NULL, '2020-02-20 11:11:55', '2020-02-20 11:12:29'),
(182, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260968309959', '', 13.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e6afe089ecd1f0e303ff1', '00', 'Transaction couldn\'t be completed', '5e4e6afe089ecd1f0e303ff1', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 11:18:22', '2020-02-20 11:18:38'),
(183, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260968309959', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e6b5e7ff80910d6ff269e', '00', 'Transaction couldn\'t be completed', '5e4e6b5e7ff80910d6ff269e', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 11:19:58', '2020-02-20 11:20:15'),
(184, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260968309959', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e6d1ece09043017fee58a', '00', 'Transaction couldn\'t be completed', '5e4e6d1ece09043017fee58a', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 11:27:26', '2020-02-20 11:27:43'),
(185, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260968309959', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e6d64089ecd1f0e3040e2', '00', 'Transaction couldn\'t be completed', '5e4e6d64089ecd1f0e3040e2', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 11:28:36', '2020-02-20 11:28:52'),
(186, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e6dd451a3310bdacab976', '00', 'Successfully processed transaction.', '5e4e6dd451a3310bdacab976', '00', '01', '1270425775', 'Successfully processed transaction.', '5e4e6df38d46fc471b81ddcb', '00', '01', '1270426626', NULL, NULL, '2020-02-20 11:30:28', '2020-02-20 11:31:02'),
(187, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e6e9b8d46fc471b81de11', '00', 'Successfully processed transaction.', '5e4e6e9b8d46fc471b81de11', '00', '01', '1270431218', 'Successfully processed transaction.', '5e4e6eba2ec74d235a409e5e', '00', '01', '1270432037', NULL, NULL, '2020-02-20 11:33:46', '2020-02-20 11:34:21'),
(188, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '0969240309', '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e70e6ce09043017fee701', '00', 'Successfully processed transaction.', '5e4e70e6ce09043017fee701', '00', '01', '1270447323', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 11:43:33', '2020-02-20 11:44:20'),
(189, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '0969240309', '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e719f2ec74d235a409f66', '00', 'Transaction couldn\'t be completed', '5e4e719f2ec74d235a409f66', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 11:46:39', '2020-02-20 11:52:58'),
(190, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', 1.00, 0.00, '1111111', '2020-02-20 00:00:00', 'Transaction in progress', '5e4e72a651a3310bdacabb29', '00', 'Successfully processed transaction.', '5e4e72a651a3310bdacabb29', '00', '01', '1270459232', 'Successfully processed transaction.', '5e4e72c87ff80910d6ff2987', '00', '01', '1270460145', NULL, NULL, '2020-02-20 11:51:02', '2020-02-20 11:51:39'),
(191, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260968309959', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e74f08d46fc471b81e072', '00', 'Transaction couldn\'t be completed', '5e4e74f08d46fc471b81e072', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 12:00:48', '2020-02-20 12:07:07'),
(192, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260968309959', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e7553ce09043017fee890', '00', 'Transaction couldn\'t be completed', '5e4e7553ce09043017fee890', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 12:02:26', '2020-02-20 12:08:46'),
(193, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260968309959', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e757dab905e0b3ab075bd', '00', 'Successfully processed transaction.', '5e4e757dab905e0b3ab075bd', '00', '01', '1270479082', 'Successfully processed transaction.', '5e4e75bb7ff80910d6ff2a87', '00', '01', '1270480778', NULL, NULL, '2020-02-20 12:03:09', '2020-02-20 12:04:14'),
(194, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '260968309959', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e75e02ec74d235a40a0e1', '00', 'Successfully processed transaction.', '5e4e75e02ec74d235a40a0e1', '00', '01', '1270481736', 'Successfully processed transaction.', '5e4e76037ff80910d6ff2aaf', '00', '01', '1270482667', NULL, NULL, '2020-02-20 12:04:47', '2020-02-20 12:05:26'),
(195, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260968309959', NULL, 10.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e76402ec74d235a40a10a', '00', 'Successfully processed transaction.', '5e4e76402ec74d235a40a10a', '00', '01', '1270484308', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-20 12:06:24', '2020-02-20 12:06:56'),
(196, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e8659089ecd1f0e304a96', '00', 'Transaction couldn\'t be completed', '5e4e8659089ecd1f0e304a96', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 13:15:05', '2020-02-20 13:21:24'),
(197, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e8fc3089ecd1f0e304ea4', '00', 'Transaction couldn\'t be completed', '5e4e8fc3089ecd1f0e304ea4', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 13:55:15', '2020-02-20 14:01:34'),
(198, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-5593147455-2-2765-4', 'Malambo', 'Chiwamba', '260967485330', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4e9096ab905e0b3ab08102', '00', 'Transaction couldn\'t be completed', '5e4e9096ab905e0b3ab08102', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-20 13:58:46', '2020-02-20 14:05:05'),
(199, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f6e6a8d46fc471b821f32', '00', 'Successfully processed transaction.', '5e4f6e6a8d46fc471b821f32', '00', '01', '1271462868', 'Successfully processed transaction.', '5e4f6e8651a3310bdacafa1d', '00', '01', '1271463374', NULL, NULL, '2020-02-21 05:45:13', '2020-02-21 05:45:45'),
(200, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f6ea17ff80910d6ff6934', '00', 'Successfully processed transaction.', '5e4f6ea17ff80910d6ff6934', '00', '01', '1271463850', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 05:46:09', '2020-02-21 05:46:40'),
(201, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-5657610546-1-5137-29', 'Makonde', 'TESTER', '260968309959', '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f6ee2089ecd1f0e308265', '00', 'Successfully processed transaction.', '5e4f6ee2089ecd1f0e308265', '00', '01', '1271464942', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 05:47:14', '2020-02-21 05:47:46'),
(202, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f6f0c630747409667a60a', '00', 'Successfully processed transaction.', '5e4f6f0c630747409667a60a', '00', '01', '1271465641', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 05:47:56', '2020-02-21 05:48:50'),
(203, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260697485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-21 05:50:22', NULL),
(204, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f6fca7ff80910d6ff6949', '00', 'Successfully processed transaction.', '5e4f6fca7ff80910d6ff6949', '00', '01', '1271469058', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 05:51:06', '2020-02-21 05:51:36'),
(205, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f710cce09043017ff2800', '00', 'Successfully processed transaction.', '5e4f710cce09043017ff2800', '00', '01', '1271475113', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 05:56:27', '2020-02-21 05:56:58'),
(206, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f7179ab905e0b3ab0b516', '00', 'Successfully processed transaction.', '5e4f7179ab905e0b3ab0b516', '00', '01', '1271477208', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 05:58:16', '2020-02-21 05:58:45'),
(207, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f7233ce09043017ff283c', '00', 'Successfully processed transaction.', '5e4f7233ce09043017ff283c', '00', '01', '1271480780', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 06:01:23', '2020-02-21 06:01:56'),
(208, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f7386ab905e0b3ab0b57c', '00', 'Successfully processed transaction.', '5e4f7386ab905e0b3ab0b57c', '00', '01', '1271487783', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 06:07:02', '2020-02-21 06:07:33'),
(209, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f755cce09043017ff28cc', '00', 'Successfully processed transaction.', '5e4f755cce09043017ff28cc', '00', '01', '1271497846', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 06:14:51', '2020-02-21 06:15:22'),
(210, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f786651a3310bdacafbc0', '00', 'Successfully processed transaction.', '5e4f786651a3310bdacafbc0', '00', '01', '1271515678', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 06:27:50', '2020-02-21 06:28:21'),
(211, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f7cd1630747409667a894', '00', 'Transaction couldn\'t be completed', '5e4f7cd1630747409667a894', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-21 06:46:41', '2020-02-21 06:53:00'),
(212, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f7ce6ab905e0b3ab0b73f', '00', 'Transaction couldn\'t be completed', '5e4f7ce6ab905e0b3ab0b73f', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-21 06:47:02', '2020-02-21 06:53:21'),
(213, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f7d01089ecd1f0e308526', '00', 'Successfully processed transaction.', '5e4f7d01089ecd1f0e308526', '00', '01', '1271545130', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 06:47:28', '2020-02-21 06:48:00'),
(214, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f7fb22ec74d235a40e313', '00', 'Successfully processed transaction.', '5e4f7fb22ec74d235a40e313', '00', '01', '1271562730', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 06:58:57', '2020-02-21 06:59:51'),
(215, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f7feece09043017ff2adc', '00', 'Successfully processed transaction.', '5e4f7feece09043017ff2adc', '00', '01', '1271564266', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 06:59:58', '2020-02-21 07:01:07');
INSERT INTO unza_transactions (`cart_id`, `transaction_type_id`, `external_trans_id`, `probase_status_code`, `probase_status_description`, `route_code`, `transaction_channel`, `id_type`, `passenger_id`, `bus_schedule_id`, `travel_date`, `travel_time`, `seller_id`, `seller_firstname`, `seller_lastname`, `seller_mobile_number`, `buyer_id`, `buyer_firstname`, `buyer_lastname`, `buyer_mobile_number`, `buyer_email`, `amount`, `transaction_fee`, `device_serial`, `transaction_date`, `debit_msg`, `debit_reference`, `debit_code`, `callback_msg`, `callback_reference`, `callback_code`, `callback_system_code`, `callback_transactionID`, `credit_msg`, `credit_reference`, `credit_code`, `credit_system_code`, `credit_transactionID`, `fina_status`, `fina_desc`, `date_created`, `date_modified`) VALUES
(216, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f800cce09043017ff2ae5', '00', 'Successfully processed transaction.', '5e4f800cce09043017ff2ae5', '00', '01', '1271565056', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:00:28', '2020-02-21 07:02:57'),
(217, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f807f2ec74d235a40e351', '00', 'Successfully processed transaction.', '5e4f807f2ec74d235a40e351', '00', '01', '1271568047', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:02:23', '2020-02-21 07:03:09'),
(218, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f8099089ecd1f0e3085fc', '00', 'Successfully processed transaction.', '5e4f8099089ecd1f0e3085fc', '00', '01', '1271568682', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:02:49', '2020-02-21 07:03:52'),
(219, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f80e92ec74d235a40e370', '00', 'Successfully processed transaction.', '5e4f80e92ec74d235a40e370', '00', '01', '1271570745', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:04:09', '2020-02-21 07:04:46'),
(220, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f8148630747409667a9bf', '00', 'Successfully processed transaction.', '5e4f8148630747409667a9bf', '00', '01', '1271573377', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:05:43', '2020-02-21 07:06:53'),
(221, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f81ae2ec74d235a40e39e', '00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-21 07:07:26', '2020-02-21 07:07:26'),
(222, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f81f92ec74d235a40e3ae', '00', 'Successfully processed transaction.', '5e4f81f92ec74d235a40e3ae', '00', '01', '1271578127', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:08:41', '2020-02-21 07:09:15'),
(223, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f824351a3310bdacafdfc', '00', 'Successfully processed transaction.', '5e4f824351a3310bdacafdfc', '00', '01', '1271580103', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:09:55', '2020-02-21 07:10:25'),
(224, 2, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f82ae8d46fc471b82233c', '00', 'Successfully processed transaction.', '5e4f82ae8d46fc471b82233c', '00', '01', '1271583139', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:11:42', '2020-02-21 07:12:20'),
(225, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f8388ce09043017ff2bce', '00', 'Successfully processed transaction.', '5e4f8388ce09043017ff2bce', '00', '01', '1271589060', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:15:19', '2020-02-21 07:15:52'),
(226, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f8429089ecd1f0e3086f0', '00', 'Successfully processed transaction.', '5e4f8429089ecd1f0e3086f0', '00', '01', '1271593630', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:18:00', '2020-02-21 07:18:30'),
(227, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f84da089ecd1f0e308720', '00', 'Successfully processed transaction.', '5e4f84da089ecd1f0e308720', '00', '01', '1271598633', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:20:58', '2020-02-21 07:23:39'),
(228, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f857f51a3310bdacafed9', '00', 'Successfully processed transaction.', '5e4f857f51a3310bdacafed9', '00', '01', '1271603088', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:23:42', '2020-02-21 07:24:17'),
(229, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f85c9089ecd1f0e308762', '00', 'Transaction couldn\'t be completed', '5e4f85c9089ecd1f0e308762', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-21 07:24:57', '2020-02-21 07:31:16'),
(230, 2, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-0812387178-1-1287-28', 'TEST', 'MTN', '260969240309', '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f85f3089ecd1f0e308770', '00', 'Successfully processed transaction.', '5e4f85f3089ecd1f0e308770', '00', '01', '1271606233', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:25:39', '2020-02-21 07:26:10'),
(231, 3, NULL, 0, 'Sync Pending', 'LV2LS', 'API', 'NRC', '967485681', NULL, '21/02/2020', NULL, NULL, NULL, NULL, NULL, NULL, 'Simon', 'Chiwamba', '260967485331', 'shchiwamba@yahoo.com', 200.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f866d089ecd1f0e308794', '00', 'Transaction couldn\'t be completed', '5e4f866d089ecd1f0e308794', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-21 07:27:41', '2020-02-21 07:34:00'),
(232, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', '', '', '260967485331', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f876851a3310bdacaff6a', '00', 'Successfully processed transaction.', '5e4f876851a3310bdacaff6a', '00', '01', '1271616859', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 07:31:52', '2020-02-21 07:32:24'),
(233, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', '', '', '260969240309', '', 1.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f9038ab905e0b3ab0bcc5', '00', 'Successfully processed transaction.', '5e4f9038ab905e0b3ab0bcc5', '00', '01', '1271681881', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 08:09:27', '2020-02-21 08:09:58'),
(234, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967458331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f90d9ab905e0b3ab0bd04', '00', 'Transaction couldn\'t be completed', '5e4f90d9ab905e0b3ab0bd04', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-02-21 08:12:08', '2020-02-21 08:12:25'),
(235, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f90f7630747409667ae67', '00', 'Successfully processed transaction.', '5e4f90f7630747409667ae67', '00', '01', '1271687920', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 08:12:39', '2020-02-21 08:13:08'),
(236, 1, NULL, 0, 'Sync Pending', NULL, 'API', NULL, NULL, NULL, NULL, NULL, '2020-0812387178-1-1287-28', NULL, NULL, '260969240309', NULL, NULL, NULL, '260967485331', NULL, 1.00, 0.00, '210ac6bf1d25ffdf', '0000-00-00 00:00:00', 'Transaction in progress', '5e4f9170630747409667ae93', '00', 'Successfully processed transaction.', '5e4f9170630747409667ae93', '00', '01', '1271691526', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 08:14:39', '2020-02-21 08:15:07'),
(237, 1, NULL, 0, 'Sync Pending', '', 'USSD', 'NRC', '', '', '', NULL, '2020-3676257032-2-8166-20', 'Gladys', 'Chibwe', '260964692323', '', '', '', '260967070808', '', 15.00, 0.00, '1111111', '2020-02-21 00:00:00', 'Transaction in progress', '5e4f9368ce09043017ff30e4', '00', 'Successfully processed transaction.', '5e4f9368ce09043017ff30e4', '00', '01', '1271707024', 'Institution resources are insufficient to perform this operation.', NULL, '02', '', '', NULL, NULL, '2020-02-21 08:23:04', '2020-02-21 08:23:40'),
(238, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '17', NULL, NULL, '260967485331', '0', NULL, NULL, '260966855355', NULL, 1.00, 0.00, 'f69144e8ac1cbc8d', '0000-00-00 00:00:00', 'Transaction in progress', '5e727b5a526aa76b6b04bb2f', '00', 'Transaction couldn\'t be completed', '5e727b5a526aa76b6b04bb2f', '01', '100', '', NULL, NULL, NULL, '', '', NULL, NULL, '2020-03-18 19:49:46', '2020-03-18 19:56:05'),
(239, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15', 'Francis', 'Marketeer', '2600969240309', '16', 'Simon', 'Buyer', '2600967485331', 'user@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-04-03 14:34:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-04-03 14:47:21', NULL),
(240, 1, NULL, 0, 'Sync Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '16', 'Simon', 'Marketeer', '260967485331', '15', 'Francis', 'Buyer', '260969240309', 'user@domain.com', 1.00, 0.00, 'SAMSUNG-J5PRO', '2020-04-03 15:34:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, '2020-04-03 15:15:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_summaries_logs`
--

CREATE TABLE `transaction_summaries_logs` (
  `id` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `debit_reference` varchar(250) NOT NULL,
  `debit_request` text,
  `debit_request_time` datetime DEFAULT NULL,
  `debit_response` text,
  `debit_response_time` datetime DEFAULT NULL,
  `debit_callback_response` text,
  `debit_callback_response_time` datetime DEFAULT NULL,
  `credit_reference` varchar(250) DEFAULT NULL,
  `credit_request` text,
  `credit_request_time` datetime DEFAULT NULL,
  `credit_response` text,
  `credit_response_time` datetime DEFAULT NULL,
  `credit_callback_response` text,
  `credit_callback_response_time` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

INSERT INTO unza_transaction_types (`transaction_type_id`, `name`, `description`, `date_created`, `date_modified`) VALUES
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

INSERT INTO unza_users (`user_id`, `role_id`, `firstname`, `lastname`, `nrc`, `gender`, `dob`, `mobile_number`, `email`, `password`, `token_balance`, `account_number`, `verification_token`, `password_reset_token`, `auth_key`, `status`, `created_by`, `updated_by`, `date_created`, `date_updated`) VALUES
(1, 1, 'Francis', 'Chulu C', '111111', 'Male', '1978-11-29', '260969240309', 'chulu1francis@gmail.com', '$2y$13$bvhLW881SbIuZvY9M1Rgf.E5exSZManCymyHWPF5Jw41wEcAO1wPS', 0.00, '111111', 'gHn4kQcKjfq55mM9kiEzVCir_3Rvs8VK_1562149047', NULL, 'CbART1y2XkwfYkCNCk_gCFi3hSXUx7U1', '1', 1, 1, '2019-11-05 00:00:00', '2020-01-21 14:33:20'),
(5, 2, 'Chishala', 'Chulu', '111111', 'Male', '2016-03-16', '0969240309', 'francis.chulu@unza.zm', '$2y$13$M9ceiZrYNhysoORQpLe75Oi/so058Nf8oAEt4Sy/T4yx4t2CgInFa', 0.00, NULL, NULL, NULL, 'D-Hf8AnNVt2dPtADnmJ8oq4e0syK_4nc', '1', 1, 1, '2019-11-08 12:35:16', '2019-11-08 10:50:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_config`
--
ALTER TABLE unza_api_config
  ADD PRIMARY KEY (`api_config_id`),
  ADD UNIQUE KEY `application_name` (`application_name`);

--
-- Indexes for table `image`
--
ALTER TABLE unza_image
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_charges`
--
ALTER TABLE `market_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_charges_details`
--
ALTER TABLE `market_charges_details`
  ADD PRIMARY KEY (`transaction_details_id`);

--
-- Indexes for table `market_charges_summaries`
--
ALTER TABLE `market_charges_summaries`
  ADD PRIMARY KEY (`tx_summary_id`);

--
-- Indexes for table `market_notifications`
--
ALTER TABLE unza_market_notifications
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE unza_permissions
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_to_roles`
--
ALTER TABLE unza_permission_to_roles
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE unza_roles
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE unza_sms_logs
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `traders`
--
ALTER TABLE unza_traders
  ADD PRIMARY KEY (`trader_id`),
  ADD UNIQUE KEY `firstname` (`firstname`,`lastname`),
  ADD UNIQUE KEY `mobile_number` (`mobile_number`),
  ADD UNIQUE KEY `firstname_2` (`firstname`,`lastname`,`mobile_number`);

--
-- Indexes for table `transaction_summaries`
--
ALTER TABLE unza_transactions
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `transaction_summaries_logs`
--
ALTER TABLE unza_transaction_logs
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_types`
--
ALTER TABLE unza_transaction_types
  ADD PRIMARY KEY (`transaction_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE unza_users
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
ALTER TABLE unza_api_config
  MODIFY `api_config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE unza_image
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `market_charges`
--
ALTER TABLE `market_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `market_charges_details`
--
ALTER TABLE `market_charges_details`
  MODIFY `transaction_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_charges_summaries`
--
ALTER TABLE `market_charges_summaries`
  MODIFY `tx_summary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_notifications`
--
ALTER TABLE unza_market_notifications
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE unza_permissions
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `permission_to_roles`
--
ALTER TABLE unza_permission_to_roles
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE unza_roles
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE unza_sms_logs
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `traders`
--
ALTER TABLE unza_traders
  MODIFY `trader_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transaction_summaries`
--
ALTER TABLE unza_transactions
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `transaction_summaries_logs`
--
ALTER TABLE unza_transaction_logs
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_types`
--
ALTER TABLE unza_transaction_types
  MODIFY `transaction_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE unza_users
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
