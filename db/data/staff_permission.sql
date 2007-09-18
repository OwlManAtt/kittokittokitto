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
-- Dumping data for table `staff_permission`
--


/*!40000 ALTER TABLE `staff_permission` DISABLE KEYS */;
LOCK TABLES `staff_permission` WRITE;
INSERT INTO `staff_permission` (`staff_permission_id`, `api_name`, `permission_name`) VALUES (1,'ignore_board_lock','Post In Locked Board'),(2,'delete_post','Delete Post'),(3,'edit_post','Edit Post'),(4,'manage_thread','Lock/Stick Thread'),(5,'admin_panel','Admin Panel Access'),(6,'moderate','Moderation Dropdown'),(7,'manage_permissions','Edit Permissions'),(8,'manage_pets','Edit Pet Species/Colors'),(9,'manage_users','User Manager'),(10,'manage_boards','Manage Boards'),(11,'manage_shops','Manage Shops'),(12,'manage_items','Manage Items'),(13,'forum_access:staff','Forum: Staff Board');
UNLOCK TABLES;
/*!40000 ALTER TABLE `staff_permission` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

