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
-- Table structure for table `unit`
--
DROP TABLE IF EXISTS `unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(100) NOT NULL,
  `unit_type` bigint(20) DEFAULT NULL,
  `parent_unit` bigint(20) DEFAULT NULL,
  `unit_shortname` varchar(20) DEFAULT NULL,
  `staff_limit` bigint(20) NOT NULL,
  `max_patients` bigint(20) NOT NULL,
  `number_of_beds` bigint(20) DEFAULT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `color_unit` varchar(20) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `address` text,
  `phone_number` varchar(100) DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_unit_1_idx` (`unit_type`),
  CONSTRAINT `fk_unit_1` FOREIGN KEY (`unit_type`) REFERENCES `unit_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit`
--

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` VALUES (1,'Agency Staff',1,0,'AS',0,0,0,'Agency staff',1,'#e6dcdc',NULL,'fgh','4654','2019-10-09 10:44:56',1),(2,'St Matthews Unit',1,0,'SMU',100,100,58,'St. Matthew’s Unit',1,'#f8cd07',NULL,'29-31 St Matthews Parade Kingsley Northampton NN2 7HF','+44 (0)1604 711 222','2019-10-09 10:45:29',1),(3,'St Matthews Hospital',1,0,'SMH',100,100,16,'St Matthews Hospital',1,'#12f627',NULL,'21-23 St Matthew’s Parade Kingsley Northampton NN2 7HF','+44 (0)1604 723 530','2019-10-09 10:45:20',1),(4,'The Avenue',1,0,'TA',100,100,32,'The Avenue',1,'#e61983',NULL,'The Avenue Spinney Hill Northampton NN3 6BA','+44 (0)1604 644 455','2019-10-09 10:45:42',1),(5,'The Dallingtons',1,0,'TD',100,100,40,'The Dallingtons',1,'rgb(15, 41, 238)',NULL,'The Dallingtons 116 Harlestone Road Northampton NN5 6AB\r\n','01604 581 181,  01604 754 654','2019-10-09 10:46:07',1),(6,'Kingsthorpe Grange',1,0,'KG',100,100,51,'Kingsthorpe Grange',1,'rgb(223, 38, 38)',NULL,'296 Harborough Road Kingsthorpe Northampton NN2 8DT','+44 (0)1604 821 000','2019-10-09 11:21:27',1),(7,'The Broomhill',1,0,'BHM',0,0,99,'The Broomhill',1,'#e66110',NULL,'Holdenby Road Spratton Northampton, Northants. NN6 8LD','+44 (0)1604 844 192','2019-10-09 10:45:54',1);
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-09 15:30:43
