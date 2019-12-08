-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 03 déc. 2019 à 15:32
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
-- Base de données :  `prwb_1920_G09`
--
DROP DATABASE IF EXISTS `prwb_1920_G09`;
CREATE DATABASE IF NOT EXISTS `prwb_1920_G09` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_1920_G09`;

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
  KEY `Author` (`AuthorId`),
  KEY `ParentId` (`ParentId`),
  KEY `AcceptedAnswerId` (`AcceptedAnswerId`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`PostId`, `AuthorId`, `Title`, `Body`, `Timestamp`, `AcceptedAnswerId`, `ParentId`) VALUES
(1, 1, 'What does \'initialization\' exactly mean?', 'My csapp book says that if global and static variables are initialized, than they are contained in .data section in ELF relocatable object file.\r\n\r\nSo my question is that if some `foo.c` code contains \r\n​```\r\nint a;\r\nint main()\r\n{\r\n    a = 3;\r\n}`\r\n​```\r\nand `example.c` contains,\r\n​```\r\nint b = 3;\r\nint main()\r\n{\r\n...\r\n}\r\n​```\r\nis it only `b` that considered to be initialized? In other words, does initialization mean declaration and definition in same line?', '2019-11-02 08:30:00', NULL, NULL),
(2, 2, '', 'It means exactly what it says. Initialized static storage duration objects will have their init values set before the main function is called. Not initialized will be zeroed. The second part of the statement is actually implementation dependant,  and implementation has the full freedom of the way it will be archived. \r\n\r\nWhen you declare the variable without the keyword `extern`  you always define it as well', '2019-11-02 08:31:00', NULL, 1),
(3, 3, '', 'Both are considered initialized\r\n------------------------------------\r\n\r\n\r\nThey get [zero initialized][1] or constant initalized (in short: if the right hand side is a compile time constant expression).\r\n\r\n> If permitted, Constant initialization takes place first (see Constant\r\n> initialization for the list of those situations). In practice,\r\n> constant initialization is usually performed at compile time, and\r\n> pre-calculated object representations are stored as part of the\r\n> program image. If the compiler doesn\'t do that, it still has to\r\n> guarantee that this initialization happens before any dynamic\r\n> initialization.\r\n> \r\n> For all other non-local static and thread-local variables, Zero\r\n> initialization takes place. In practice, variables that are going to\r\n> be zero-initialized are placed in the .bss segment of the program\r\n> image, which occupies no space on disk, and is zeroed out by the OS\r\n> when loading the program.\r\n\r\nTo sum up, if the implementation cannot constant initialize it, then it must first zero initialize and then initialize it before any dynamic initialization happends.\r\n\r\n\r\n  [1]: https://en.cppreference.com/w/cpp/language/zero_initialization\r\n\r\n', '2019-11-02 08:32:00', NULL, 1),
(4, 1, 'How do I escape characters in an Angular date pipe?', 'I have an Angular date variable `today` that I\'m using the [date pipe][1] on, like so:\r\n\r\n    {{today | date:\'LLLL d\'}}\r\n\r\n> February 13\r\n\r\nHowever, I would like to make it appear like this:\r\n\r\n> 13 days so far in February\r\n\r\nWhen I try a naive approach to this, I get this result:\r\n\r\n    {{today | date:\'d days so far in LLLL\'}}\r\n\r\n> 13 13PM201818 18o fPMr in February\r\n\r\nThis is because, for instance `d` refers to the day.\r\n\r\nHow can I escape these characters in an Angular date pipe? I tried `\\d` and such, but the result did not change with the added backslashes.\r\n  [1]: https://angular.io/api/common/DatePipe', '2019-11-02 08:33:00', 5, NULL),
(5, 1, '', 'How about this:\r\n\r\n    {{today | date:\'d \\\'days so far in\\\' LLLL\'}}\r\n\r\nAnything inside single quotes is ignored. Just don\'t forget to escape them.', '2019-11-02 08:34:00', NULL, 4),
(6, 3, '', 'Then only other alternative to stringing multiple pipes together as suggested by RichMcCluskey would be to create a custom pipe that calls through to momentjs format with the passed in date. Then you could use the same syntax including escape sequence that momentjs supports.\r\n\r\nSomething like this could work, it is not an exhaustive solution in that it does not deal with localization at all and there is no error handling code or tests.\r\n\r\n	import { Inject, Pipe, PipeTransform } from \'@angular/core\';\r\n\r\n	@Pipe({ name: \'momentDate\', pure: true })\r\n	export class MomentDatePipe implements PipeTransform {\r\n\r\n		transform(value: any, pattern: string): string {\r\n			if (!value)\r\n				return \'\';\r\n			return moment(value).format(pattern);\r\n		}\r\n	}\r\n\r\nAnd then the calling code:\r\n\r\n    {{today | momentDate:\'d [days so far in] LLLL\'}}\r\n\r\nFor all the format specifiers see the [documentation for format][1]. \r\n\r\nKeep in mind you do have to import `momentjs` either as an import statement, have it imported in your cli config file, or reference the library from the root HTML page (like index.html).\r\n\r\n\r\n\r\n  [1]: http://momentjs.com/docs/#/displaying/format/', '2019-11-02 08:35:00', NULL, 4),
(7, 2, '', 'As far as I know this is not possible with the Angular date pipe at the time of this answer. One alternative is to use multiple date pipes like so:\r\n\r\n    {{today | date:\'d\'}} days so far in {{today | date:\'LLLL\'}}\r\n\r\nEDIT:\r\n\r\nAfter posting this I tried @Gh0sT \'s solution and it worked, so I guess there is a way to use one date pipe.', '2019-11-02 08:36:00', NULL, 4),
(8, 5, 'Q1', 'Q1', '2019-12-02 08:00:00', NULL, NULL),
(9, 1, '', 'R1', '2019-12-02 08:05:00', NULL, 8),
(10, 2, '', 'R2', '2019-12-02 08:03:00', NULL, 8),
(11, 3, '', 'R3', '2019-12-02 08:04:00', NULL, 8),
(12, 4, 'Q2', 'Q2', '2019-12-02 09:00:00', NULL, NULL),
(13, 5, '', 'R4', '2019-12-02 09:01:00', NULL, 12),
(14, 1, 'Q3', 'Q3', '2019-12-02 10:00:00', NULL, NULL),
(15, 3, '', 'R5', '2019-12-02 10:02:00', NULL, 14),
(16, 3, '', 'R6', '2019-12-02 10:02:00', NULL, 14),
(17, 2, 'Q4', 'Q4', '2019-12-02 11:00:00', NULL, NULL),
(18, 3, '', 'R7', '2019-12-02 10:02:00', NULL, 17),
(19, 4, 'Q5', 'Q5', '2019-12-02 11:00:00', NULL, NULL),
(20, 3, '', 'R8', '2019-12-02 10:02:00', NULL, 19);

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
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`UserId`, `UserName`, `Password`, `FullName`, `Email`) VALUES
(1, 'ben', '56ce92d1de4f05017cf03d6cd514d6d1', 'Benoît Penelle', 'ben@test.com'),
(2, 'bruno', '56ce92d1de4f05017cf03d6cd514d6d1', 'Bruno Lacroix', 'bruno@test.com'),
(3, 'admin', '56ce92d1de4f05017cf03d6cd514d6d1', 'Administrator', 'admin@test.com'),
(4, 'boris', '56ce92d1de4f05017cf03d6cd514d6d1', 'Boris Verhaegen', 'boris@test.com'),
(5, 'alain', '56ce92d1de4f05017cf03d6cd514d6d1', 'Alain Silovy', 'alain@test.com');

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
-- Déchargement des données de la table `vote`
--

INSERT INTO `vote` (`UserId`, `PostId`, `UpDown`) VALUES
(1, 8, -1),
(1, 11, 1),
(1, 12, 1),
(1, 13, -1),
(1, 18, 1),
(2, 1, -1),
(2, 3, 1),
(2, 9, -1),
(2, 11, 1),
(2, 12, 1),
(2, 14, -1),
(2, 15, -1),
(3, 1, -1),
(3, 2, -1),
(3, 5, -1),
(3, 12, 1),
(4, 7, 1),
(4, 8, -1),
(4, 9, 1),
(4, 16, -1),
(5, 1, 1),
(5, 5, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`AuthorId`) REFERENCES `user` (`UserId`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`ParentId`) REFERENCES `post` (`PostId`),
  ADD CONSTRAINT `post_ibfk_3` FOREIGN KEY (`AcceptedAnswerId`) REFERENCES `post` (`PostId`);

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
