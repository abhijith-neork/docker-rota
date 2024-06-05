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
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `description` text,
  `activity_date` datetime DEFAULT NULL,
  `activity_by` bigint(20) DEFAULT NULL,
  `add_type` varchar(200) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `primary_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_activity_log_1_idk` (`user_id`),
  CONSTRAINT `fk_activity_log_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) NOT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `currency` varchar(15) DEFAULT NULL,
  `Address1` varchar(100) DEFAULT NULL,
  `Address2` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(45) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designation_rates`
--

DROP TABLE IF EXISTS `designation_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designation_rates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `designation_id` bigint(20) NOT NULL,
  `overtime_rate` decimal(5,2) DEFAULT NULL,
  `holiday_rate` decimal(5,2) DEFAULT NULL,
  `sickness_rate` decimal(5,2) DEFAULT NULL,
  `maternity-rate` decimal(5,2) DEFAULT NULL,
  `authorised_absence_rate` decimal(5,2) DEFAULT NULL,
  `unauthorised_absence_rate` decimal(5,2) DEFAULT NULL,
  `other_rates` decimal(5,2) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_designation_rates_1_idk` (`designation_id`),
  CONSTRAINT `fk_designation_rates_1` FOREIGN KEY (`designation_id`) REFERENCES `master_designation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designation_rates`
--

LOCK TABLES `designation_rates` WRITE;
/*!40000 ALTER TABLE `designation_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `designation_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_management`
--

DROP TABLE IF EXISTS `document_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_management` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_document_management_1_idk` (`user_id`),
  KEY `fk_document_management_2_idk` (`group_id`),
  CONSTRAINT `fk_document_management_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_document_management_2` FOREIGN KEY (`group_id`) REFERENCES `master_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_management`
--

LOCK TABLES `document_management` WRITE;
/*!40000 ALTER TABLE `document_management` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_management` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_permissions`
--

DROP TABLE IF EXISTS `group_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `permission_id` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_group_permissions_1_idx` (`group_id`),
  KEY `index3` (`permission_id`),
  CONSTRAINT `fk_group_permissions_1` FOREIGN KEY (`group_id`) REFERENCES `master_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_permissions`
--

LOCK TABLES `group_permissions` WRITE;
/*!40000 ALTER TABLE `group_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holliday`
--

DROP TABLE IF EXISTS `holliday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holliday` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `hollidaytype` bigint(20) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `comment` text,
  `status` tinyint(4) DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_holliday_1_idx` (`user_id`),
  KEY `fk_holliday_2` (`hollidaytype`),
  CONSTRAINT `fk_holliday_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_holliday_2` FOREIGN KEY (`hollidaytype`) REFERENCES `holliday_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holliday`
--

LOCK TABLES `holliday` WRITE;
/*!40000 ALTER TABLE `holliday` DISABLE KEYS */;
/*!40000 ALTER TABLE `holliday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holliday_type`
--

DROP TABLE IF EXISTS `holliday_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holliday_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `holliday_name` varchar(60) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holliday_type`
--

LOCK TABLES `holliday_type` WRITE;
/*!40000 ALTER TABLE `holliday_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `holliday_type` ENABLE KEYS */;
UNLOCK TABLES;

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
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_designation`
--

LOCK TABLES `master_designation` WRITE;
/*!40000 ALTER TABLE `master_designation` DISABLE KEYS */;
INSERT INTO `master_designation` VALUES (32,'Administrator',1,'c','2019-07-01 15:52:17','2019-07-02 15:09:19',0),(33,'Supervisor',1,'b','2019-07-01 15:52:28',NULL,NULL),(34,'Staff nurse',1,'a','2019-07-01 15:52:40','2019-07-03 09:36:43',0);
/*!40000 ALTER TABLE `master_designation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_group`
--

DROP TABLE IF EXISTS `master_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_group`
--

LOCK TABLES `master_group` WRITE;
/*!40000 ALTER TABLE `master_group` DISABLE KEYS */;
INSERT INTO `master_group` VALUES (1,'Employee',1,'2019-07-01 15:46:30',NULL,NULL),(2,'Manager',1,'2019-07-01 15:46:49',NULL,NULL),(3,'Administrator',1,'2019-07-01 15:47:05','2019-07-01 16:50:17','');
/*!40000 ALTER TABLE `master_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_payment_type`
--

DROP TABLE IF EXISTS `master_payment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_payment_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(45) DEFAULT NULL,
  `description` text,
  `status` tinyint(4) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_payment_type`
--

LOCK TABLES `master_payment_type` WRITE;
/*!40000 ALTER TABLE `master_payment_type` DISABLE KEYS */;
INSERT INTO `master_payment_type` VALUES (8,'Hourly','aaa',1,'2019-07-01 15:51:34','2019-07-02 16:24:14',0),(9,'Daily','bb',1,'2019-07-01 15:51:46',NULL,NULL),(10,'Weekly','cc',1,'2019-07-01 15:51:53',NULL,NULL),(11,'Monthly','dd',1,'2019-07-01 15:52:01',NULL,NULL);
/*!40000 ALTER TABLE `master_payment_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_shift`
--

DROP TABLE IF EXISTS `master_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_shift` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(200) NOT NULL,
  `start_time` time NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `end_time` time NOT NULL,
  `shift_shortcut` varchar(20) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updation_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_shift`
--

LOCK TABLES `master_shift` WRITE;
/*!40000 ALTER TABLE `master_shift` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_shift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_training`
--

DROP TABLE IF EXISTS `master_training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_training` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `place` varchar(45) NOT NULL,
  `address` varchar(100) NOT NULL,
  `unit` bigint(20) NOT NULL,
  `point_of_person` varchar(20) NOT NULL,
  `contact_num` bigint(20) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_training`
--

LOCK TABLES `master_training` WRITE;
/*!40000 ALTER TABLE `master_training` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_training` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `comment` text,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notes_1_idx` (`user_id`),
  CONSTRAINT `fk_notes_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_details`
--

DROP TABLE IF EXISTS `personal_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `fname` varchar(45) DEFAULT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `mobile_number` varchar(45) DEFAULT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address1` varchar(40) DEFAULT NULL,
  `address2` varchar(40) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `postcode` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_personal_details_1_idx` (`user_id`),
  CONSTRAINT `fk_personal_details_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_details`
--

LOCK TABLES `personal_details` WRITE;
/*!40000 ALTER TABLE `personal_details` DISABLE KEYS */;
INSERT INTO `personal_details` VALUES (1,2,'swaraj','sugunan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 15:53:13',NULL,NULL),(2,3,'f','fdg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 16:19:48',NULL,NULL),(3,4,'f','fdg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 16:20:33',NULL,NULL),(4,5,'f','fdg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 16:24:32',NULL,NULL),(5,6,'dfg','dfg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 16:25:20',NULL,NULL),(6,7,'swaraj','sugunan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 16:26:14',NULL,NULL),(7,8,'ert','ert',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 16:27:34',NULL,NULL),(8,9,'dfg','dfg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2019-07-01 16:38:44',NULL,NULL);
/*!40000 ALTER TABLE `personal_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rota`
--

DROP TABLE IF EXISTS `rota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rota` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) NOT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rota_2_idx` (`shift_id`),
  KEY `fk_rota_3_idx` (`unit_id`),
  KEY `fk_rota_1_idx` (`user_id`),
  CONSTRAINT `fk_rota_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rota_2` FOREIGN KEY (`shift_id`) REFERENCES `master_shift` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rota_3` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rota`
--

LOCK TABLES `rota` WRITE;
/*!40000 ALTER TABLE `rota` DISABLE KEYS */;
/*!40000 ALTER TABLE `rota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary`
--

DROP TABLE IF EXISTS `salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `payroll_id` varchar(100) DEFAULT NULL,
  `salary` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_salary_1_idx` (`user_id`),
  CONSTRAINT `fk_salary_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary`
--

LOCK TABLES `salary` WRITE;
/*!40000 ALTER TABLE `salary` DISABLE KEYS */;
/*!40000 ALTER TABLE `salary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `site_title` varchar(100) DEFAULT NULL,
  `site_description` text,
  `site_keywords` text,
  `admin_email` varchar(100) DEFAULT NULL,
  `pagination_limit` bigint(20) DEFAULT NULL,
  `from_email` varchar(100) DEFAULT NULL,
  `to_email` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `time_log`
--

DROP TABLE IF EXISTS `time_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_time_log_1` (`user_id`),
  KEY `fk_time_log_2` (`shift_id`),
  KEY `fk_time_log_3_idx` (`unit_id`),
  CONSTRAINT `fk_time_log_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_time_log_2` FOREIGN KEY (`shift_id`) REFERENCES `master_shift` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_time_log_3` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_log`
--

LOCK TABLES `time_log` WRITE;
/*!40000 ALTER TABLE `time_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `time_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_feedback`
--

DROP TABLE IF EXISTS `training_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_feedback` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `training_id` bigint(20) NOT NULL,
  `feedback` text NOT NULL,
  `user_rating` decimal(5,2) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_training_feedback_1_idx` (`user_id`),
  KEY `fk_training_feedback_2_idx` (`training_id`),
  CONSTRAINT `fk_training_feedback_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_training_feedback_2` FOREIGN KEY (`training_id`) REFERENCES `master_training` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_feedback`
--

LOCK TABLES `training_feedback` WRITE;
/*!40000 ALTER TABLE `training_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_staff`
--

DROP TABLE IF EXISTS `training_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_staff` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `training_id` bigint(20) DEFAULT NULL,
  `shift_id` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_training_staff_1_idx` (`user_id`),
  KEY `fk_training_staff_2_idx` (`training_id`),
  CONSTRAINT `fk_training_staff_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_training_staff_2` FOREIGN KEY (`training_id`) REFERENCES `master_training` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_staff`
--

LOCK TABLES `training_staff` WRITE;
/*!40000 ALTER TABLE `training_staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_staff` ENABLE KEYS */;
UNLOCK TABLES;

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
  `staff_limit` bigint(20) NOT NULL,
  `max_patients` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_unit_1_idx` (`unit_type`),
  CONSTRAINT `fk_unit_1` FOREIGN KEY (`unit_type`) REFERENCES `unit_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit`
--

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` VALUES (2,'ABC1',1,2,4,'df',1,'2019-07-01 15:50:13','2019-07-01 18:05:36',0),(4,'fgh',2,32,435,'dgdfg',1,'2019-07-02 13:54:04',NULL,NULL);
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit_type`
--

DROP TABLE IF EXISTS `unit_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_type`
--

LOCK TABLES `unit_type` WRITE;
/*!40000 ALTER TABLE `unit_type` DISABLE KEYS */;
INSERT INTO `unit_type` VALUES (1,'Hospital',1,'2019-07-01 15:48:30',NULL,NULL),(2,'HomeCare',1,'2019-07-01 15:48:30',NULL,NULL);
/*!40000 ALTER TABLE `unit_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_unit`
--

DROP TABLE IF EXISTS `user_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_unit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_unit_1_idx` (`user_id`),
  KEY `fk_user_unit_2_idx` (`unit_id`),
  CONSTRAINT `fk_user_unit_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_unit_2` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_unit`
--

LOCK TABLES `user_unit` WRITE;
/*!40000 ALTER TABLE `user_unit` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(120) NOT NULL,
  `password` char(100) NOT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `designation_id` bigint(20) DEFAULT NULL,
  `payment_type` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updation_userid` varchar(45) DEFAULT NULL,
  `reset_password` bigint(20) DEFAULT NULL,
  `key` varchar(45) DEFAULT NULL,
  `temp_email` varchar(120) DEFAULT NULL,
  `user_session` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_1_idx` (`payment_type`),
  KEY `fk_users_2_idx` (`group_id`),
  KEY `fk_users_3_idx` (`designation_id`),
  CONSTRAINT `fk_users_1` FOREIGN KEY (`payment_type`) REFERENCES `master_payment_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_2` FOREIGN KEY (`group_id`) REFERENCES `master_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_3` FOREIGN KEY (`designation_id`) REFERENCES `master_designation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'swaraj@123.com','',1,33,11,NULL,'2019-07-01 15:53:13',NULL,NULL,NULL,NULL,NULL,NULL),(3,'fgg@fgg.com','',NULL,32,8,1,'2019-07-01 16:19:48',NULL,NULL,NULL,NULL,NULL,NULL),(4,'fgg@fgg.com','',NULL,32,8,1,'2019-07-01 16:20:33',NULL,NULL,NULL,NULL,NULL,NULL),(5,'fgg@fgg.com','123',NULL,32,8,1,'2019-07-01 16:24:32',NULL,NULL,NULL,NULL,NULL,NULL),(6,'abc@123.com','dfg123',NULL,34,10,1,'2019-07-01 16:25:20',NULL,NULL,NULL,NULL,NULL,NULL),(7,'swaraj@123.com','swaraj@123',NULL,33,10,1,'2019-07-01 16:26:14',NULL,NULL,NULL,NULL,NULL,NULL),(8,'123@cgbg.com','ert@123',NULL,32,8,1,'2019-07-01 16:27:34',NULL,NULL,NULL,NULL,NULL,NULL),(9,'fg@123','dfg@123',NULL,32,8,1,'2019-07-01 16:38:44',NULL,NULL,NULL,NULL,NULL,NULL),(10,'fg@123','dfg@123',NULL,32,8,1,'2019-07-01 16:42:25',NULL,NULL,NULL,NULL,NULL,NULL),(11,'fd@123','dg@123',NULL,32,8,1,'2019-07-01 16:43:11',NULL,NULL,NULL,NULL,NULL,NULL),(12,'dfs@1312','dfsf@123',NULL,33,8,1,'2019-07-01 16:43:55',NULL,NULL,NULL,NULL,NULL,NULL),(13,'saasa@gmail.com','sdf@123',NULL,34,11,1,'2019-07-01 16:47:06',NULL,NULL,NULL,NULL,NULL,NULL),(14,'dfg@fdgh.com','dfg@123',NULL,33,9,1,'2019-07-02 09:39:20',NULL,NULL,NULL,NULL,NULL,NULL),(15,'swara@mma.com','df@123',NULL,32,8,1,'2019-07-02 14:33:06',NULL,NULL,NULL,NULL,NULL,NULL),(16,'sdf@df','d@123',NULL,32,9,1,'2019-07-02 14:36:47',NULL,NULL,NULL,NULL,NULL,NULL),(17,'sdf@df','d@123',NULL,32,9,1,'2019-07-02 14:36:51',NULL,NULL,NULL,NULL,NULL,NULL),(18,'sdf@df','d@123',NULL,32,9,1,'2019-07-02 14:36:54',NULL,NULL,NULL,NULL,NULL,NULL),(19,'sdf@df','d@123',NULL,32,9,1,'2019-07-02 14:37:54',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-07-03 10:01:45
