-- MySQL dump 10.11
--
-- Host: localhost    Database: kitto
-- ------------------------------------------------------
-- Server version	5.0.51a-3ubuntu5.1

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
-- Dumping data for table `item_type`
--

LOCK TABLES `item_type` WRITE;
/*!40000 ALTER TABLE `item_type` DISABLE KEYS */;
INSERT INTO `item_type` (`item_type_id`, `item_name`, `item_descr`, `item_class_id`, `happiness_bonus`, `hunger_bonus`, `pet_specie_color_id`, `item_image`, `item_recipe_type_id`, `recipe_created_item_type_id`, `recipe_batch_quantity`, `unique_item`, `transferable_item`) VALUES (1,'Red Apple','A delicious, healthy red apple.',1,0,3,0,'apple.png',0,0,0,'N','Y'),(5,'Rozen Paintbrush','<p>The Rozen paintbrush is delicious paint. You must use it~desu!</p>',3,0,0,3,'rozen.png',0,0,0,'N','Y'),(6,'Grubby Bowl','This is an old wooden bowl. Since there is nothing else, your pet will have to amuse itself with this.',2,1,0,0,'bowl.png',0,0,0,'N','Y'),(7,'Red Paintbrush','This will turn your pet red.',3,0,0,1,'red.png',0,0,0,'N','Y'),(8,'Blue Paintbrush','This will turn your pet blue.',3,0,0,1,'blue.png',0,0,0,'N','Y'),(9,'Apple Pie','A recipe for an apple pie.',4,0,0,0,'recipe_pie.png',1,10,1,'Y','N'),(10,'Apple Pie','A warm apple pie.',1,0,5,0,'apple_pie.png',0,0,0,'N','Y');
/*!40000 ALTER TABLE `item_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-07-13 21:59:00
