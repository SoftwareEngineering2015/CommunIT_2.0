-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2016 at 05:37 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `communit2`
--

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE IF NOT EXISTS `communities` (
  `community_id` char(12) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `community_id` char(12) NOT NULL,
  `community_name` varchar(255) NOT NULL,
  `community_description` varchar(255) NOT NULL,
  `default_pin_color` char(7) NOT NULL DEFAULT '#96F0F0',
  `default_pin_color_status` tinyint(1) NOT NULL DEFAULT '1',
  `allow_user_pin_colors` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `floorplans_to_markers`
--

CREATE TABLE IF NOT EXISTS `floorplans_to_markers` (
  `floorplan_id` char(12) NOT NULL,
  `marker_id` char(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `floor_plans`
--

CREATE TABLE IF NOT EXISTS `floor_plans` (
  `floorplan_id` char(12) NOT NULL,
  `floor` varchar(255) NOT NULL,
  `image_location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `markers`
--

CREATE TABLE IF NOT EXISTS `markers` (
  `marker_id` char(12) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `location` varchar(255) NOT NULL,
  `pin_color` char(7) NOT NULL DEFAULT '#96F0F0',
  `has_floorplan` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `markers_to_communities`
--

CREATE TABLE IF NOT EXISTS `markers_to_communities` (
  `community_id` char(12) NOT NULL,
  `marker_id` char(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `markers_to_floorplans`
--

CREATE TABLE IF NOT EXISTS `markers_to_floorplans` (
  `marker_id` char(12) NOT NULL,
  `floorplan_id` char(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE IF NOT EXISTS `privileges` (
`privilege_id` int(11) NOT NULL,
  `privilege` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`privilege_id`, `privilege`) VALUES
(1, 'owner'),
(2, 'moderator'),
(3, 'resident');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `profile_id` char(12) NOT NULL,
  `user_id` char(12) NOT NULL,
  `community_id` char(12) NOT NULL,
  `phone_01` char(12) DEFAULT NULL,
  `phone_02` char(12) DEFAULT NULL,
  `email_01` varchar(255) DEFAULT NULL,
  `email_02` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profiles_to_markers`
--

CREATE TABLE IF NOT EXISTS `profiles_to_markers` (
  `marker_id` char(12) NOT NULL,
  `profile_id` char(12) NOT NULL,
  `has_edited` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requests_to_join_communities`
--

CREATE TABLE IF NOT EXISTS `requests_to_join_communities` (
  `user_id` char(12) NOT NULL,
  `community_id` char(12) NOT NULL,
  `requested_or_invited` int(1) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE IF NOT EXISTS `residents` (
`resident_id` int(16) NOT NULL,
  `profile_id` char(12) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone_01` char(12) DEFAULT NULL,
  `phone_02` char(12) DEFAULT NULL,
  `email_01` varchar(255) DEFAULT NULL,
  `email_02` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` char(12) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `m_initial` char(1) DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` char(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_to_communities`
--

CREATE TABLE IF NOT EXISTS `users_to_communities` (
  `user_id` char(12) NOT NULL,
  `community_id` char(12) NOT NULL,
  `privilege_id` int(11) NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
 ADD PRIMARY KEY (`community_id`), ADD UNIQUE KEY `community_id` (`community_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`community_id`);

--
-- Indexes for table `floorplans_to_markers`
--
ALTER TABLE `floorplans_to_markers`
 ADD PRIMARY KEY (`floorplan_id`,`marker_id`), ADD KEY `floor_plan_id` (`floorplan_id`), ADD KEY `marker_id` (`marker_id`);

--
-- Indexes for table `floor_plans`
--
ALTER TABLE `floor_plans`
 ADD PRIMARY KEY (`floorplan_id`), ADD UNIQUE KEY `image_location_id` (`image_location`), ADD UNIQUE KEY `floorplan_id_UNIQUE` (`floorplan_id`);

--
-- Indexes for table `markers`
--
ALTER TABLE `markers`
 ADD PRIMARY KEY (`marker_id`);

--
-- Indexes for table `markers_to_communities`
--
ALTER TABLE `markers_to_communities`
 ADD PRIMARY KEY (`community_id`,`marker_id`), ADD KEY `marker_id` (`marker_id`);

--
-- Indexes for table `markers_to_floorplans`
--
ALTER TABLE `markers_to_floorplans`
 ADD PRIMARY KEY (`marker_id`,`floorplan_id`), ADD UNIQUE KEY `marker_id` (`marker_id`), ADD KEY `floor_plan_id` (`floorplan_id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
 ADD PRIMARY KEY (`privilege_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
 ADD PRIMARY KEY (`profile_id`), ADD KEY `user_id` (`user_id`), ADD KEY `community_id` (`community_id`);

--
-- Indexes for table `profiles_to_markers`
--
ALTER TABLE `profiles_to_markers`
 ADD PRIMARY KEY (`marker_id`,`profile_id`), ADD KEY `profile_id` (`profile_id`);

--
-- Indexes for table `requests_to_join_communities`
--
ALTER TABLE `requests_to_join_communities`
 ADD PRIMARY KEY (`user_id`,`community_id`), ADD KEY `community_id` (`community_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
 ADD PRIMARY KEY (`resident_id`), ADD UNIQUE KEY `profile_id` (`profile_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `user_id` (`user_id`), ADD UNIQUE KEY `user_id_2` (`user_id`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_to_communities`
--
ALTER TABLE `users_to_communities`
 ADD PRIMARY KEY (`user_id`,`community_id`), ADD KEY `privilege_id` (`privilege_id`), ADD KEY `community_id` (`community_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
MODIFY `privilege_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
MODIFY `resident_id` int(16) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `config`
--
ALTER TABLE `config`
ADD CONSTRAINT `config_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE;

--
-- Constraints for table `floorplans_to_markers`
--
ALTER TABLE `floorplans_to_markers`
ADD CONSTRAINT `floorplans_to_markers_ibfk_1` FOREIGN KEY (`marker_id`) REFERENCES `markers` (`marker_id`) ON DELETE CASCADE,
ADD CONSTRAINT `floorplans_to_markers_ibfk_2` FOREIGN KEY (`floorplan_id`) REFERENCES `floor_plans` (`floorplan_id`) ON DELETE CASCADE;

--
-- Constraints for table `markers_to_communities`
--
ALTER TABLE `markers_to_communities`
ADD CONSTRAINT `markers_to_communities_ibfk_1` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
ADD CONSTRAINT `markers_to_communities_ibfk_2` FOREIGN KEY (`marker_id`) REFERENCES `markers` (`marker_id`) ON DELETE CASCADE;

--
-- Constraints for table `markers_to_floorplans`
--
ALTER TABLE `markers_to_floorplans`
ADD CONSTRAINT `markers_to_floorplans_ibfk_1` FOREIGN KEY (`marker_id`) REFERENCES `markers` (`marker_id`) ON DELETE CASCADE,
ADD CONSTRAINT `markers_to_floorplans_ibfk_2` FOREIGN KEY (`floorplan_id`) REFERENCES `floor_plans` (`floorplan_id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
ADD CONSTRAINT `profiles_ibfk_2` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles_to_markers`
--
ALTER TABLE `profiles_to_markers`
ADD CONSTRAINT `profiles_to_markers_ibfk_1` FOREIGN KEY (`marker_id`) REFERENCES `markers` (`marker_id`) ON DELETE CASCADE,
ADD CONSTRAINT `profiles_to_markers_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`profile_id`) ON DELETE CASCADE;

--
-- Constraints for table `requests_to_join_communities`
--
ALTER TABLE `requests_to_join_communities`
ADD CONSTRAINT `requests_to_join_communities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
ADD CONSTRAINT `requests_to_join_communities_ibfk_2` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE;

--
-- Constraints for table `residents`
--
ALTER TABLE `residents`
ADD CONSTRAINT `residents_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`profile_id`) ON DELETE CASCADE;

--
-- Constraints for table `users_to_communities`
--
ALTER TABLE `users_to_communities`
ADD CONSTRAINT `users_to_communities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
ADD CONSTRAINT `users_to_communities_ibfk_2` FOREIGN KEY (`community_id`) REFERENCES `communities` (`community_id`) ON DELETE CASCADE,
ADD CONSTRAINT `users_to_communities_ibfk_3` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`privilege_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
