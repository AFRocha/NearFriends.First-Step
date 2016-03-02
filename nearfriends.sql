-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 02-Mar-2016 às 17:12
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nearfriends`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(760) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `user_agent` varchar(1000) NOT NULL,
  `last_activity` int(255) NOT NULL,
  `user_data` varchar(1000) NOT NULL,
  `userId` int(255) DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(22) NOT NULL,
  `password` varchar(150) NOT NULL,
  `email` varchar(22) NOT NULL,
  `foto` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `firstFriend` int(255) NOT NULL,
  `secondFriend` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `firstFriend` (`firstFriend`),
  KEY `secondFriend` (`secondFriend`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `friend_request`
--

CREATE TABLE IF NOT EXISTS `friend_request` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `first_friend` int(255) NOT NULL,
  `second_friend` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `first_friend` (`first_friend`),
  KEY `second_friend` (`second_friend`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `body` varchar(1000) NOT NULL,
  `userId` int(255) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD CONSTRAINT `ci_sessions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `clients` (`id`);

--
-- Limitadores para a tabela `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`firstFriend`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`secondFriend`) REFERENCES `clients` (`id`);

--
-- Limitadores para a tabela `friend_request`
--
ALTER TABLE `friend_request`
  ADD CONSTRAINT `friend_request_ibfk_1` FOREIGN KEY (`first_friend`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `friend_request_ibfk_2` FOREIGN KEY (`second_friend`) REFERENCES `clients` (`id`);

--
-- Limitadores para a tabela `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `clients` (`id`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_invalid_sessions` ON SCHEDULE EVERY '0:10' MINUTE_SECOND STARTS '2016-02-27 17:41:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM `ci_sessions` 
WHERE `userId` IS NULL$$

CREATE DEFINER=`root`@`localhost` EVENT `delete_innactive_sessions` ON SCHEDULE EVERY 1 MINUTE STARTS '2016-02-27 17:00:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM `ci_sessions`
WHERE (UNIX_TIMESTAMP() - `ci_sessions`.`last_activity` ) > 300$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
