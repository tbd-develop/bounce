-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 14, 2012 at 12:57 AM
-- Server version: 5.1.61
-- PHP Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mortong_tburnsd`
--

-- --------------------------------------------------------

--
-- Table structure for table `Contacts`
--

CREATE TABLE IF NOT EXISTS `Contacts` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Address` varchar(50) NOT NULL,
  `Address2` varchar(50) DEFAULT NULL,
  `City` varchar(100) NOT NULL,
  `County` varchar(100) NOT NULL,
  `Postcode` varchar(10) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Mobile` varchar(20) NOT NULL,
  `Work` varchar(20) NOT NULL,
  `IsPrimary` bit(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE IF NOT EXISTS `Courses` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Phone` text NOT NULL,
  `Address` varchar(30) NOT NULL,
  `Address2` varchar(30) DEFAULT NULL,
  `City` varchar(50) NOT NULL,
  `County` varchar(100) NOT NULL,
  `Postcode` varchar(10) NOT NULL,
  `Country` varchar(30) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `WebsiteUrl` varchar(200) NOT NULL,
  `IsEnabled` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`Id`),
  KEY `Address` (`Address`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Table structure for table `CustomFields`
--

CREATE TABLE IF NOT EXISTS `CustomFields` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `CustomType` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `EventCustomFields`
--

CREATE TABLE IF NOT EXISTS `EventCustomFields` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FieldId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `EventCustomValues`
--

CREATE TABLE IF NOT EXISTS `EventCustomValues` (
  `EventId` int(10) unsigned NOT NULL,
  `FieldId` int(10) unsigned NOT NULL,
  `Value` varchar(4096) NOT NULL,
  PRIMARY KEY (`EventId`,`FieldId`),
  KEY `Value` (`Value`(767))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE IF NOT EXISTS `Events` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `Description` text,
  `ScheduledDate` date NOT NULL,
  `IsSignupEnabled` bit(1) NOT NULL,
  `IsScored` bit(1) NOT NULL,
  `IsEnabled` bit(1) NOT NULL,
  `CourseId` int(10) unsigned DEFAULT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ScheduledDate` (`ScheduledDate`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Table structure for table `EventSignupGuests`
--

CREATE TABLE IF NOT EXISTS `EventSignupGuests` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SignupId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `SignupIp` (`SignupId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `EventSignups`
--

CREATE TABLE IF NOT EXISTS `EventSignups` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EventId` int(10) unsigned NOT NULL,
  `UserId` int(10) unsigned NOT NULL,
  `SignedUpOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `DroppedOutOn` timestamp NULL DEFAULT NULL,
  `DidAttend` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`Id`),
  KEY `EventId` (`EventId`),
  KEY `UserId` (`UserId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `Gallery`
--

CREATE TABLE IF NOT EXISTS `Gallery` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `DateCreated` datetime NOT NULL,
  `IsVisible` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `GalleryImages`
--

CREATE TABLE IF NOT EXISTS `GalleryImages` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `GalleryId` int(10) unsigned NOT NULL,
  `ImageName` varchar(255) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `ImageName` (`ImageName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `MessageBoards`
--

CREATE TABLE IF NOT EXISTS `MessageBoards` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `IsEnabled` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE IF NOT EXISTS `Messages` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `BoardId` int(10) unsigned NOT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Message` text NOT NULL,
  `UserId` int(10) unsigned NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `BoardId` (`BoardId`,`Title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `Profiles`
--

CREATE TABLE IF NOT EXISTS `Profiles` (
  `UserId` int(10) unsigned NOT NULL,
  `Handicap` float(4,1) NOT NULL,
  PRIMARY KEY (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Reviews`
--

CREATE TABLE IF NOT EXISTS `Reviews` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CourseId` int(10) unsigned NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `Rating` tinyint(1) unsigned NOT NULL DEFAULT '3',
  `ValueForMoney` tinyint(1) unsigned NOT NULL DEFAULT '3',
  `WouldReturn` bit(1) NOT NULL DEFAULT b'1',
  `DateVisited` datetime NOT NULL,
  `DatePosted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `AddUserId` int(10) unsigned NOT NULL,
  `LastEditUserId` int(10) unsigned DEFAULT NULL,
  `LastEditDate` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `CourseId` (`CourseId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE IF NOT EXISTS `Roles` (
  `Id` tinyint(1) unsigned NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UserContacts`
--

CREATE TABLE IF NOT EXISTS `UserContacts` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned NOT NULL,
  `ContactId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `UserId` (`UserId`),
  KEY `ContactId` (`ContactId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Role` tinyint(4) NOT NULL,
  `LastAuthId` varchar(255) NOT NULL,
  `LastLogin` datetime DEFAULT NULL,
  `DateCreated` datetime NOT NULL,
  `IsActive` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`Id`),
  KEY `FirstName` (`FirstName`,`LastName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Table structure for table `UserSettings`
--

CREATE TABLE IF NOT EXISTS `UserSettings` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned NOT NULL,
  `Settings` text NOT NULL,
  PRIMARY KEY (`Id`,`UserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
