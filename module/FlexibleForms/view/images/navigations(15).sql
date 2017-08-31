-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 15, 2015 at 03:37 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `htb_phxfoundation`
--

-- --------------------------------------------------------

--
-- Table structure for table `navigations`
--

CREATE TABLE IF NOT EXISTS `navigations` (
  `navigationId` int(11) NOT NULL AUTO_INCREMENT,
  `navigationName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `navigationKey` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `navigationUrl` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `createdUserId` int(11) DEFAULT NULL,
  `modifiedUserId` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `propertyId` int(11) DEFAULT NULL,
  `navigationOrder` int(11) NOT NULL,
  `navCategory` varchar(255) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`navigationId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `navigations`
--

INSERT INTO `navigations` (`navigationId`, `navigationName`, `navigationKey`, `navigationUrl`, `createdUserId`, `modifiedUserId`, `created`, `status`, `propertyId`, `navigationOrder`, `navCategory`, `parent`) VALUES
(1, 'Careers', 'careers', 'careers', 108, 108, '2015-01-07 21:12:30', 1, 22, 1, 'mainmenu', 0),
(2, 'About', 'about', 'about', 108, 108, '2015-01-07 21:19:29', 1, 22, 2, 'mainmenu', 0),
(3, 'test', 'sfvdsf', 'test2', 108, 108, '2015-01-08 20:49:10', 1, 22, 3, 'mainmenu', 0),
(4, 'test1', 'test2', 'test3', 108, 108, '2015-01-08 21:06:39', 1, 22, 4, 'mainmenu', 0),
(5, 'news', 'news', 'news', 108, 108, '2015-01-08 21:21:54', 1, 22, 5, 'mainmenu', 0),
(6, 'efe', 'erger', 'resrg', 108, 108, '2015-01-08 22:14:36', 1, 22, 6, 'mainmenu', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
