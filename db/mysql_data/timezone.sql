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
-- Dumping data for table `timezone`
--


/*!40000 ALTER TABLE `timezone` DISABLE KEYS */;
LOCK TABLES `timezone` WRITE;
INSERT INTO `timezone` (`timezone_id`, `timezone_short_name`, `timezone_long_name`, `timezone_continent`, `timezone_offset`, `order_by`) VALUES (1,'ACDT','Australian Central Daylight Time','Australia',10.5,1),(2,'ACST','Australian Central Standard Time','Australia',9.5,1),(3,'ADT','Atlantic Daylight Time','North America',-3.0,1),(4,'AEDT','Australian Eastern Daylight Time','Australia',11.0,1),(5,'AEST','Australian Eastern Standard Time','Australia',10.0,1),(6,'AKDT','Alaska Daylight Time','North America',-8.0,1),(7,'AKST','Alaska Standard Time','North America',-9.0,1),(8,'AST','Atlantic Standard Time','North America',-4.0,1),(9,'AWDT','Australian Western Daylight Time','Australia',9.0,1),(10,'AWST','Australian Western Standard Time','Australia',8.0,1),(11,'BST','British Summer Time','Europe',1.0,1),(12,'CDT','Central Daylight Time','North America',-5.0,1),(13,'CEDT','Central European Daylight Time','Europe',2.0,1),(14,'CEST','Central European Summer Time','Europe',2.0,1),(15,'CET','Central European Time','Europe',1.0,1),(16,'CST','Central Summer (Daylight) Time','Australia',10.5,1),(17,'CST','Central Standard Time','Australia',9.5,1),(18,'CST','Central Standard Time','North America',-6.0,1),(19,'CXT','Christmas Island Time','Australia',7.0,1),(20,'EDT','Eastern Daylight Time','North America',-4.0,1),(21,'EEDT','Eastern European Daylight Time','Europe',3.0,1),(22,'EEST','Eastern European Summer Time','Europe',3.0,1),(23,'EET','Eastern European Time','Europe',2.0,1),(24,'EST','Eastern Summer (Daylight) Time','Australia',11.0,1),(25,'EST','Eastern Standard Time','Australia',10.0,1),(26,'EST','Eastern Standard Time','North America',-5.0,1),(27,'GMT','Greenwich Mean Time','Europe',0.0,1),(28,'HAA','Heure Avancee de l\'Atlantique','North America',-3.0,1),(29,'HAC','Heure Avancee du Centre','North America',-5.0,1),(30,'HADT','Hawaii-Aleutian Daylight Time','North America',-9.0,1),(31,'HAE','Heure Avancee de l\'Est','North America',-4.0,1),(32,'HAP','Heure Avancee du Pacifique','North America',-7.0,1),(33,'HAR','Heure Avancee des Rocheuses','North America',-6.0,1),(34,'HAST','Hawaii-Aleutian Standard Time','North America',-10.0,1),(35,'HAT','Heure Avancee de Terre-Neuve','North America',-2.5,1),(36,'HAY','Heure Avancee du Yukon','North America',-8.0,1),(37,'HNA','Heure Normale de l\'Atlantique','North America',-4.0,1),(38,'HNC','Heure Normale du Centre','North America',-6.0,1),(39,'HNE','Heure Normale de l\'Est','North America',-5.0,1),(40,'HNP','Heure Normale du Pacifique','North America',-8.0,1),(41,'HNR','Heure Normale des Rocheuses','North America',-7.0,1),(42,'HNT','Heure Normale de Terre-Neuve','North America',-3.5,1),(43,'HNY','Heure Normale du Yukon','North America',-9.0,1),(44,'IST','Irish Summer Time','Europe',1.0,1),(45,'MDT','Mountain Daylight Time','North America',-6.0,1),(46,'MESZ','Mitteleuropaische Sommerzeit','Europe',2.0,1),(47,'MEZ','Mitteleuropaische Zeit','Europe',1.0,1),(48,'MST','Mountain Standard Time','North America',-7.0,1),(49,'NDT','Newfoundland Daylight Time','North America',-2.5,1),(50,'NFT','Norfolk (Island) Time','Australia',11.5,1),(51,'NST','Newfoundland Standard Time','North America',-3.5,1),(52,'PDT','Pacific Daylight Time','North America',-7.0,1),(53,'PST','Pacific Standard Time','North America',-8.0,1),(54,'UTC','Coordinated Universal Time','Europe',0.0,0),(55,'WEDT','Western European Daylight Time','Europe',1.0,1),(56,'WEST','Western European Summer Time','Europe',1.0,1),(57,'WET','Western European Time','Europe',0.0,1),(58,'WST','Western Summer (Daylight) Time','Australia',9.0,1),(59,'WST','Western Standard Time','Australia',8.0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `timezone` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

