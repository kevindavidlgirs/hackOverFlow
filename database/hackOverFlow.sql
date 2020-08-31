-- Cette base de données à été créée par l'EPFC
--
-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 02 déc. 2019 à 19:06
-- Version du serveur :  10.4.8-MariaDB
-- Version de PHP :  7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `hackoverflow`
--
DROP DATABASE IF EXISTS `hackoverflow`;
CREATE DATABASE IF NOT EXISTS `hackoverflow` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `hackoverflow`;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `CommentId` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `PostId` int(11) NOT NULL,
  `Body` text NOT NULL,
  `Timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`CommentId`),
  KEY `UserId` (`UserId`),
  KEY `PostId` (`PostId`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `PostId` int(11) NOT NULL AUTO_INCREMENT,
  `AuthorId` int(11) NOT NULL,
  `Title` varchar(256) DEFAULT NULL,
  `Body` text NOT NULL,
  `Timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `AcceptedAnswerId` int(11) DEFAULT NULL,
  `ParentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`PostId`),
  KEY `AuthorId` (`AuthorId`),
  KEY `ParentId` (`ParentId`),
  KEY `AcceptedAnswerId` (`AcceptedAnswerId`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `posttag`
--

DROP TABLE IF EXISTS `posttag`;
CREATE TABLE IF NOT EXISTS `posttag` (
  `PostId` int(11) NOT NULL,
  `TagId` int(11) NOT NULL,
  PRIMARY KEY (`PostId`,`TagId`),
  KEY `TagId` (`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `TagId` int(11) NOT NULL AUTO_INCREMENT,
  `TagName` varchar(256) NOT NULL,
  PRIMARY KEY (`TagId`),
  UNIQUE KEY `TagName` (`TagName`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(128) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `FullName` varchar(256) NOT NULL,
  `Email` varchar(128) NOT NULL,
  `Role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `UserId` int(11) NOT NULL,
  `PostId` int(11) NOT NULL,
  `UpDown` tinyint(1) NOT NULL,
  PRIMARY KEY (`UserId`,`PostId`),
  KEY `PostId` (`PostId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`PostId`) REFERENCES `post` (`PostId`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`AuthorId`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`ParentId`) REFERENCES `post` (`PostId`),
  ADD CONSTRAINT `post_ibfk_3` FOREIGN KEY (`AcceptedAnswerId`) REFERENCES `post` (`PostId`);

--
-- Contraintes pour la table `posttag`
--
ALTER TABLE `posttag`
  ADD CONSTRAINT `posttag_ibfk_1` FOREIGN KEY (`PostId`) REFERENCES `post` (`PostId`),
  ADD CONSTRAINT `posttag_ibfk_2` FOREIGN KEY (`TagId`) REFERENCES `tag` (`TagId`);

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`PostId`) REFERENCES `post` (`PostId`),
  ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
