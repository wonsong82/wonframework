--
-- Table structure for table `won_contact_contact`
--

DROP TABLE IF EXISTS `won_contact_contact`;

CREATE TABLE `won_contact_contact` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT 'new name',
  `country_code` int(3) DEFAULT '1',
  `tel` int(15) DEFAULT '2120010001',
  `fax` int(15) DEFAULT '2120010002',
  `address1` varchar(255) DEFAULT 'address line 1',
  `address2` varchar(255) DEFAULT 'address line 2',
  `facebook` varchar(255) DEFAULT '',
  `twitter` varchar(255) DEFAULT '',
  `yelp` varchar(255) DEFAULT '',
  `hours_mon_s` varchar(255) NOT NULL DEFAULT '9am',
  `hours_mon_e` varchar(255) NOT NULL DEFAULT '7pm',
  `hours_tue_s` varchar(255) NOT NULL DEFAULT '9am',
  `hours_tue_e` varchar(255) NOT NULL DEFAULT '7pm',
  `hours_wed_s` varchar(255) NOT NULL DEFAULT '9am',
  `hours_wed_e` varchar(255) NOT NULL DEFAULT '7pm',
  `hours_thu_s` varchar(255) NOT NULL DEFAULT '9am',
  `hours_thu_e` varchar(255) NOT NULL DEFAULT '7pm',
  `hours_fri_s` varchar(255) NOT NULL DEFAULT '9am',
  `hours_fri_e` varchar(255) NOT NULL DEFAULT '7pm',
  `hours_sat_s` varchar(255) NOT NULL DEFAULT '9am',
  `hours_sat_e` varchar(255) NOT NULL DEFAULT '7pm',
  `hours_sun_s` varchar(255) NOT NULL DEFAULT '9am',
  `hours_sun_e` varchar(255) NOT NULL DEFAULT '7pm',
  `tel2` int(15) DEFAULT '1111111111',
  `email` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `won_contact_contact`
--

INSERT INTO `won_contact_contact` VALUES
('6','Ave A 에비뉴에이','1','2126740700','0','81 Avenue A.','New York, NY 10008','http://www.facebook.com/pages/Karaoke-Sing-Sing-AveA/129251557112376','https://twitter.com/singsingavea','a\'bb\"cc\\','3pm','4am','3pm','4am','3pm','4am','3pm','4am','2pm','4am','2pm','4am','2pm','4am','0','ave@karaokesingsing.com'),
('7','St. Marks','1','2123877800','0','9 Saint Marks Pl.','New York, NY 10003','http://www.facebook.com/pages/Singsing-karaoke-Stmarks/106651919386646','https://twitter.com/singsingstmarks','','1pm','4am','1pm','4am','1pm','4am','1pm','4am','1pm','4am','1pm','4am','1pm','4am','0','stmarks@karaokesingsing.com');

-- ---------------------------------------------------

--
-- Table structure for table `won_url_url`
--

DROP TABLE IF EXISTS `won_url_url`;

CREATE TABLE `won_url_url` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  KEY `template` (`template`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `won_url_url`
--

INSERT INTO `won_url_url` VALUES
('1','404error','404error.php'),
('2','','home.php');

-- ---------------------------------------------------

--
-- Table structure for table `won_user_group`
--

DROP TABLE IF EXISTS `won_user_group`;

CREATE TABLE `won_user_group` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `editable` int(1) DEFAULT '1',
  `sort_order` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `won_user_group`
--

INSERT INTO `won_user_group` VALUES
('1','Administrator','0','1');

-- ---------------------------------------------------

--
-- Table structure for table `won_user_membership`
--

DROP TABLE IF EXISTS `won_user_membership`;

CREATE TABLE `won_user_membership` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `won_user_membership`
--

INSERT INTO `won_user_membership` VALUES
('1','1','1');

-- ---------------------------------------------------

--
-- Table structure for table `won_user_user`
--

DROP TABLE IF EXISTS `won_user_user`;

CREATE TABLE `won_user_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `active` int(1) DEFAULT '1',
  `banned` int(1) DEFAULT '0',
  `joined` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `won_user_user`
--

INSERT INTO `won_user_user` VALUES
('1','admin','f1c1592588411002af340cbaedd6fc33','1','0','2012-07-26 13:45:09');

-- ---------------------------------------------------

