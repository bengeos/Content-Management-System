-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2015 at 10:56 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `content_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE IF NOT EXISTS `grades` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `user_id`, `name`, `created`) VALUES
(1, 1, 'Grade One', '2015-08-18 18:39:38'),
(2, 1, 'Grade Two', '2015-08-18 18:39:51'),
(3, 1, 'Grade Three', '2015-08-18 18:41:06'),
(4, 1, 'Grade Four', '2015-08-18 18:41:14'),
(5, 1, 'Grade Five', '2015-08-18 18:41:23'),
(6, 1, 'Grade Six', '2015-08-18 18:41:29'),
(7, 1, 'Grade Seven', '2015-08-18 18:41:35'),
(8, 1, 'Grade Eight', '2015-08-18 18:41:44'),
(9, 1, 'Grade Nine', '2015-08-18 18:41:50'),
(10, 1, 'Grade Ten', '2015-08-18 18:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `user_id`, `name`, `grade_id`, `subject_id`, `category`, `file_name`, `description`, `created`) VALUES
(1, 2, 'sdsadasdasd', 1, 1, 'asdasdasd', '7abab694ae569bb393393069b1993a54.pak', 'sadasd', '2015-08-18 19:32:18'),
(2, 2, 'Human Digetsive System', 10, 5, 'Chapter One', 'b0d967d701df3e5921949472768ba8b5.MSI', 'Digestive System', '2015-08-18 20:06:44'),
(3, 2, 'Trignometry', 10, 1, 'Chapter One', 'd08bf81296d9f8eec3151a6d622bcba1.dll', 'trignometry', '2015-08-18 20:08:14'),
(4, 2, 'Spoken English', 10, 2, 'Chapter One', '5aff1e21e6133861fc355d3560e42816.ini', 'spoken english', '2015-08-18 20:08:58'),
(5, 2, 'Spoken English', 10, 2, 'Chapter One', 'f11b02b4c431be4957cf58bca5d04463.dll', 'Spoken English', '2015-08-18 20:09:40'),
(6, 2, 'Spoken English', 1, 2, 'Chapter One', '314829b50785f878f5b169d396a40eae.dll', 'Spoken English', '2015-08-18 20:10:00'),
(7, 2, 'Motion ', 10, 3, 'Chapter One', 'f33068a4bec547db5e6da4330802bc59.ini', 'Motion', '2015-08-18 20:22:17'),
(8, 2, 'Acceleration', 10, 3, 'Chapter One', '5a7f934414cf1733773e59d94c0db22f.txt', 'Acceleration', '2015-08-18 20:22:56'),
(9, 2, 'Cordinate System', 10, 1, 'Chapter Two', '809dab08607fd16ca73c71dd955b7767.txt', 'Cordinate System', '2015-08-18 20:23:39'),
(10, 2, 'Trignometry', 1, 1, 'Chapter Two', '3b3fc86250829a1ec349796bd16ca580.mp4', 'Trignometry', '2015-08-19 04:19:11'),
(11, 2, 'Music', 10, 1, 'Chapter Three', '732f3a87742088b9855541b7ad92bc1e.mp4', 'music fiel', '2015-08-22 14:59:58');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `user_id`, `name`, `created`) VALUES
(1, 1, 'Mathematics', '2015-08-18 18:38:52'),
(2, 1, 'English', '2015-08-18 18:38:59'),
(3, 1, 'Physics', '2015-08-18 18:39:05'),
(4, 1, 'Chemistry', '2015-08-18 18:39:17'),
(5, 1, 'Biology', '2015-08-18 19:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `full_name` varchar(500) NOT NULL,
  `user_name` varchar(500) NOT NULL,
  `user_pass` varchar(500) NOT NULL,
  `user_type` enum('Admin','User','','') NOT NULL,
  `privilage` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `user_name`, `user_pass`, `user_type`, `privilage`, `created`) VALUES
(1, 'Biniam Kassahun', 'ben', '*652F5C6351D0ED09328FE3DE88902CDFB6948F7E', 'Admin', 0, '2015-08-18 18:33:39'),
(2, 'George Kassahun', '123', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', 'User', 1, '2015-08-18 18:43:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
 ADD PRIMARY KEY (`id`), ADD KEY `grade_id` (`grade_id`), ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
ADD CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
ADD CONSTRAINT `materials_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
