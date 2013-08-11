-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Ned 04. srp 2013, 14:43
-- Verze serveru: 5.6.12
-- Verze PHP: 5.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `zzsng`
--
CREATE DATABASE IF NOT EXISTS `zzsng` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `zzsng`;

-- --------------------------------------------------------

--
-- Struktura tabulky `exam_mistakes`
--

CREATE TABLE IF NOT EXISTS `exam_mistakes` (
  `term` int(11) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `exam_results`
--

CREATE TABLE IF NOT EXISTS `exam_results` (
  `cycle` int(255) NOT NULL,
  `hits` int(255) NOT NULL DEFAULT '0',
  `mistakes` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cycle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `exam_terms`
--

CREATE TABLE IF NOT EXISTS `exam_terms` (
  `term` int(11) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `created` datetime NOT NULL,
  `last_change` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `last_change` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=74 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `lessons`
--

CREATE TABLE IF NOT EXISTS `lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `language` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `last_change` datetime NOT NULL,
  `c_term_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jmeno` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1005 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `lessons_groups`
--

CREATE TABLE IF NOT EXISTS `lessons_groups` (
  `lekce` int(11) NOT NULL,
  `skupina` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `learned` tinyint(1) NOT NULL DEFAULT '0',
  `problematic` tinyint(1) NOT NULL DEFAULT '0',
  `created` time NOT NULL,
  `last_change` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `terms`
--

CREATE TABLE IF NOT EXISTS `terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `lesson` int(11) NOT NULL,
  `term` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `metadata` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `translation` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `comment` text COLLATE utf8_czech_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `last_change` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lekce` (`lesson`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=33577 ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
