-- MySQL dump 10.10
--
-- Host: localhost    Database: kkk
-- ------------------------------------------------------
-- Server version	5.0.22-Debian_0ubuntu6.06.3-log

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
-- Table structure for table `avatar`
--

DROP TABLE IF EXISTS `avatar`;
CREATE TABLE `avatar` (
  `avatar_id` int(11) NOT NULL auto_increment,
  `avatar_name` varchar(50) NOT NULL,
  `avatar_image` varchar(50) NOT NULL,
  `active` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`avatar_id`),
  UNIQUE KEY `avatar_image` (`avatar_image`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `board`
--

DROP TABLE IF EXISTS `board`;
CREATE TABLE `board` (
  `board_id` smallint(3) NOT NULL auto_increment,
  `board_name` varchar(100) NOT NULL,
  `board_descr` varchar(255) NOT NULL,
  `board_locked` enum('N','Y') NOT NULL default 'N',
  `news_source` enum('N','Y') NOT NULL default 'N',
  `required_permission_id` int(11) NOT NULL,
  `order_by` tinyint(2) NOT NULL,
  PRIMARY KEY  (`board_id`),
  KEY `required_permission_id` (`required_permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `board_thread`
--

DROP TABLE IF EXISTS `board_thread`;
CREATE TABLE `board_thread` (
  `board_thread_id` int(10) unsigned NOT NULL auto_increment,
  `board_id` smallint(3) NOT NULL,
  `thread_name` varchar(60) NOT NULL,
  `user_id` int(11) NOT NULL,
  `thread_created_datetime` datetime NOT NULL,
  `thread_last_posted_datetime` datetime NOT NULL,
  `stickied` tinyint(1) NOT NULL default '0',
  `locked` enum('N','Y') NOT NULL default 'N',
  PRIMARY KEY  (`board_thread_id`),
  KEY `board_id` (`board_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `board_thread_post`
--

DROP TABLE IF EXISTS `board_thread_post`;
CREATE TABLE `board_thread_post` (
  `board_thread_post_id` int(10) unsigned NOT NULL auto_increment,
  `board_thread_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `posted_datetime` datetime NOT NULL,
  `post_text` text NOT NULL,
  PRIMARY KEY  (`board_thread_post_id`),
  KEY `board_thread_id` (`board_thread_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `cron_tab`
--

DROP TABLE IF EXISTS `cron_tab`;
CREATE TABLE `cron_tab` (
  `cron_tab_id` int(11) NOT NULL auto_increment,
  `cron_class` varchar(50) NOT NULL,
  `cron_frequency_seconds` int(10) unsigned NOT NULL,
  `unixtime_next_run` bigint(11) unsigned NOT NULL,
  `enabled` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`cron_tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `datetime_format`
--

DROP TABLE IF EXISTS `datetime_format`;
CREATE TABLE `datetime_format` (
  `datetime_format_id` int(11) NOT NULL auto_increment,
  `datetime_format_name` varchar(30) NOT NULL,
  `datetime_format` text NOT NULL,
  PRIMARY KEY  (`datetime_format_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `item_class`
--

DROP TABLE IF EXISTS `item_class`;
CREATE TABLE `item_class` (
  `item_class_id` int(11) NOT NULL auto_increment,
  `php_class` varchar(30) NOT NULL,
  `class_descr` varchar(30) NOT NULL,
  `relative_image_dir` varchar(50) NOT NULL,
  `verb` varchar(30) NOT NULL,
  PRIMARY KEY  (`item_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `item_type`
--

DROP TABLE IF EXISTS `item_type`;
CREATE TABLE `item_type` (
  `item_type_id` int(11) NOT NULL auto_increment,
  `item_name` varchar(50) NOT NULL,
  `item_descr` text NOT NULL,
  `item_class_id` int(11) NOT NULL,
  `happiness_bonus` tinyint(3) unsigned NOT NULL,
  `hunger_bonus` tinyint(3) unsigned NOT NULL,
  `pet_specie_color_id` int(11) NOT NULL,
  `item_image` varchar(200) NOT NULL,
  PRIMARY KEY  (`item_type_id`),
  KEY `item_class_id` (`item_class_id`),
  KEY `pet_specie_color_id` (`pet_specie_color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `jump_page`
--

DROP TABLE IF EXISTS `jump_page`;
CREATE TABLE `jump_page` (
  `jump_page_id` int(10) unsigned NOT NULL auto_increment,
  `page_title` varchar(50) NOT NULL default '',
  `page_html_title` varchar(255) NOT NULL default '',
  `layout_type` enum('basic','deep') NOT NULL default 'deep',
  `page_slug` varchar(25) NOT NULL default '',
  `access_level` enum('restricted','user','public') NOT NULL default 'user',
  `restricted_permission_api_name` varchar(35) NOT NULL,
  `php_script` varchar(100) NOT NULL default '',
  `include_tinymce` enum('N','Y') NOT NULL default 'N',
  `active` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`jump_page_id`),
  UNIQUE KEY `page_slug` (`page_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `pet_specie`
--

DROP TABLE IF EXISTS `pet_specie`;
CREATE TABLE `pet_specie` (
  `pet_specie_id` int(11) NOT NULL auto_increment,
  `specie_name` varchar(50) NOT NULL,
  `specie_descr` text NOT NULL,
  `relative_image_dir` varchar(200) NOT NULL,
  `max_hunger` tinyint(3) unsigned NOT NULL,
  `max_happiness` tinyint(3) unsigned NOT NULL,
  `available` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`pet_specie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `pet_specie_color`
--

DROP TABLE IF EXISTS `pet_specie_color`;
CREATE TABLE `pet_specie_color` (
  `pet_specie_color_id` int(11) NOT NULL auto_increment,
  `color_name` varchar(30) NOT NULL,
  `color_img` varchar(200) NOT NULL,
  `base_color` enum('N','Y') NOT NULL default 'N',
  PRIMARY KEY  (`pet_specie_color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `pet_specie_pet_specie_color`
--

DROP TABLE IF EXISTS `pet_specie_pet_specie_color`;
CREATE TABLE `pet_specie_pet_specie_color` (
  `pet_specie_pet_specie_color_id` int(11) NOT NULL auto_increment,
  `pet_specie_id` int(11) NOT NULL,
  `pet_specie_color_id` int(11) NOT NULL,
  PRIMARY KEY  (`pet_specie_pet_specie_color_id`),
  UNIQUE KEY `pet_specie_id` (`pet_specie_id`,`pet_specie_color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Links a color to a specie. Without entry, specie cannot beco';

--
-- Table structure for table `shop`
--

DROP TABLE IF EXISTS `shop`;
CREATE TABLE `shop` (
  `shop_id` int(11) NOT NULL auto_increment,
  `shop_name` varchar(30) NOT NULL,
  `shop_image` varchar(200) NOT NULL,
  `welcome_text` text NOT NULL,
  PRIMARY KEY  (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `shop_inventory`
--

DROP TABLE IF EXISTS `shop_inventory`;
CREATE TABLE `shop_inventory` (
  `shop_inventory_id` int(11) NOT NULL auto_increment,
  `item_type_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `quantity` int(11) unsigned NOT NULL,
  `price` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`shop_inventory_id`),
  KEY `item_type_id` (`item_type_id`),
  KEY `shop_id` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `shop_restock`
--

DROP TABLE IF EXISTS `shop_restock`;
CREATE TABLE `shop_restock` (
  `shop_restock_id` int(11) NOT NULL auto_increment,
  `shop_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `restock_frequency_seconds` int(11) unsigned NOT NULL,
  `unixtime_next_restock` int(11) unsigned NOT NULL,
  `min_price` bigint(11) NOT NULL,
  `max_price` bigint(11) NOT NULL,
  `min_quantity` smallint(3) NOT NULL,
  `max_quantity` smallint(3) NOT NULL,
  `store_quantity_cap` smallint(3) unsigned NOT NULL,
  PRIMARY KEY  (`shop_restock_id`),
  KEY `shop_id` (`shop_id`,`item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `staff_group`
--

DROP TABLE IF EXISTS `staff_group`;
CREATE TABLE `staff_group` (
  `staff_group_id` int(11) NOT NULL auto_increment,
  `group_name` varchar(50) NOT NULL,
  `group_descr` text NOT NULL,
  PRIMARY KEY  (`staff_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `staff_group_staff_permission`
--

DROP TABLE IF EXISTS `staff_group_staff_permission`;
CREATE TABLE `staff_group_staff_permission` (
  `staff_group_staff_permission` int(11) NOT NULL auto_increment,
  `staff_group_id` int(11) NOT NULL,
  `staff_permission_id` int(11) NOT NULL,
  PRIMARY KEY  (`staff_group_staff_permission`),
  UNIQUE KEY `staff_group_id` (`staff_group_id`,`staff_permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `staff_permission`
--

DROP TABLE IF EXISTS `staff_permission`;
CREATE TABLE `staff_permission` (
  `staff_permission_id` int(11) NOT NULL auto_increment,
  `api_name` varchar(50) NOT NULL,
  `permission_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`staff_permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `timezone`
--

DROP TABLE IF EXISTS `timezone`;
CREATE TABLE `timezone` (
  `timezone_id` int(11) NOT NULL auto_increment,
  `timezone_short_name` varchar(4) NOT NULL,
  `timezone_long_name` varchar(32) character set utf8 NOT NULL,
  `timezone_continent` varchar(13) NOT NULL,
  `timezone_offset` float(3,1) NOT NULL,
  `order_by` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`timezone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `currency` bigint(20) unsigned NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `password_hash` char(32) default NULL,
  `current_salt` char(32) NOT NULL,
  `current_salt_expiration` datetime NOT NULL,
  `registered_ip_addr` varchar(16) default NULL,
  `last_ip_addr` varchar(16) default NULL,
  `last_activity` datetime default NULL,
  `access_level` enum('banned','user') NOT NULL default 'user',
  `email` text NOT NULL,
  `age` smallint(3) unsigned NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `profile` text NOT NULL,
  `signature` text NOT NULL,
  `avatar_id` int(11) NOT NULL,
  `user_title` varchar(20) NOT NULL default 'User',
  `datetime_created` datetime default NULL,
  `post_count` int(10) unsigned NOT NULL,
  `textarea_preference` enum('tinymce','plain') NOT NULL default 'tinymce',
  `datetime_last_post` datetime NOT NULL,
  `active_user_pet_id` int(11) NOT NULL,
  `timezone_id` int(11) NOT NULL,
  `datetime_format_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `active_user_pet_id` (`active_user_pet_id`),
  KEY `avatar_id` (`avatar_id`),
  KEY `timezone_id` (`timezone_id`),
  KEY `datetime_format_id` (`datetime_format_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `user_item`
--

DROP TABLE IF EXISTS `user_item`;
CREATE TABLE `user_item` (
  `user_item_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_item_id`),
  KEY `user_id` (`user_id`),
  KEY `item_type_id` (`item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `user_message`
--

DROP TABLE IF EXISTS `user_message`;
CREATE TABLE `user_message` (
  `user_message_id` int(11) unsigned NOT NULL auto_increment,
  `sender_user_id` int(11) NOT NULL,
  `recipient_user_id` int(11) NOT NULL,
  `recipient_list` text NOT NULL,
  `message_title` varchar(255) NOT NULL,
  `message_body` text NOT NULL,
  `sent_at` datetime NOT NULL,
  `message_read` enum('N','Y') NOT NULL default 'N',
  PRIMARY KEY  (`user_message_id`),
  KEY `sender_user_id` (`sender_user_id`),
  KEY `recipient_user_id` (`recipient_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `user_notification`
--

DROP TABLE IF EXISTS `user_notification`;
CREATE TABLE `user_notification` (
  `user_notification_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `notification_text` text NOT NULL,
  `notification_url` text NOT NULL,
  `notification_datetime` datetime NOT NULL,
  PRIMARY KEY  (`user_notification_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `user_pet`
--

DROP TABLE IF EXISTS `user_pet`;
CREATE TABLE `user_pet` (
  `user_pet_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `pet_specie_id` int(11) NOT NULL,
  `pet_specie_color_id` int(11) NOT NULL,
  `pet_name` varchar(25) NOT NULL,
  `hunger` tinyint(3) unsigned NOT NULL,
  `happiness` tinyint(3) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `unixtime_next_decrement` int(10) unsigned NOT NULL,
  `profile` text NOT NULL,
  PRIMARY KEY  (`user_pet_id`),
  KEY `user_id` (`user_id`),
  KEY `pet_specie_id` (`pet_specie_id`,`pet_specie_color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Pets = specie + user + color.';

--
-- Table structure for table `user_staff_group`
--

DROP TABLE IF EXISTS `user_staff_group`;
CREATE TABLE `user_staff_group` (
  `user_staff_group_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `staff_group_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_staff_group_id`),
  UNIQUE KEY `user_id` (`user_id`,`staff_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

