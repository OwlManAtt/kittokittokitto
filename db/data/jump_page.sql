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
-- Dumping data for table `jump_page`
--


/*!40000 ALTER TABLE `jump_page` DISABLE KEYS */;
LOCK TABLES `jump_page` WRITE;
INSERT INTO `jump_page` (`jump_page_id`, `page_title`, `page_html_title`, `layout_type`, `page_slug`, `access_level`, `restricted_permission_api_name`, `php_script`, `include_tinymce`, `active`) VALUES (1,'Home','Home','deep','home','public','','meta/home.php','N','Y'),(2,'Register','Register','deep','register','public','','user/register.php','N','Y'),(3,'Logoff','Logoff','deep','logoff','user','','user/logout.php','N','Y'),(4,'Login','Login','deep','login','public','','user/login.php','N','Y'),(14,'Profile','Profile','deep','profile','user','','user/profile.php','N','Y'),(22,'Pets','Pets','deep','pets','user','','pets/manage.php','N','Y'),(23,'Pets - Create','Pets - Create','deep','create-pet','user','','pets/create.php','N','Y'),(24,'Pets - Abandon','Pets - Abandon','deep','abandon-pet','user','','pets/abandon.php','N','Y'),(25,'Items','Items','deep','items','user','','items/list.php','N','Y'),(26,'Item - Details','Item - Details','deep','item','user','','items/detail.php','N','Y'),(27,'Shops','Shops','deep','shops','user','','shops/list.php','N','Y'),(28,'Shops - View Stock','Shops - View Stock','deep','shop','user','','shops/shop.php','N','Y'),(29,'Notices','Notices','deep','notice','user','','user/notices.php','N','Y'),(30,'Game Corner','Game Corner','deep','games','user','','games/list.php','N','Y'),(31,'Magic Trick','Magic Trick','deep','magic-game','user','','games/magic_game.php','N','Y'),(32,'Boards','Boards','deep','boards','user','','boards/board_list.php','N','Y'),(33,'Boards','Boards','deep','threads','user','','boards/thread_list.php','N','Y'),(34,'Boards','Boards','deep','thread','user','','boards/post_list.php','Y','Y'),(35,'Boards - Reply','Boards - Reply','deep','thread-reply','user','','boards/reply.php','N','Y'),(36,'Create Thread','Create Thread','deep','new-thread','user','','boards/create_thread.php','Y','Y'),(37,'Forum Moderation','Forum Moderation','deep','forum-admin','restricted','moderate','boards/moderation.php','N','Y'),(38,'Edit Post','Edit Post','deep','edit-post','user','','boards/edit_post.php','Y','Y'),(39,'Edit Thread','Edit Thread','deep','edit-thread','user','','boards/edit_thread.php','N','Y'),(40,'Preferences','Preferences','deep','preferences','user','','user/preferences.php','Y','Y'),(41,'Pet Profile','Pet Profile','deep','pet','user','','pets/profile.php','N','Y'),(42,'Edit Pet Profile','Edit Pet Profile','deep','edit-pet','user','','pets/edit.php','Y','Y'),(43,'News','News','deep','news','public','','news/list.php','N','Y'),(44,'Messages','Messages','deep','messages','user','','messages/list.php','N','Y'),(45,'Compose Message','Compose Message','deep','write-message','user','','messages/write.php','Y','Y'),(46,'Send Message','Send Message','deep','send-message','user','','messages/send.php','N','Y'),(47,'Read Message','Read Message','deep','message','user','','messages/view.php','N','Y'),(48,'Admin Overview','Admin Overview','deep','admin','restricted','admin_panel','admin/links.php','N','Y'),(49,'Permission Editor','Permission Editor','deep','admin-permissions','restricted','manage_permissions','admin/permissions/home.php','N','Y'),(50,'Edit Pet Colors','Edit Pet Colors','deep','admin-pet-colors','restricted','manage_pets','admin/pets/colors/home.php','N','Y'),(51,'Edit Pet Species','Edit Pet Species','deep','admin-pet-species','restricted','manage_pets','admin/pets/species/home.php','N','Y'),(52,'User Admin','User Admin','deep','admin-users','restricted','manage_users','admin/user/home.php','Y','Y'),(53,'Board Admin','Board Admin','deep','admin-boards','restricted','manage_boards','admin/boards/home.php','N','Y'),(54,'Shop Admin','Shop Admin','deep','admin-shops','restricted','manage_shops','admin/shops/home.php','N','Y'),(55,'Item Admin','Item Admin','deep','admin-items','restricted','manage_items','admin/items/home.php','N','Y'),(56,'Permission Editor','Permission Editor','deep','admin-permissions-edit','restricted','manage_permissions','admin/permissions/edit.php','Y','Y'),(57,'Board Creator','Board Creator','deep','admin-boards-create','restricted','manage_boards','admin/boards/create.php','N','N'),(58,'Edit Color','Edit Color','deep','admin-pet-colors-edit','restricted','manage_pets','admin/pets/colors/edit.php','N','Y'),(59,'Edit Specie','Edit Specie','deep','admin-pet-species-edit','restricted','manage_pets','admin/pets/species/edit.php','Y','Y'),(60,'Pet to Color Mapping','Pet to Color Mapping','deep','admin-pet-specie-colors','restricted','manage_pets','admin/pets/species/color_mapping.php','N','Y'),(61,'Board Editor','Board Editor','deep','admin-boards-edit','restricted','manage_boards','admin/boards/edit.php','Y','Y'),(62,'Edit Shop','Edit Shop','deep','admin-shops-edit','restricted','manage_shops','admin/shops/edit.php','Y','Y'),(63,'Edit Item','Edit Item','deep','admin-items-edit','restricted','manage_items','admin/items/edit.php','Y','Y'),(64,'New Item','New Item','deep','admin-items-add','restricted','manage_items','admin/items/new.php','N','Y'),(65,'Edit Restocks','Edit Restocks','deep','admin-restock','restricted','manage_items','admin/items/restock/home.php','N','Y'),(66,'Edit Restocks','Edit Restocks','deep','admin-restock-edit','restricted','manage_items','admin/items/restock/edit.php','N','Y'),(67,'Staff','Staff','deep','staff','public','','meta/staff.php','N','Y');
UNLOCK TABLES;
/*!40000 ALTER TABLE `jump_page` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

