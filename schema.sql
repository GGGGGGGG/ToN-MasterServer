/*
SQLyog Community
MySQL - 10.0.29-MariaDB-0ubuntu0.16.10.1 : Database - masterserver
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `actionplayers` */

DROP TABLE IF EXISTS `actionplayers`;

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

/*Table structure for table `buddies` */

DROP TABLE IF EXISTS `buddies`;

CREATE TABLE `buddies` (
  `source_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `note` varchar(50) DEFAULT NULL,
  `clan_name` varchar(50) DEFAULT NULL,
  `clan_tag` varchar(50) DEFAULT NULL,
  `clan_img` varchar(50) DEFAULT NULL,
  `avatar` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `clans` */

DROP TABLE IF EXISTS `clans`;

CREATE TABLE `clans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clan_name` varchar(20) DEFAULT NULL,
  `clan_tag` varchar(3) DEFAULT NULL,
  `clan_img` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `commanders` */

DROP TABLE IF EXISTS `commanders`;

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

/*Table structure for table `items` */

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `account_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `exp_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `maps` */

DROP TABLE IF EXISTS `maps`;

CREATE TABLE `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Table structure for table `matches` */

DROP TABLE IF EXISTS `matches`;

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

/*Table structure for table `player` */

DROP TABLE IF EXISTS `player`;

CREATE TABLE `player` (
  `user` int(11) NOT NULL DEFAULT '0',
  `server` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `online` int(11) DEFAULT '0',
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `playerinfos` */

DROP TABLE IF EXISTS `playerinfos`;

CREATE TABLE `playerinfos` (
  `account_id` int(11) NOT NULL,
  `overall_r` int(11) DEFAULT NULL,
  `sf` int(11) DEFAULT NULL,
  `lf` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `clan_id` int(11) DEFAULT NULL,
  `karma` int(11) DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  KEY `FK_ClanPlayerInfo` (`clan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `playerstats` */

DROP TABLE IF EXISTS `playerstats`;

CREATE TABLE `playerstats` (
  `account_id` int(11) NOT NULL,
  `exp` int(11) DEFAULT NULL,
  `earned_exp` int(11) DEFAULT NULL,
  `wins` int(11) DEFAULT NULL,
  `losses` int(11) DEFAULT NULL,
  `d_conns` int(11) DEFAULT NULL,
  `kills` int(11) DEFAULT NULL,
  `deaths` int(11) DEFAULT NULL,
  `assists` int(11) DEFAULT NULL,
  `souls` int(11) DEFAULT NULL,
  `razed` int(11) DEFAULT NULL,
  `pdmg` int(11) DEFAULT NULL,
  `bdmg` int(11) DEFAULT NULL,
  `npc` int(11) DEFAULT NULL,
  `hp_healed` int(11) DEFAULT NULL,
  `res` int(11) DEFAULT NULL,
  `gold` int(11) DEFAULT NULL,
  `hp_repaired` int(11) DEFAULT NULL,
  `secs` int(11) DEFAULT NULL,
  `total_secs` int(11) DEFAULT NULL,
  `c_wins` int(11) DEFAULT NULL,
  `c_losses` int(11) DEFAULT NULL,
  `c_d_conns` int(11) DEFAULT NULL,
  `c_exp` int(11) DEFAULT NULL,
  `c_earned_exp` int(11) DEFAULT NULL,
  `c_builds` int(11) DEFAULT NULL,
  `c_gold` int(11) DEFAULT NULL,
  `c_razed` int(11) DEFAULT NULL,
  `c_hp_healed` int(11) DEFAULT NULL,
  `c_pdmg` int(11) DEFAULT NULL,
  `c_kills` int(11) DEFAULT NULL,
  `c_assists` int(11) DEFAULT NULL,
  `c_debuffs` int(11) DEFAULT NULL,
  `c_buffs` int(11) DEFAULT NULL,
  `c_orders` int(11) DEFAULT NULL,
  `c_secs` int(11) DEFAULT NULL,
  `c_winstreak` int(11) DEFAULT NULL,
  `malphas` int(11) DEFAULT NULL,
  `revenant` int(11) DEFAULT NULL,
  `devourer` int(11) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `server` */

DROP TABLE IF EXISTS `server`;

CREATE TABLE `server` (
  `id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(32) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `num_conn` int(11) DEFAULT NULL,
  `max_conn` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `minlevel` int(11) DEFAULT NULL,
  `maxlevel` int(11) DEFAULT NULL,
  `official` char(1) NOT NULL DEFAULT 'N',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipport` (`ip`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `servers` */

DROP TABLE IF EXISTS `servers`;

CREATE TABLE `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `num_conn` int(11) DEFAULT NULL,
  `max_conn` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `minlevel` int(11) DEFAULT NULL,
  `maxlevel` int(11) DEFAULT NULL,
  `official` char(1) NOT NULL DEFAULT 'N',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipport` (`ip`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `teams` */

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match` int(11) NOT NULL,
  `race` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matchrace` (`match`,`race`),
  KEY `match` (`match`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `account_type` varchar(50) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `temp_password` char(1) DEFAULT 'N',
  `cookie` varchar(64) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `votes` */

DROP TABLE IF EXISTS `votes`;

CREATE TABLE `votes` (
  `account_id` int(11) NOT NULL DEFAULT '0',
  `comm_id` int(11) NOT NULL DEFAULT '0',
  `match_id` int(11) NOT NULL DEFAULT '0',
  `vote` int(11) NOT NULL,
  `reason` text,
  PRIMARY KEY (`account_id`,`comm_id`,`match_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
