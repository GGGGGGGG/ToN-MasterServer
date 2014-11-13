-- MySQL dump 10.13  Distrib 5.1.38, for apple-darwin9.5.0 (i386)
--
-- Host: localhost    Database: s2ce
-- ------------------------------------------------------
-- Server version	5.1.38

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
-- Table structure for table `actionplayers`
--

DROP TABLE IF EXISTS `actionplayers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actionplayers` (
  `user` int(11) NOT NULL,
  `match` int(11) NOT NULL,
  `team` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `assists` int(11) NOT NULL,
  `souls` int(11) NOT NULL,
  `razed` int(11) NOT NULL,
  `pdmg` int(11) NOT NULL,
  `bdmg` int(11) NOT NULL,
  `npc` int(11) NOT NULL,
  `hp_healed` int(11) NOT NULL,
  `res` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `hp_repaired` int(11) NOT NULL,
  `secs` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`user`,`match`,`team`),
  KEY `user` (`user`),
  KEY `match` (`match`),
  KEY `match_2` (`match`,`team`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actionplayers`
--

LOCK TABLES `actionplayers` WRITE;
/*!40000 ALTER TABLE `actionplayers` DISABLE KEYS */;
/*!40000 ALTER TABLE `actionplayers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commanders`
--

DROP TABLE IF EXISTS `commanders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commanders` (
  `user` int(11) NOT NULL DEFAULT '0',
  `match` int(11) NOT NULL DEFAULT '0',
  `team` int(11) NOT NULL DEFAULT '0',
  `builds` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `razed` int(11) NOT NULL,
  `hp_healed` int(11) NOT NULL,
  `pdmg` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `debuffs` int(11) NOT NULL,
  `buffs` int(11) NOT NULL,
  `orders` int(11) NOT NULL,
  `secs` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`user`,`match`,`team`),
  KEY `user` (`user`),
  KEY `match` (`match`),
  KEY `match_2` (`match`,`team`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commanders`
--

LOCK TABLES `commanders` WRITE;
/*!40000 ALTER TABLE `commanders` DISABLE KEYS */;
/*!40000 ALTER TABLE `commanders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maps`
--

DROP TABLE IF EXISTS `maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maps`
--

LOCK TABLES `maps` WRITE;
/*!40000 ALTER TABLE `maps` DISABLE KEYS */;
INSERT INTO `maps` VALUES (1,'bunker'),(2,'crossroads'),(3,'deadlock'),(4,'desolation'),(5,'eden'),(6,'hellpeak'),(7,'hiddenvillage'),(8,'losthills'),(9,'lostvalley'),(10,'moonlight'),(11,'morning'),(12,'storm'),(13,'ancientcities');
/*!40000 ALTER TABLE `maps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` int(11) NOT NULL,
  `servername` varchar(50) NOT NULL,
  `winner` int(11) DEFAULT NULL,
  `duration` time NOT NULL,
  `map` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `map` (`map`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servers` (
  `id` int(11) NOT NULL DEFAULT '0',
  `ip` int(11) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `num_conn` int(11) DEFAULT NULL,
  `max_conn` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `minlevel` int(11) DEFAULT NULL,
  `maxlevel` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipport` (`ip`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servers`
--

LOCK TABLES `servers` WRITE;
/*!40000 ALTER TABLE `servers` DISABLE KEYS */;
INSERT INTO `servers` VALUES (4,NULL,NULL,0,0,'Can East 1',NULL,0,0),(5,NULL,NULL,0,0,'US East 1',NULL,0,0),(6,NULL,NULL,0,0,'n00bstories.com',NULL,0,0),(8,NULL,NULL,0,0,'Beginner EU 1',NULL,0,0),(9,NULL,NULL,0,0,'EU 2',NULL,0,0),(10,NULL,NULL,0,0,'UK 2',NULL,0,0),(11,NULL,NULL,0,0,'Veteran US Central 2',NULL,0,0),(12,NULL,NULL,0,0,'US Central 3',NULL,0,0),(13,NULL,NULL,0,0,'US East 2',NULL,0,0),(14,NULL,NULL,0,0,'Beginner US East 3',NULL,0,0),(15,NULL,NULL,0,0,'US West 1',NULL,0,0),(16,NULL,NULL,0,0,'US West 2',NULL,0,0),(17,NULL,NULL,0,0,'Can East 2',NULL,0,0),(18,NULL,NULL,0,0,'Can East 3',NULL,0,0),(19,NULL,NULL,0,0,'Beginner UK 3',NULL,0,0),(20,NULL,NULL,0,0,'Beginner EU 3',NULL,0,0),(21,NULL,NULL,0,0,'Veteran UK 4',NULL,0,0),(22,NULL,NULL,0,0,'Beginner UK 5',NULL,0,0),(23,NULL,NULL,0,0,'Veteran UK 6',NULL,0,0),(24,NULL,NULL,0,0,'EU 4',NULL,0,0),(25,NULL,NULL,0,0,'EU 5',NULL,0,0),(26,NULL,NULL,0,0,'^rS2howto.com ^yEU6',NULL,0,0),(27,NULL,NULL,0,0,'US Central 7',NULL,0,0),(29,NULL,NULL,0,0,'Veteran US Central 6',NULL,0,0),(30,NULL,NULL,0,0,'QA Savage 2 Server',NULL,0,0),(31,NULL,NULL,0,0,'US Southeast 1',NULL,0,0),(32,NULL,NULL,0,0,'US East 2',NULL,0,0),(33,NULL,NULL,0,0,'EU 4',NULL,0,0),(34,NULL,NULL,0,0,'Beginner US West 3',NULL,0,0),(35,NULL,NULL,0,0,'Veteran US West 4',NULL,0,0),(36,NULL,NULL,0,0,'Veteran US East 4',NULL,0,0),(37,NULL,NULL,0,0,'US East 1',NULL,0,0),(38,NULL,NULL,0,0,'US East Duel Arena',NULL,0,0),(39,NULL,NULL,0,0,'US Central 4',NULL,0,0),(42,NULL,NULL,0,0,'Asia 1',NULL,0,0),(43,NULL,NULL,0,0,'US West 6',NULL,0,0),(44,NULL,NULL,0,0,'US East 1',NULL,0,0),(45,NULL,NULL,0,0,'US East 2',NULL,0,0),(46,NULL,NULL,0,0,'US Central 8',NULL,0,0),(47,NULL,NULL,0,0,'US South 3',NULL,0,0),(48,NULL,NULL,0,0,'US Central 7',NULL,0,0),(49,NULL,NULL,0,0,'US Central 5',NULL,0,0),(50,NULL,NULL,0,0,'US Central 6',NULL,0,0),(51,NULL,NULL,0,0,'US Midwest 5',NULL,0,0),(52,NULL,NULL,0,0,'US West 7',NULL,0,0),(53,NULL,NULL,0,0,'US West 2',NULL,0,0),(54,NULL,NULL,0,0,'US West 2',NULL,0,0),(55,NULL,NULL,0,0,'US West Test Server',NULL,0,0),(56,NULL,NULL,0,0,'US East 2',NULL,0,0),(58,NULL,NULL,0,0,'US East 9',NULL,0,0),(59,NULL,NULL,0,0,'US East 10',NULL,0,0),(60,NULL,NULL,0,0,'Internode Sav2 AU #01',NULL,0,0),(62,NULL,NULL,0,0,'Australia 1',NULL,0,0),(63,NULL,NULL,0,0,'Australia 3',NULL,0,0),(64,NULL,NULL,0,0,'US East 7',NULL,0,0),(65,NULL,NULL,0,0,'US East 8',NULL,0,0),(66,NULL,NULL,0,0,'US East 9',NULL,0,0),(67,NULL,NULL,0,0,'US East 10',NULL,0,0),(68,NULL,NULL,0,0,'US East 11',NULL,0,0),(70,NULL,NULL,0,0,'Can East 4',NULL,0,0),(73,NULL,NULL,0,0,'Can West 1',NULL,0,0),(77,NULL,NULL,0,0,'US West 7b',NULL,0,0),(81,NULL,NULL,0,0,'Can East 1b',NULL,0,0),(82,NULL,NULL,0,0,'US East 1b',NULL,0,0),(83,NULL,NULL,0,0,'Beginner US Central 1',NULL,0,0),(84,NULL,NULL,0,0,'^gMaliken Rules',NULL,0,0),(85,NULL,NULL,0,0,'^wplaysavage2.com ^y#1',NULL,0,0),(86,NULL,NULL,0,0,'EU 2',NULL,0,0),(87,NULL,NULL,0,0,'UK 9',NULL,0,0),(91,NULL,NULL,0,0,'US East 2',NULL,0,0),(95,NULL,NULL,0,0,'Can East 3b',NULL,0,0),(96,NULL,NULL,0,0,'^gMaliken Beginners',NULL,0,0),(97,NULL,NULL,0,0,'EU 3b',NULL,0,0),(98,NULL,NULL,0,0,'UK 4b',NULL,0,0),(99,NULL,NULL,0,0,'UK 5b',NULL,0,0),(100,NULL,NULL,0,0,'UK 6b',NULL,0,0),(101,NULL,NULL,0,0,'^gMaliken Easy Starter',NULL,0,0),(102,NULL,NULL,0,0,'EU 5b',NULL,0,0),(103,NULL,NULL,0,0,'EU 6b',NULL,0,0),(104,NULL,NULL,0,0,'US South 1b',NULL,0,0),(106,NULL,NULL,0,0,'US Midwest 1b',NULL,0,0),(107,NULL,NULL,0,0,'^gMaliken Beginners 2',NULL,0,0),(108,NULL,NULL,0,0,'US Southeast 1b',NULL,0,0),(110,NULL,NULL,0,0,'EU 8b',NULL,0,0),(111,NULL,NULL,0,0,'US West 3b',NULL,0,0),(112,NULL,NULL,0,0,'US West 4b',NULL,0,0),(113,NULL,NULL,0,0,'US East 4b',NULL,0,0),(114,NULL,NULL,0,0,'US East 5b',NULL,0,0),(115,NULL,NULL,0,0,'US East 6b',NULL,0,0),(119,NULL,NULL,0,0,'US West 5b',NULL,0,0),(120,NULL,NULL,0,0,'US West 6b',NULL,0,0),(121,NULL,NULL,0,0,'US Southeast 2b',NULL,0,0),(122,NULL,NULL,0,0,'US Southeast 3b',NULL,0,0),(123,NULL,NULL,0,0,'US South 2b',NULL,0,0),(124,NULL,NULL,0,0,'US South 3b',NULL,0,0),(125,NULL,NULL,0,0,'US Midwest 2b',NULL,0,0),(126,NULL,NULL,0,0,'US Midwest 3b',NULL,0,0),(127,NULL,NULL,0,0,'US Midwest 4b',NULL,0,0),(128,NULL,NULL,0,0,'US Midwest 5b',NULL,0,0),(130,NULL,NULL,0,0,'US West 8b',NULL,0,0),(131,NULL,NULL,0,0,'US West 9b',NULL,0,0),(132,NULL,NULL,0,0,'US West 10b',NULL,0,0),(133,NULL,NULL,0,0,'US East 7b',NULL,0,0),(135,NULL,NULL,0,0,'US East 9b',NULL,0,0),(136,NULL,NULL,0,0,'US East 10b',NULL,0,0),(138,NULL,NULL,0,0,'Internode Sav2 AU #02',NULL,0,0),(142,NULL,NULL,0,0,'US East 8b',NULL,0,0),(145,NULL,NULL,0,0,'US East 11b',NULL,0,0),(155,NULL,NULL,0,0,'^gFRAG^y.com.br',NULL,0,0),(156,NULL,NULL,0,0,'medkitgames.com.br',NULL,0,0),(157,NULL,NULL,0,0,'US South 4',NULL,0,0),(158,NULL,NULL,0,0,'US South 4b',NULL,0,0),(159,NULL,NULL,0,0,'US Central Duel',NULL,0,0),(160,NULL,NULL,0,0,'US Central 9',NULL,0,0),(161,NULL,NULL,0,0,'US South 5c',NULL,0,0),(162,NULL,NULL,0,0,'US South 5d',NULL,0,0),(169,NULL,NULL,0,0,'EU 5',NULL,0,0),(170,NULL,NULL,0,0,'EU 9b',NULL,0,0),(171,NULL,NULL,0,0,'EU 6',NULL,0,0),(172,NULL,NULL,0,0,'EU 6',NULL,0,0),(173,NULL,NULL,0,0,'EU 7',NULL,0,0),(174,NULL,NULL,0,0,'EU 11b',NULL,0,0),(175,NULL,NULL,0,0,'EU 8',NULL,0,0),(176,NULL,NULL,0,0,'EU 12b',NULL,0,0),(177,NULL,NULL,0,0,'US Midwest 1c',NULL,0,0),(179,NULL,NULL,0,0,'EU Test Server',NULL,0,0),(180,NULL,NULL,0,0,'EU 13b',NULL,0,0),(12220,NULL,NULL,0,0,'Beginner US Central 5',NULL,0,0),(12223,NULL,NULL,0,0,'UK 7',NULL,0,0),(12224,NULL,NULL,0,0,'UK 8',NULL,0,0),(22761,NULL,NULL,0,0,'^rNewerth.com ^yEU1',NULL,0,0),(22762,NULL,NULL,0,0,'^rNewerth.com ^yEU2',NULL,0,0),(26986,NULL,NULL,0,0,'^bAlkon.com.ar ^c(Ranked)',NULL,0,0),(29603,NULL,NULL,0,0,'Nordic 1',NULL,0,0),(7,NULL,NULL,0,0,'UK 1',NULL,0,0);
/*!40000 ALTER TABLE `servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match` int(11) NOT NULL,
  `race` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matchrace` (`match`,`race`),
  KEY `match` (`match`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `account_id` int(11) NOT NULL DEFAULT '0',
  `comm_id` int(11) NOT NULL DEFAULT '0',
  `match_id` int(11) NOT NULL DEFAULT '0',
  `vote` int(11) NOT NULL,
  `reason` text,
  PRIMARY KEY (`account_id`,`comm_id`,`match_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-02-02  0:45:27
