-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: rotacloud
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.18.04.1

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
-- Table structure for table `master_shift`
--
DROP TABLE IF EXISTS `master_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_shift` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(200) NOT NULL,
  `shift_shortcut` varchar(20) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `shift_category` tinyint(4) DEFAULT NULL,
  `part_number` tinyint(4) DEFAULT NULL,
  `targeted_hours` varchar(10) DEFAULT NULL,
  `unpaid_break_hours` varchar(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `hours` varchar(10) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updation_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_shift`
--

LOCK TABLES `master_shift` WRITE;
/*!40000 ALTER TABLE `master_shift` DISABLE KEYS */;
INSERT INTO `master_shift` VALUES (0,'Offday','X','12:00','11:59',NULL,0,'0','0',1,'0','2019-08-02 14:01:19',NULL,NULL),(1,'Holiday','H','00:00:00','00:00:00',NULL,0,'0','0',1,'0','2019-09-18 18:24:49',NULL,NULL),(2,'Training','T','00:00:00','00:00:00',NULL,0,'0','0',1,'0','2019-08-02 14:01:19',NULL,NULL),(3,'Sick','S','09:00:00','09:00:00',0,0,'0','0',0,'0','2019-07-31 01:14:06',NULL,NULL),(4,'Absent Without Leave','AWOL','09:00:00','09:00:00',0,0,'0','0',0,'0','2019-07-31 01:14:06',NULL,NULL),(5,'8-4','8-4','08:00:00','16:00:00',1,0,'07:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(6,'6-1.30','6-1.30','06:00:00','13:00:00',1,0,'06:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(7,'6-6','6-6','06:00:00','18:00:00',1,0,'11:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(8,'7.30-3.30','7.30-3.30','07:30:00','15:30:00',1,0,'07:15','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(9,'8-5','8-5','08:00:00','17:00:00',1,0,'08:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(10,'9-4.30','9-4.30','09:00:00','16:30:00',1,0,'07:00','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(11,'9-5','9-5','09:00:00','17:00:00',1,0,'07:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(12,'6-1.30','6-1.30','06:00:00','13:00:00',1,0,'06:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(13,'Early','E','07:15:00','13:45:00',1,0,'06:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(14,'Late','L','13:15:00','19:45:00',1,0,'06:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(15,'LongDay','EL','07:15:00','19:45:00',1,0,'11:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(16,'Night','N','19:30:00','07:30:00',2,0,'12:00','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(17,'OliverStreet','O12','07:15:00','19:45:00',1,0,'11:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(18,'9-2.30','9-2.30','09:00:00','14:00:00',1,0,'05:00','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(19,'10-4','10-4','10:00:00','16:00:00',1,0,'05:30','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(20,'7.30-6','7.30-6','07:30:00','18:00:00',1,0,'10:00','0',1,'0','2019-07-31 01:14:06',NULL,NULL),(21,'Open Shift','OPEN','00:00:00','00:00:00',0,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `master_shift` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-09 15:29:59
