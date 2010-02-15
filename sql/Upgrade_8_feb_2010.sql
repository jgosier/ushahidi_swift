-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
--- Generation Time: Feb 08, 2010 at 04:04 PM
--- Server version: 5.1.37
--- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

---
--- Database: `ushahidi`
---

--update as of 12-feb-2010

--- --------------------------------------------------------

---
--- Table structure for table `feed`
---

DROP TABLE IF EXISTS `feed`;

CREATE TABLE IF NOT EXISTS `feed` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feed_name` varchar(255) DEFAULT NULL,
  `feed_url` varchar(255) DEFAULT NULL,
  `category_id` int(5) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `feed_cache` text,
  `feed_active` tinyint(4) DEFAULT '1',
  `feed_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=217 ;

---
--- Dumping data for table `feed`
---

INSERT INTO `feed` (`id`, `feed_name`, `feed_url`, `category_id`, `weight`, `feed_cache`, `feed_active`, `feed_update`) VALUES
(212, 'http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml', 'http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml', 3, '100.00', NULL, 1, 1265631321),
(213, 'http://www.cnn.com/?eref=rss_topstories', 'http://www.cnn.com/?eref=rss_topstories', 4, '100.00', NULL, 1, 1265631324),
(214, 'http://rss.news.yahoo.com/rss/business', 'http://rss.news.yahoo.com/rss/business', 5, '100.00', NULL, 1, 1265631332),
(215, 'http://rss.news.yahoo.com/rss/entertainment', 'http://rss.news.yahoo.com/rss/entertainment', 5, '100.00', NULL, 1, 1265631338),



--- --------------------------------------------------------

---
--- Table structure for table `feed_item`
---
DROP TABLE IF EXISTS `feed_item`;

CREATE TABLE IF NOT EXISTS `feed_item` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `location_id` bigint(20) DEFAULT '0',
  `incident_id` int(11) NOT NULL DEFAULT '0',
  `item_title` varchar(255) DEFAULT NULL,
  `item_description` text,
  `item_link` varchar(255) DEFAULT NULL,
  `item_date` datetime DEFAULT NULL,
  `item_source` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=537 ;

---
--- Dumping data for table `feed_item`
---


DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `locale` varchar(10) NOT NULL DEFAULT 'en_US',
  `category_type` tinyint(4) DEFAULT NULL,
  `category_title` varchar(255) DEFAULT NULL,
  `category_description` text,
  `category_color` varchar(20) DEFAULT NULL,
  `category_image` varchar(100) DEFAULT NULL,
  `category_image_shadow` varchar(100) DEFAULT NULL,
  `category_visible` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_visible` (`category_visible`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

---
--- Dumping data for table `category`
---

INSERT INTO `category` (`id`, `parent_id`, `locale`, `category_type`, `category_title`, `category_description`, `category_color`, `category_image`, `category_image_shadow`, `category_visible`) VALUES
(1, 0, 'en_US', 5, 'TWITTER', 'TWITTER', 'FFFFFF', NULL, NULL, 1),
(2, 0, 'en_US', 5, 'SMS', 'SMS', 'FFFFFF', NULL, NULL, 1),
(3, 0, 'en_US', 5, 'BLOGS', 'BLOGS', 'FFFFFF', NULL, NULL, 1),
(4, 0, 'en_US', 5, 'NEWS', 'NEWS', 'FFFFFF', NULL, NULL, 1),
(5, 0, 'en_US', 5, 'OTHERS', 'OTHERS', 'FFFFFF', NULL, NULL, 1),
(10, 0, 'en_US', 5, 'EMAILS', 'EMAILS', 'FFFFFF', NULL, NULL, 0),
(6, 0, 'en_US', 5, 'GOVERNMENT FORCES', 'GOVERNMENT FORCES', '9999FF', NULL, NULL, 0),
(7, 0, 'en_US', 5, 'CIVILIANS', 'CIVILIANS', '66CC00', NULL, NULL, 0),
(8, 0, 'en_US', 5, 'LOOTING', 'LOOTING', 'FFCC00', NULL, NULL, 0),
(9, 0, 'en_US', 5, 'PEACE EFFORTS', 'PEACE EFFORTS', 'FAEBD7', NULL, NULL, 0),
(11, 0, 'en_US', 5, 'TWITTERSEARCH', 'TWTTERSEARCH', 'FFFFFF', NULL, NULL, 0);


CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tagged_id` int(11) NOT NULL DEFAULT '0',
  `tablename` varchar(100) NOT NULL DEFAULT 'en_US',
  `tags` varchar(500)DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;







