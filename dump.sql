-- MySQL dump 10.13  Distrib 5.6.32, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: drm
-- ------------------------------------------------------
-- Server version	5.6.32-1+deb.sury.org~xenial+0.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `drm`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `drm` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `drm`;

--
-- Table structure for table `bans`
--

DROP TABLE IF EXISTS `bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bans` (
  `steamid64` varchar(255) NOT NULL,
  `reason` text CHARACTER SET utf8 NOT NULL,
  `expires` timestamp NULL DEFAULT NULL,
  `banned` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`steamid64`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detours`
--

DROP TABLE IF EXISTS `detours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detours` (
  `steamid64` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`steamid64`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `developers`
--

DROP TABLE IF EXISTS `developers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `developers` (
  `steamid64` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `settings` text CHARACTER SET utf8,
  `admin` tinyint(1) NOT NULL,
  `lastloggedin` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `transactionid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`steamid64`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emailverification`
--

DROP TABLE IF EXISTS `emailverification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emailverification` (
  `steamid64` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`steamid64`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hashes`
--

DROP TABLE IF EXISTS `hashes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hashes` (
  `hashnumber` int(11) NOT NULL DEFAULT '1',
  `script_id` int(11) NOT NULL,
  `developer` varchar(255) NOT NULL,
  `steamid64` varchar(255) NOT NULL,
  `revoked` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `script_data`
--

DROP TABLE IF EXISTS `script_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `script_data` (
  `script_id` int(11) NOT NULL,
  `version` varchar(255) NOT NULL,
  `success_code` text CHARACTER SET utf8 NOT NULL,
  `failure_code` text NOT NULL,
  `latest` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`script_id`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `script_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scripts`
--

DROP TABLE IF EXISTS `scripts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scripts` (
  `script_id` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `developer` varchar(255) NOT NULL,
  `white_black_list` text NOT NULL,
  `blacklist_is_whitelist` tinyint(1) NOT NULL DEFAULT '0',
  `purchase_checking` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`script_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scripts_used`
--

DROP TABLE IF EXISTS `scripts_used`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scripts_used` (
  `steamid64` varchar(255) NOT NULL,
  `script_id` int(11) NOT NULL,
  UNIQUE KEY `constraint` (`steamid64`,`script_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `script_id` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `hostname` text CHARACTER SET utf8 NOT NULL,
  `ip` varchar(255) NOT NULL,
  `port` varchar(255) NOT NULL,
  `countrycode` varchar(255) NOT NULL,
  `os` int(2) NOT NULL,
  `version` varchar(255) NOT NULL,
  `legitimate` tinyint(1) NOT NULL DEFAULT '1',
  `reason` varchar(255) DEFAULT NULL,
  `developer` varchar(255) NOT NULL,
  `lastquery` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `firstquery` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filter` (`ip`,`script_id`,`port`)
) ENGINE=InnoDB AUTO_INCREMENT=396123 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `steamid64` varchar(255) NOT NULL,
  `steamtable` text NOT NULL,
  PRIMARY KEY (`steamid64`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vars`
--

DROP TABLE IF EXISTS `vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vars` (
  `varkey` varchar(255) NOT NULL,
  `varint` int(11) DEFAULT NULL,
  `varstr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`varkey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-29 12:50:31
