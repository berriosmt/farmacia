-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: us-cdbr-east-03.cleardb.com
-- Generation Time: May 14, 2021 at 01:41 PM
-- Server version: 5.6.50-log
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `heroku_f4825e3f20c25fd`
--

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(7,2) NOT NULL,
  `imagen` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `imagen`) VALUES
(1, 'Panadol', '4.97', 'images/panadol.png'),
(2, 'Advil', '5.56', 'images/advil.png'),
(3, 'Band-aid', '5.46', 'images/band-aid.png'),
(4, 'Swan Rubbing Alcohol', '8.32', 'images/SwanAlcohol.png'),
(14, 'Germ-X Hand Sanitizer', '1.93', 'images/Germ-X.png'),
(24, 'Tylenol ', '9.45', 'images/tylenolExtra.png');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(255) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(30) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_quantity` varchar(255) NOT NULL,
  `item_mc_gross` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `address_street` varchar(255) NOT NULL,
  `address_city` varchar(255) NOT NULL,
  `address_state` varchar(255) NOT NULL,
  `address_zip` varchar(255) NOT NULL,
  `address_country` varchar(255) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `txn_id` (`txn_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `txn_id`, `payment_amount`, `payment_status`, `item_id`, `item_name`, `item_quantity`, `item_mc_gross`, `created`, `payer_email`, `first_name`, `last_name`, `address_street`, `address_city`, `address_state`, `address_zip`, `address_country`, `id_usuario`) VALUES
(74, '5CA50696BX858145M', '1.74', 'Pending', '2', 'Advil', '1', '1.56', '2021-05-06 23:01:14', 'sb-pnude6123499@personal.example.com', 'John', 'Doe', '1 Main St', 'San Jose', 'CA', '95131', 'United States', 1),
(84, '2FE061065N2268028', '6.09', 'Pending', '3', 'Band-aid', '1', '5.46', '2021-05-09 15:05:15', 'sb-pnude6123499@personal.example.com', 'John', 'Doe', '1 Main St', 'San Jose', 'CA', '95131', 'United States', 1),
(94, '6S826181C4314442X', '1.74', 'Pending', '2', 'Advil', '1', '1.56', '2021-05-13 18:39:51', 'sb-pnude6123499@personal.example.com', 'John', 'Doe', '1 Main St', 'San Jose', 'CA', '95131', 'United States', 1),
(104, '9UH77069AN858302G', '9.28', 'Pending', '4', 'Swan Rubbing Alcohol', '1', '8.32', '2021-05-13 19:02:14', 'sb-pnude6123499@personal.example.com', 'John', 'Doe', '1 Main St', 'San Jose', 'CA', '95131', 'United States', 1),
(114, '62L07669125426907', '2.15', 'Pending', '14', 'Germ-X Hand Sanitizer', '1', '1.93', '2021-05-13 19:29:03', 'sb-pnude6123499@personal.example.com', 'John', 'Doe', '1 Main St', 'San Jose', 'CA', '95131', 'United States', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `reset` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `admin`, `reset`) VALUES
(1, 'gh@gm.com', '$2y$10$nZvrYt4KyVvZqffWRAbBAOSm8lK3ykRJ9hqkaAnDG.NWdjtg1Gmxe', 0, ''),
(24, 'admin@farmaciasanblas.com', '$2y$10$.ag5cPjfIBTDszmrTMOnjuxk0aq9nh.lpKvUSsmlo5BuxQAPkWQ0W', 1, ''),
(34, 'mely.bt15@gmail.com', '$2y$10$J5uBsvbqtJvJeZDyoZcM8ODnDblArGMBEEYfSuOcs1mlQitY11rVq', 0, '6098a52b1c82f');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
