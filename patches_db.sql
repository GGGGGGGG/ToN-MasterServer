DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `major` int(3) NOT NULL DEFAULT 0,
  `minor` int(3) NOT NULL DEFAULT 0,
  `build` int(3) NOT NULL DEFAULT 0,
  `revision` int(3) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `size` varchar(32) DEFAULT NULL,
  `info` blob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `version_check`;
CREATE TABLE `version_check` (
  `name` varchar(120) NOT NULL,
  `major` int(3) NOT NULL DEFAULT 0,
  `minor` int(3) NOT NULL DEFAULT 0,
  `build` int(3) NOT NULL DEFAULT 0,
  `revision` int(3) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `size` varchar(32) DEFAULT NULL,
  `os` char(3) NOT NULL,
  `arch` varchar(10) NOT NULL,
  `info` blob DEFAULT NULL,
  `remove` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`name`,`os`,`arch`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;