DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
   `account_id` int(11) NOT NULL,
   `item_id` int(11) DEFAULT NULL,
   `type` int(11) DEFAULT NULL,
   `exp_date` DATE DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
