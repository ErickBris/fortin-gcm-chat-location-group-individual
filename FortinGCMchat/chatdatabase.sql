-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 12, 2015 at 04:58 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chatdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `groupdetail`
--

CREATE TABLE IF NOT EXISTS `groupdetail` (
`id` int(11) NOT NULL,
  `memberid` varchar(100) NOT NULL,
  `groupid` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groupmaster`
--

CREATE TABLE IF NOT EXISTS `groupmaster` (
`groupid` int(11) NOT NULL,
  `groupname` varchar(100) NOT NULL,
  `adminid` varchar(100) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `location_table`
--

CREATE TABLE IF NOT EXISTS `location_table` (
`id` int(11) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `userid` varchar(100) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE IF NOT EXISTS `usertable` (
`id` int(11) NOT NULL,
  `userid` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `gcmid` varchar(500) NOT NULL,
  `membersince` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profilepic` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'Available',
  `logintype` varchar(100) NOT NULL,
  `isloggin` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_relation_map`
--

CREATE TABLE IF NOT EXISTS `user_relation_map` (
`relationid` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `friend_id` varchar(50) NOT NULL,
  `blocked_status` varchar(50) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groupdetail`
--
ALTER TABLE `groupdetail`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupmaster`
--
ALTER TABLE `groupmaster`
 ADD PRIMARY KEY (`groupid`);

--
-- Indexes for table `location_table`
--
ALTER TABLE `location_table`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usertable`
--
ALTER TABLE `usertable`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_relation_map`
--
ALTER TABLE `user_relation_map`
 ADD PRIMARY KEY (`relationid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groupdetail`
--
ALTER TABLE `groupdetail`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groupmaster`
--
ALTER TABLE `groupmaster`
MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `location_table`
--
ALTER TABLE `location_table`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usertable`
--
ALTER TABLE `usertable`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_relation_map`
--
ALTER TABLE `user_relation_map`
MODIFY `relationid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
