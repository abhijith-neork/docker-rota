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
-- Table structure for table `master_designation`
--

DROP TABLE IF EXISTS `master_designation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_designation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `designation_name` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `description` text,
  `part_number` tinyint(4) DEFAULT NULL,
  `designation_code` varchar(5) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_designation`
--
LOCK TABLES `master_designation` WRITE;
/*!40000 ALTER TABLE `master_designation` DISABLE KEYS */;
INSERT INTO `master_designation` VALUES (1,'Accounts Admin',1,'Accounts Admin',0,'ACAD','2019-07-01 15:52:17','2019-09-04 18:17:41',1),(2,'Activity Co-Ordinator',1,'Activity Co-Ordinator',0,'ACCO','2019-07-01 15:52:28','2019-10-01 10:45:49',1),(3,'Adaptation Nurse',1,'Adaptation Nurse',0,'AP','2019-07-01 15:52:28','2019-09-04 18:18:52',1),(4,'Administrator',1,'Administrator',0,'ADM','2019-07-01 15:52:40','2019-10-08 15:06:19',1),(5,'Cook',1,'Cook',0,'COOK','2019-07-01 15:52:40','2019-07-01 15:52:40',1),(6,'Deputy Manager ',1,'Deputy Manager',0,'DMGR','2019-10-09 10:58:21','2019-07-01 15:52:40',1),(7,'Director',1,'Director',0,'DIR','2019-10-09 10:58:46','2019-07-01 15:52:40',1),(8,'Domestic',1,'Domestic',0,'DOM','2019-10-09 10:59:01','2019-07-01 15:52:40',1),(9,'Driver',1,'Driver',0,'DRI','2019-10-09 10:59:27','2019-07-01 15:52:40',1),(10,'Health and Social Care Worker',1,'Health and Social Care Worker',0,'HSCW','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(11,'Senior Health and Social Care Worker',1,'Senior Health and Social Care Worker',0,'SHSCW','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(12,'Maintenance',1,'Maintenance',0,'MAIN','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(13,'Manager',1,'Manager',0,'MAN','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(14,'Registered General Nurse',1,'Registered General Nurse',0,'RGN','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(15,'Registered MentalHealth Nurse',1,'Registered MentalHealth Nurse',0,'RMN','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(16,'Registered Learning Disability Nurse',1,'Registered Learning Disability Nurse',0,'RNLD','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(17,'Occupational Therapist',1,'Occupational Therapist',0,'OT','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(18,'Occupational Therapy Techinical Instructor',1,'Occupational Therapy Techinical Instructor',0,'OTTI','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(19,'Supervised Practice Nurse',1,'Supervised Practice Nurse',0,'SPN','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(20,'HR Generalist',1,'HR Generalist',0,'HRGT','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(21,'HR Lead',1,'HR Lead',0,'HRL','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(22,'Recruitment and Resource Partner',1,'Recruitment and Resource Partner',0,'RARP','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(23,'Training and Development Officer',1,'Training and Development Officer',0,'TDO','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(24,'Mental Health Admin',1,'Mental Health Admin',0,'MHA','2019-10-09 10:59:27','2019-10-09 10:59:27',1),(25,'Mental Health Lead',1,'Mental Health Lead',0,'MHL','2019-10-09 10:59:27','2019-10-09 10:59:27',1);
/*!40000 ALTER TABLE `master_designation` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-09 15:31:14
