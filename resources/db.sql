-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2018 at 01:28 PM
-- Server version: 5.6.41-84.1
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jblakmc_fiatgateway`
--

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `id` int(11) NOT NULL,
  `base` float NOT NULL COMMENT 'BTC Priced In USD',
  `crypto` float NOT NULL COMMENT 'Cryptocurrency Being Sold Priced In BTC',
  `price` float NOT NULL COMMENT 'Price Of 1 Cryptocurrency Unit In USD',
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `crypto` float NOT NULL COMMENT 'Amount Of Cryptocurrency Purchasing',
  `price` float NOT NULL COMMENT 'Price In Fiat',
  `fee` float NOT NULL COMMENT 'Fee Charged By Gateway',
  `total` float NOT NULL COMMENT 'Includes Gateway + Provider Fees',
  `address` text NOT NULL COMMENT 'Public Wallet Address',
  `provider` text NOT NULL COMMENT 'Payment Provider',
  `data` text NOT NULL COMMENT 'All Data Returned By Provider',
  `time` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = Unpaid, 1 = Paid, 2 = Paid & Cryptocurrency Sent'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `expires` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 = Cron Deletes'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD KEY `id` (`id`(250));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
