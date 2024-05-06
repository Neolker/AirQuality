-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: md406.wedos.net:3306
-- Generation Time: May 06, 2024 at 04:16 PM
-- Server version: 10.4.31-MariaDB-log
-- PHP Version: 5.4.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `d153019_airqual`
--

-- --------------------------------------------------------

--
-- Table structure for table `namerena_data`
--

CREATE TABLE IF NOT EXISTS `namerena_data` (
  `ndid` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_zarizeni` int(11) DEFAULT 0,
  `id_uzivatele` int(11) DEFAULT 0,
  `unix_ts` bigint(20) DEFAULT 0,
  `co2_prumer` double DEFAULT 0,
  `co2_trend` smallint(6) DEFAULT 0,
  `teplota_prumer` double DEFAULT 0,
  `teplota_trend` smallint(6) DEFAULT 0,
  `vlhkost_prumer` double DEFAULT 0,
  `vlhkost_trend` smallint(6) DEFAULT 0,
  `baterie_hodnota` double DEFAULT 0,
  `pozice` smallint(6) DEFAULT 0 COMMENT '0 až 5 dle strany, na které se předmět nachází',
  PRIMARY KEY (`ndid`),
  KEY `id_zarizeni` (`id_zarizeni`),
  KEY `id_uzivatele` (`id_uzivatele`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `nastaveni`
--

CREATE TABLE IF NOT EXISTS `nastaveni` (
  `nid` int(11) NOT NULL AUTO_INCREMENT,
  `klic` varchar(128) DEFAULT NULL,
  `nazev` varchar(256) DEFAULT NULL,
  `hodnota` text DEFAULT NULL,
  `popis` text DEFAULT NULL,
  `typ` smallint(6) DEFAULT 0,
  `poradi` int(11) DEFAULT 0,
  `zobrazovat` smallint(6) DEFAULT 1,
  PRIMARY KEY (`nid`),
  KEY `klic` (`klic`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `uzivatele`
--

CREATE TABLE IF NOT EXISTS `uzivatele` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(64) DEFAULT NULL,
  `prava` mediumint(9) DEFAULT 0,
  `heslo` varchar(128) DEFAULT NULL,
  `heslo_2` varchar(128) DEFAULT NULL,
  `login` varchar(128) DEFAULT NULL,
  `posledni_aktivita_ts` bigint(20) DEFAULT 0,
  `posledni_prihlaseni_ts` bigint(20) DEFAULT 0,
  `posledni_prihlaseni_ip` varchar(256) DEFAULT NULL,
  `pocet_prihlaseni` bigint(20) DEFAULT 0,
  `registrace_ts` bigint(20) DEFAULT 0,
  `email` varchar(128) DEFAULT NULL,
  `titul` varchar(32) DEFAULT NULL,
  `jmeno` varchar(64) DEFAULT NULL,
  `prijmeni` varchar(64) DEFAULT NULL,
  `spolecnost` varchar(64) DEFAULT NULL,
  `nazev` varchar(256) DEFAULT NULL,
  `telefon` varchar(32) DEFAULT NULL,
  `darkmode` smallint(6) DEFAULT 0,
  `ajaxmode` smallint(6) DEFAULT 1,
  `infomode` smallint(6) DEFAULT 0,
  `varsmode` smallint(6) DEFAULT 0,
  `aktivni_uzivatel` int(11) DEFAULT 1,
  `odstraneny_uzivatel` int(11) DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `session` (`session`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

--
-- Table structure for table `zarizeni`
--

CREATE TABLE IF NOT EXISTS `zarizeni` (
  `zid` int(11) NOT NULL AUTO_INCREMENT,
  `id_uzivatele` int(11) DEFAULT 0,
  `nazev` varchar(256) DEFAULT NULL,
  `vyrobni_cislo` varchar(256) DEFAULT NULL,
  `lokalita` varchar(256) DEFAULT NULL,
  `nastaveni_co2_cervena` int(11) DEFAULT 1500,
  `nastaveni_co2_zluta` int(11) DEFAULT 1000,
  `nastaveni_co2_zelena` int(11) DEFAULT 0,
  PRIMARY KEY (`zid`),
  UNIQUE KEY `vyrobni_cislo` (`vyrobni_cislo`),
  KEY `id_uzivatele` (`id_uzivatele`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=9 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
