-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `hashtags`;
CREATE TABLE `hashtags` (
  `idTweet` int(11) NOT NULL,
  `hashtag` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  KEY `idTweet` (`idTweet`),
  CONSTRAINT `hashtags_ibfk_1` FOREIGN KEY (`idTweet`) REFERENCES `tweets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `hashtags` (`idTweet`, `hashtag`) VALUES
(2,	'first'),
(2,	'new'),
(2,	'flowers'),
(3,	'first'),
(3,	'new');

DROP TABLE IF EXISTS `tweets`;
CREATE TABLE `tweets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` tinytext NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

INSERT INTO `tweets` (`id`, `user`, `message`, `date`) VALUES
(1,	'Robin',	'this is the message',	'2018-11-12 17:06:02'),
(2,	'Caroline',	'this is the first message from Caroline',	'2018-11-12 17:07:39'),
(3,	'Robin',	'I forgot my first message!',	'2018-11-12 17:08:59');

-- 2018-11-13 13:35:13