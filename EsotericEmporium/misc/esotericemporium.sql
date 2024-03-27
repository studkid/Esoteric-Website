-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2024 at 12:31 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esotericemporium`
--
CREATE DATABASE IF NOT EXISTS `esotericemporium` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `esotericemporium`;

DROP TABLE IF EXISTS `purchase`;
DROP TABLE IF EXISTS `eeuser`;
DROP TABLE IF EXISTS `item`;

-- --------------------------------------------------------

--
-- Table structure for table `eeuser`
--

CREATE TABLE IF NOT EXISTS `eeuser` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eeuser`
--

INSERT INTO `eeuser` (`userID`, `username`, `password`, `role`) VALUES
(1, 'Admin', '00B905NdqJljM', 'Administrator'),
(2, 'Joe', '00UTZBLXnAHkQ', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `itemID` int(11) NOT NULL AUTO_INCREMENT,
  `itemName` varchar(50) NOT NULL,
  `itemDescription` varchar(300) DEFAULT NULL,
  `itemImageName` varchar(50) DEFAULT NULL,
  `itemPrice` decimal(20,2) NOT NULL,
  `itemHidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemID`, `itemName`, `itemDescription`, `itemImageName`, `itemPrice`, `itemHidden`) VALUES
(1, 'Test Item', 'This is a useless test item. Do not buy.', NULL, 19.99, 0),
(2, 'Levitating Paperweight', 'Hovers above your paperwork.', 'paperweight.jpg', 79.99, 0),
(3, 'Deluxe Skeleton Key', 'A key that \'opens\' doors by breaking them down.', 'skeletonKey.jpg', 119.99, 0),
(4, 'Gandalf', ' gandalf', 'gandalf.jpg', 4.99, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE IF NOT EXISTS `purchase` (
  `purchaseID` int(11) NOT NULL AUTO_INCREMENT,
  `itemID` int(11) NOT NULL,
  `purchaserName` varchar(50) NOT NULL,
  `purchaseDate` datetime NOT NULL,
  PRIMARY KEY (`purchaseID`),
  KEY `itemID_FK` (`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `itemID_FK` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
