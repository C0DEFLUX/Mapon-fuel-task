-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 20, 2022 at 06:53 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mapon_task`
--

-- --------------------------------------------------------

--
-- Table structure for table `mapon_fuel`
--

CREATE TABLE `mapon_fuel` (
  `id` int(11) NOT NULL,
  `fuel_date` varchar(225) NOT NULL,
  `fuel_time` varchar(225) NOT NULL,
  `fuel_card_nr` varchar(225) NOT NULL,
  `fuel_vehicle_nr` varchar(225) NOT NULL,
  `fuel_product` varchar(225) NOT NULL,
  `fuel_amount` varchar(225) NOT NULL,
  `fuel_total_sum` varchar(225) NOT NULL,
  `fuel_currency` varchar(255) NOT NULL,
  `fuel_country` varchar(225) NOT NULL,
  `fuel_country_iso` varchar(255) NOT NULL,
  `fuel_station` varchar(225) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mapon_user`
--

CREATE TABLE `mapon_user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mapon_fuel`
--
ALTER TABLE `mapon_fuel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapon_user`
--
ALTER TABLE `mapon_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mapon_fuel`
--
ALTER TABLE `mapon_fuel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mapon_user`
--
ALTER TABLE `mapon_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
