CREATE DATABASE IF NOT EXISTS `wikiportret`
USE `wikiportret`;



CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `description` text,
  `archived` int(1) NOT NULL DEFAULT '0',
  `owner` int(3) NOT NULL DEFAULT '0',
  `timestamp` int(10) NOT NULL,
  `license` varchar(50) DEFAULT 'CC-BY-SA 3.0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `otrsname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `isSysop` int(1) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


