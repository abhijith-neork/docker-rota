-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: 172.17.0.2    Database: erota
-- ------------------------------------------------------
-- Server version	5.7.38

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Ethnicity`
--

DROP TABLE IF EXISTS `Ethnicity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Ethnicity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Ethnic_group` varchar(150) DEFAULT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `other_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ethnicity`
--

LOCK TABLES `Ethnicity` WRITE;
/*!40000 ALTER TABLE `Ethnicity` DISABLE KEYS */;
INSERT INTO `Ethnicity` VALUES (1,'White',0,1,0),(2,'English/Welsh/Scottish/Northern Irish/British',1,1,0),(3,'Irish',1,1,0),(4,'Gypsy or Irish Traveller',1,1,0),(5,'Any other White background',1,1,1),(6,'Mixed/multiple ethnic groups',0,1,0),(7,'White and Black Caribbean',6,1,0),(8,'White and Black African',6,1,0),(9,'White and Asian',6,1,0),(10,'Any other mixed/multiple ethnic background',6,1,1),(11,'Asian/Asian British',0,1,0),(12,'Indian',11,1,0),(13,'Pakistani',11,1,0),(14,'Bangladeshi',11,1,0),(15,'Chinese',11,1,0),(16,'Any other Asian background',11,1,1),(17,'Black/African/Caribbean/Black British',0,1,0),(18,'African',17,1,0),(19,'Caribbean',17,1,0),(20,'Any other Black/African/Caribbean background',17,1,1),(21,'Other ethnic group',0,1,0),(22,'Arab',21,1,0),(23,'Any other ethnic group',21,1,1);
/*!40000 ALTER TABLE `Ethnicity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `History_post_data`
--

DROP TABLE IF EXISTS `History_post_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `History_post_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `description` text,
  `type` varchar(50) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `History_post_data`
--

LOCK TABLES `History_post_data` WRITE;
/*!40000 ALTER TABLE `History_post_data` DISABLE KEYS */;
INSERT INTO `History_post_data` VALUES (1,'Vaikom,2,0,VK,BIZCOSPACES 8th Floor, Infra Futura Building Seaport Airport Road, Kakkanad, opp. Bharat Matha College, Kochi, 682021, India,8977777777,2,2,2,2023-05-17 13:37:19,Test,1,#000000','Add Unit',1,'2023-05-17 13:37:19'),(2,'Kochi,1,0,KI,Test,2323232323,2,2,2,2023-05-17 14:26:18,2,1,#000000','Add Unit',1,'2023-05-17 14:26:18'),(3,'Chinchu,Gopi,chinchu@neork.com,2,14,9','Add User',1,'2023-05-17 14:26:44'),(4,'Chinchu,Gopi,chinchu+1@neork.com,2,14,9','Add User',1,'2023-05-17 14:27:18'),(5,'Chinchu,Gopi,chinchugopi89@gmail.com,2,14,9','Add User',1,'2023-05-17 15:56:37'),(6,'Chinchu,Gopi,anoopoxford@gmail.com,2,14,9','Add User',1,'2023-05-17 16:09:26'),(7,'Chinchu,Gopi,anoopoxfordg@gmail.com,2,14,9','Add User',1,'2023-05-17 16:14:04'),(8,'karthika,K,karthika@neork.com,2,14,9','Add User',1,'2023-05-17 16:15:29'),(9,'Chinchu,Gopi,chinchugopi8998@gmail.com,2,14,9','Add User',1,'2023-05-18 10:39:32'),(10,'CHinchu,gopi,chinchua@neork.com,2,14,9','Add User',1,'2023-05-18 11:56:37'),(11,'chincu,v,chinchuas@neork.com,2,14,9','Add User',1,'2023-05-18 12:00:17'),(12,'chinchu,chinchu,chinchufg@neork.com,2,14,9','Add User',1,'2023-05-18 12:03:32'),(13,'chinchu,chinchu,chinchuneork@gmail.com,2,14,9','Add User',1,'2023-05-18 12:07:22'),(14,'12,chinchu,chinchu,chinchuneork@gmail.com,5656565656,,Thekkumkalayil house, Kaippattoor po, Thottoor,Thekkumkalayil house,United Kingdom,,454545,chinchu,5656565656,Thekkumkalayil house, Kaippattoor po, Thottoor,F,12,0','Personal Details',1,'2023-05-18 12:56:30'),(15,'12,3,08:00,1, ,222.00,2,222.00,NaN,2,14,18/05/2023,,123,148,9,4.00,1,,','Employee Details',1,'2023-05-18 12:57:43'),(16,'12,1,0,0,0,0,0,1','Workschedule',1,'2023-05-18 12:57:55'),(17,'user_id :11','Delete User',1,'2023-05-18 12:58:13'),(18,'user_id :10','Delete User',1,'2023-05-18 12:58:17'),(19,'user_id :9','Delete User',1,'2023-05-18 12:58:20'),(20,'user_id :8','Delete User',1,'2023-05-18 12:58:28'),(21,'user_id :7','Delete User',1,'2023-05-18 12:58:31'),(22,'user_id :6','Delete User',1,'2023-05-18 12:58:33'),(23,'user_id :5','Delete User',1,'2023-05-18 12:58:35'),(24,'user_id :4','Delete User',1,'2023-05-18 12:58:37'),(25,'user_id :3','Delete User',1,'2023-05-18 12:58:39'),(26,'user_id :2','Delete User',1,'2023-05-18 12:58:41'),(27,'Agency,2,0,A,Test,2323232323,2,2,2,2023-05-18 14:03:29,2,1,#000000','Add Unit',1,'2023-05-18 14:03:29'),(28,'Kochi,1,0,KC,Test,2323232323,2,2,2,2023-05-18 14:04:14,2,1,#b46161','Add Unit',1,'2023-05-18 14:04:14'),(29,'Test,Test,3,0,2023-05-18 14:13:02,2023-05-18 14:13:02,1,2','Add Notes',1,'2023-05-18 14:13:02'),(30,'2,12,18/05/2023,18/05/2023,08:00,222.00,test,222.00,NaN,without_offday_holiday : 2023-05-18','Add Leave - admin side',1,'2023-05-18 14:17:46'),(31,'2,12,20/05/2023,20/05/2023,08:00,222.00,Test,222.00,NaN,without_offday_holiday : ','Add Leave - admin side',1,'2023-05-18 15:46:48'),(32,'2,12,18/05/2023,18/05/2023,08:00,214.00,test,222.00,NaN,without_offday_holiday : 2023-05-18','Add Leave - admin side',1,'2023-05-18 16:08:40'),(33,'2,12,18/05/2023,18/05/2023,08:00,214.00,6,222.00,NaN,without_offday_holiday : ','Add Leave - admin side',1,'2023-05-18 16:11:48'),(34,'12,chinchu,chinchu,chinchuneork@gmail.com,5656565656,30/11/-0001,Thekkumkalayil house, Kaippattoor po, Thottoor,Thekkumkalayil house,United Kingdom,,454545,chinchu,5656565656,Thekkumkalayil house, Kaippattoor po, Thottoor,F,12,0','Personal Details',1,'2023-05-18 16:13:08'),(35,'12,3,08:00,1, ,222.00,2,222.00,NaN,2,14,18/05/2023,,123,7,9,4.00,1,,','Employee Details',1,'2023-05-18 16:13:12'),(36,'Security,ndling ,2023-05-18,2023-05-18,01:00,01:00,10:00,Kochi,Test,2,1,2323232323,chinchu+1@neork.com,0,2023-05-18 16:23:07,1','Add Training',1,'2023-05-18 16:23:07'),(37,'2023-05-26,2023-05-18 16:23:30,1','Add Bankholiday',1,'2023-05-18 16:23:30'),(38,'Test','Add Job Role Group',1,'2023-05-18 16:36:24');
/*!40000 ALTER TABLE `History_post_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Unit_Designation_history`
--

DROP TABLE IF EXISTS `User_Unit_Designation_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User_Unit_Designation_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` bigint(20) DEFAULT NULL,
  `Previous_unit` bigint(20) DEFAULT NULL,
  `Current_unit` bigint(20) DEFAULT NULL,
  `Previous_designation` bigint(20) DEFAULT NULL,
  `Current_designation` bigint(20) DEFAULT NULL,
  `Updation_date` datetime DEFAULT NULL,
  `Updated_by` bigint(20) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_User_Unit_Designation_history_1_idx` (`User_id`),
  CONSTRAINT `fk_User_Unit_Designation_history_1` FOREIGN KEY (`User_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Unit_Designation_history`
--

LOCK TABLES `User_Unit_Designation_history` WRITE;
/*!40000 ALTER TABLE `User_Unit_Designation_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `User_Unit_Designation_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_thumb`
--

DROP TABLE IF EXISTS `User_thumb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User_thumb` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `thumbnail` text,
  `thumbnail1` text,
  `thumbnail2` text,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `entrolled_from` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_thumb`
--

LOCK TABLES `User_thumb` WRITE;
/*!40000 ALTER TABLE `User_thumb` DISABLE KEYS */;
/*!40000 ALTER TABLE `User_thumb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `description` text,
  `activity_date` datetime DEFAULT NULL,
  `activity_by` bigint(20) DEFAULT NULL,
  `add_type` varchar(200) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `primary_id` bigint(20) DEFAULT NULL,
  `creation_date` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'Super Admin has created a rota for Kochi for the week 2023-05-21 to 2023-05-27 with settings: day shift minimum:1, day shift maximum:2, night shift maximum:1, night shift maximum:2, number of patients:1, 1:1 patients:1, nurse_day_count:1, nurse_night_count:1','2023-05-18 16:14:41',1,'Create New Rota',0,NULL,1,'2023-05-18 16:14:41');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `additional_hours`
--

DROP TABLE IF EXISTS `additional_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `additional_hours` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `additional_hours` varchar(45) DEFAULT NULL,
  `day_additional_hours` varchar(45) DEFAULT NULL,
  `night_additional_hours` varchar(45) DEFAULT NULL,
  `additinal_hour_timelog_id` varchar(45) DEFAULT NULL,
  `comment` text,
  `creation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `additional_hours`
--

LOCK TABLES `additional_hours` WRITE;
/*!40000 ALTER TABLE `additional_hours` DISABLE KEYS */;
/*!40000 ALTER TABLE `additional_hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agency_staffs`
--

DROP TABLE IF EXISTS `agency_staffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agency_staffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `agency_staffid` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) NOT NULL,
  `created_date` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agency_staffs`
--

LOCK TABLES `agency_staffs` WRITE;
/*!40000 ALTER TABLE `agency_staffs` DISABLE KEYS */;
/*!40000 ALTER TABLE `agency_staffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `available_master_shift`
--

DROP TABLE IF EXISTS `available_master_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `available_master_shift` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(200) NOT NULL,
  `shift_shortcut` varchar(20) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `shift_category` tinyint(4) DEFAULT NULL,
  `part_number` tinyint(4) DEFAULT NULL,
  `targeted_hours` varchar(10) DEFAULT NULL,
  `unpaid_break_hours` varchar(10) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `parent_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1112 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `available_master_shift`
--

LOCK TABLES `available_master_shift` WRITE;
/*!40000 ALTER TABLE `available_master_shift` DISABLE KEYS */;
INSERT INTO `available_master_shift` VALUES (169,'Available 9.15-14.45','AVL-9.15-2.45','09:15','14:45',1,1,'5:30','00:00','2020-06-12 13:30:22',106),(170,'Available 8.30-15.30PN','AVL-8.3-3.3PN','08:30','15:30',1,1,'7:00','00:00','2020-06-17 13:29:30',107),(171,'Available Kitchen Long Day','AVL-KIT','07:15','19:45',1,1,'12:30','01:00','2020-07-13 15:48:50',108),(1000,'Available 8-4','AVL-8-4','08:00','16:00',1,1,'8:00','00:30','2019-07-31 01:14:06',5),(1001,'Available 6-1.30','AVL-6-1.30','06:00:00','13:00:00',1,1,'06:30','0','2019-07-31 01:14:06',6),(1002,'Available 6-6','AVL-6-6','06:00','18:00',1,1,'12:00','01:00','2019-07-31 01:14:06',7),(1003,'Available 7.30-3.30','AVL-7.30-3.30','07:30','15:30',1,1,'07:15','00:00','2019-07-31 01:14:06',8),(1004,'Available 8-5','AVL-8-5','08:00','17:00',1,1,'9:00','00:30','2019-07-31 01:14:06',9),(1005,'Available 9-4.30','AVL-9-4.30','09:00','16:30',1,1,'7:30','00:00','2019-07-31 01:14:06',10),(1006,'Available 9-5','AVL-9-5','09:00','17:00',1,1,'8:00','00:30','2019-07-31 01:14:06',11),(1007,'Available 6-1.30','AVL-6-1.30','06:00','13:30',1,1,'7:30','00:00','2019-07-31 01:14:06',12),(1008,'Available Early','AVL-E','07:15','13:45',3,1,'06:30','00:00','2019-07-31 01:14:06',13),(1009,'Available Late','AVL-L','13:15','19:45',1,1,'6:30','00:00','2019-07-31 01:14:06',14),(1010,'Available LongDay','AVL-EL','07:15','19:45',1,1,'12:30','01:00','2019-07-31 01:14:06',15),(1011,'Available Night','AVL-N','19:30','07:30',2,1,'12:00','00:00','2019-07-31 01:14:06',16),(1012,'Available OliverStreet','AVL-O12','07:15','19:45',1,1,'12:30','01:00','2019-07-31 01:14:06',17),(1013,'Available 9-2.30','AVL-9-2.30','09:00','14:30',1,1,'5:30','00:00','2019-07-31 01:14:06',18),(1014,'Available 10-4','AVL-10-4','10:00','16:00',1,1,'6:00','00:00','2019-07-31 01:14:06',19),(1015,'Available 7.30-6','AVL-7.30-6','07:30','18:00',1,1,'10:30','00:45','2019-07-31 01:14:06',20),(1016,'Available 7.30-12.30','AVL-7.30-12.30','07:30','12:30',1,0,'5:00','00:00','2019-10-30 21:34:45',46),(1017,'Available 7.30-4','AVL-7.30-4','07:30','16:00',1,0,'8:30','01:00','2019-10-30 21:35:45',47),(1018,'Available 12-8','AVL-12-8','12:00','20:00',1,0,'8:00','00:30','2019-10-30 21:36:05',48),(1019,'Available 7-1.30','AVL-7-1.30','07:00','13:30',1,0,'6:30','00:00','2019-10-30 21:36:21',49),(1020,'Available 7-3','AVL-7-3','07:00','15:00',1,0,'8:00','00:30','2019-10-30 21:36:37',50),(1021,'Available 8.30-4.15','AVL-8.30-4.15','08:30','16:15',1,0,'7:45','00:30','2019-10-30 21:37:07',51),(1022,'Available 8.30-4.30','AVL-8.30-4.30','08:30','16:30',1,0,'8:00','00:30','2019-10-30 21:37:27',52),(1023,'Available 8-6','AVL-8-6','08:00','18:00',1,0,'10:00','01:00','2019-10-30 21:37:40',53),(1024,'Available 9-4.15','AVL-9-4.15','09:00','16:15',1,0,'7:15','00:00','2019-10-30 21:38:06',54),(1025,'Available 9-4','AVL-9-4','09:00','16:00',1,1,'7:00','00:30','2019-11-06 10:45:02',55),(1026,'Available 9-2','AVL-9-2','09:00','14:00',1,0,'5:00','00:00','2019-11-25 12:45:04',56),(1027,'Available 9-3','AVL-9-3','09:00','15:00',1,0,'6:00','00:00','2019-11-25 12:45:52',57),(1028,'Available 8.30-6.30','AVL-8.30-6.30','08:30','18:30',1,1,'10:00','00:30','2020-01-15 09:00:40',58),(1029,'Available 730-14.30','AVL-7.30-14.30','07:30','14:30',1,1,'7:00','00:00','2020-01-16 14:38:28',59),(1030,'Available 9.15-7.15','AVL-9.15-7.15','09:15','19:15',1,1,'10:00','00:30','2020-01-17 09:00:25',60),(1031,'Available 9-5 Part of Number','AVL-9-5PN','09:00','17:00',1,1,'8:00','00:30','2020-02-06 13:24:43',65),(1032,'Available 8-6 Part of Number','AVL-8-6PN','08:00','18:00',1,1,'10:00','00:45','2020-02-06 13:28:24',66),(1033,'Available LongDay Kitchen','AVL-ELK','07:15','19:45',1,1,'12:30','01:00','2020-02-20 10:18:10',67),(1034,'Available 7.00 - 1.30','AVL-7-1.30','07:00','13:30',1,1,'6:30','00:00','2020-02-28 18:48:11',70),(1035,'Available Early Kitchen','AVL-EKL','07:15','19:45',4,1,'12:30','01:00','2020-02-28 18:49:26',71),(1036,'Available Late Kitchen','AVL-LK','13:15','19:45',4,1,'6:30','00:00','2020-02-28 18:50:44',72),(1037,'Available Laundry','AVL-LAU','07:30','16:00',1,1,'8:30','01:00','2020-02-28 18:52:42',73),(1038,'Available Early Kitchen','AVL-EK','07:15','13:45',3,1,'6:30','00:00','2020-02-28 18:54:21',74),(1039,'Available Early - 5','AVL-E5','07:15','17:00',1,1,'9:45','00:45','2020-02-28 18:54:57',75),(1040,'Available Training + Night','AVL-TN','19:30','07:30',2,1,'12:00','00:00','2020-02-28 18:55:39',76),(1041,'Available Training + LongDay','AVL-ELT','07:15','19:45',1,1,'12:30','01:00','2020-02-28 18:56:34',77),(1042,'Available 8.00-4.30','AVL-8-4.30','08:00','16:30',1,1,'8:30','00:45','2020-02-28 19:35:22',78),(1043,'Available Early 1','AVL-E1','07:15','14:30',3,1,'7:15','00:00','2020-02-28 19:36:02',79),(1044,'Available 8.30 - 1','AVL-8.30-1','08:30','13:00',1,1,'4:30','00:00','2020-03-05 15:48:35',80),(1045,'Available 8.30 - 5.30','AVL-8.30-5.30','08:30','17:30',1,1,'9:00','00:30','2020-03-05 15:49:02',81),(1046,'Available 8-4 Training','AVL-8-4T','08:00','16:00',1,1,'8:00','00:45','2020-03-09 17:24:58',82),(1047,'Available 8.30 - 4.15 Training','AVL-8.30-4.15T','08:30','16:15',1,1,'7:45','00:30','2020-03-09 17:25:36',83),(1048,'Available 7 - 1','AVL-7-1','07:00','13:00',1,1,'6:00','00:00','2020-03-10 14:48:42',84),(1049,'Available 9-4 (7 hours shoft)','AVL-9-4 (7)','09:00','16:00',1,1,'7:00','00:00','2020-03-21 12:38:54',85),(1050,'Available Kitchen Long Day','AVL-KIT','07:15','19:45',1,1,'12:30','01:00','2020-03-21 15:54:19',86),(1051,'Available 8-L','AVL-8-L','08:00','19:45',4,1,'11:45','01:00','2020-03-24 09:53:52',87),(1052,'Available 7.15-18.00','AVL-E6','07:15','18:00',3,1,'10:45','00:30','2020-03-24 15:29:07',88),(1053,'Available 8-4','AVL-8.4','08:00','16:00',1,1,'8:00','0.48','2020-04-24 09:09:14',89),(1054,'Available 9.30-4.30(A)','AVL-9.30-4.30(A)','09:30','16:30',1,1,'7:00','00:00','2020-04-24 11:40:32',90),(1055,'Available 4-8.30','AVL-4-8.3','16:00','20:30',4,1,'4:30','00:00','2020-05-03 11:19:51',91),(1056,'Available 9.30-17.00','AVL-9.3-5','09:30','17:00',1,1,'7:30','00:30','2020-05-13 09:30:57',92),(1057,'Available Early + Training','AVL-ET','07:15','13:45',3,1,'6:30','00:00','2020-05-27 09:58:39',94),(1058,'Available Late + Training','AVL-LT','13:15','19:45',3,1,'6:30','00:00','2020-05-27 09:59:18',95),(1059,'Available 7.15-15.15','AVL-7.15-3.15','07:15','15:15',1,1,'8:00','00:48','2020-05-29 15:11:30',96),(1060,'Available E-6J','AVL-E-6J','07:15','18:00',1,1,'10:45','00:45','2020-05-29 15:14:23',97),(1061,'Available 7.30-17.00','AVL-7.30-5','07:30','17:00',1,1,'9:30','00:30','2020-05-29 15:15:58',98),(1062,'Available 9-6','AVL-9-6','09:00','18:00',1,1,'9:00','00:30','2020-05-29 15:26:53',99),(1063,'Available 7-5','AVL-7.5','07:00','17:00',1,1,'10:00','00:30','2020-05-29 15:39:51',100),(1064,'Available 7-2','AVL-7.2','07:00','14:00',1,1,'7:00','00:30','2020-05-29 15:43:49',101),(1065,'Available 8-4 Part of Number','AVL-8-4PN','08:00','16:00',1,1,'8:00','00:30','2020-06-04 09:31:46',102),(1066,'Available 9-4.30 Part of Number','AVL-9-4.30PN','09:00','16:30',1,1,'7:30','00:30','2020-06-06 09:04:33',103),(1067,'Available 8-5 Part of Number','AVL-8-5PN','08:00','17:00',1,1,'9:00','00:30','2020-06-06 09:05:43',104),(1068,'Available 8.30-19.45 (not PN)','AVL-8.30-7.45','08:30','19:45',1,1,'11:15','00:45','2020-06-11 13:46:53',105),(1069,'Available Student Nurse LongDay','AVL-SN-EL','07:15','19:45',1,1,'12:30','1','2020-08-17 11:43:29',109),(1070,'Available Student Nurse Early','AVL-SN-E','07:15','13:45',1,1,'6:30','00:00','2020-08-17 11:46:16',110),(1071,'Available Student Nurse Late','AVL-SN-L','13:15','19:45',4,1,'6:30','00:00','2020-08-17 11:49:10',111),(1072,'Available Student Nurse Night','AVL-SN-N','19:30','07:30',2,1,'12:00','00:00','2020-08-17 11:50:25',112),(1073,'Available Cancelled Shift','AVL-CS','01:00','02:00',1,1,'1:00','01:00','2020-10-27 15:36:18',113),(1074,'Available Early Kitchen - Part of Numbers ','AVL-EK-PN','07:15','13:45',3,1,'6:30','00:00','2020-11-06 18:39:34',114),(1075,'Available Late Kitchen Part of numbers ','AVL-LK-PN','13:15','19:45',4,1,'6:30','00:00','2020-11-06 18:59:21',115),(1076,'Available 6-3','AVL-6.3','06:00','15:00',1,1,'9:00','00:30','2021-05-09 14:15:45',116),(1077,'Available 9-7','AVL-9.7','09:00','19:00',1,1,'10:00','00:30','2021-05-10 12:40:37',117),(1078,'Available 10:00-18:00','AVL-10:00-18:00','10:00','18:00',1,1,'8:00','00:30','2022-05-13 16:31:43',118),(1079,'Available 7:30-18:00','AVL-7:30-18:00','07:30','18:00',1,1,'10:30','00:30','2022-05-13 16:34:10',119),(1080,'Available 8-18','AVL-8-18','08:00','18:00',1,1,'10:00','00:30','2022-06-28 13:33:21',120),(1081,'Available 9.00 - 2.00','AVL-9.2','09:00','14:00',1,1,'5:00','00:00','2022-06-28 17:00:24',121),(1082,'Available 09.00 - 15.00','AVL-9.3','09:00','15:00',1,1,'6:00','00::00','2022-06-28 17:01:11',122),(1083,'Available 10.00 - 15.00','AVL-10.3','10:00','15:00',1,1,'5:00','00:00','2022-06-28 17:01:54',123),(1084,'Available 07.00 - 15.00 ','AVL-7.3','07:00','15:00',1,1,'8:00','00:30','2022-06-28 17:02:25',124),(1085,'Available 8 - 2','AVL-8-2','08:00','14:00',1,1,'6:00','00:00','2022-06-29 14:03:11',125),(1086,'Available 08:30 - 20:30','AVL-8.3-20.3','08:30','20:30',1,1,'12:00','00:30','2022-08-15 09:55:57',126),(1087,'Available Domestic- E','AVL-E- Dom','07:15','13:15',3,1,'6:00','00:00','2022-08-31 13:29:18',127),(1088,'Available Long Day Induction','AVL-EL-IND','07:15','19:45',1,1,'12:30','00.30','2022-09-05 14:09:13',128),(1089,'Available Night Induction','AVL-N-IND','19:30','07:30',2,1,'12:00','00:00','2022-09-05 14:10:17',129),(1090,'Available Early Induction','AVL-E-IND','07:15','15:15',3,1,'8:00','00:00','2022-09-05 14:12:16',130),(1091,'Available Late Induction','AVL-L-IND','14:00','19:45',4,1,'5:45','00:15','2022-09-05 14:16:57',131),(1092,'Available 04:30-15:30','AVL-4.3-3.3','04:30','15:30',1,1,'11:00','01:00','2022-09-06 08:52:40',132),(1093,'Available 04:15-15:15','AVL-4.15-3.15','04:15','15:15',1,1,'11:00','01:00','2022-09-06 08:57:10',133),(1094,'Available 07:30-14:00','AVL-7.3-2','07:30','14:00',1,1,'6:30','00:30','2022-09-12 09:01:51',134),(1095,'Available 6-12','AVL-6.12','06:00','12:00',1,1,'6:00','00:15','2022-09-12 09:06:18',135),(1096,'Available 6-14','AVL-6.14','06:00','14:00',1,1,'8:00','00:30','2022-09-12 09:07:09',136),(1097,'Available 08:15-20:15','AVL-8.15-8.15','08:15','20:15',1,1,'12:00','00:30','2022-09-22 12:51:18',137),(1098,'Available 11:00-19:00','AVL-11-07','11:00','19:00',1,1,'8:00','00:30','2022-09-26 15:48:18',138),(1099,'Available 11.15-19.45','AVL-11.15-19.45','11:15','19:45',1,1,'8:30','00:30','2022-09-30 12:43:37',139),(1100,'Available 8-4.30','AVL-8-4.3','08:00','16:30',1,1,'8:30','00:30','2022-09-30 12:53:14',140),(1101,'Available 7.15-15.45','AVL-7.15-15.45','07:15','15:45',1,1,'8:30','00:30','2022-09-30 13:07:49',141),(1102,'Available ACTIVITY COORDINATOR','AVL-ac','09:30','17:30',1,1,'8:00','00:30','2023-02-14 14:40:39',142),(1103,'Available 9-12.30','AVL-9-12.30','09:00','12:30',1,1,'3:30','00:00','2023-03-24 09:55:53',143),(1104,'Available 8-4 IND','AVL-8-4IND','08:00','16:00',1,1,'8:00','00:30','2023-03-24 09:58:36',144),(1105,'Available 9-5 IND','AVL-9-5 IND','09:00','17:00',1,1,'8:00','00:30','2023-04-04 10:30:19',145),(1106,'Available 8.15-14.30','AVL-8.15-14.30','08:15','14:30',1,1,'6:15','00:15','2023-04-21 14:57:29',146),(1107,'Available 13.30-20.00','AVL-13.30-20.00','13:30','20:00',4,1,'6:30','15:00','2023-04-21 14:58:08',147),(1108,'Available 08.00-20.15','AVL-08.00-20.15','08:00','20:15',1,1,'12:15','01:00','2023-04-21 14:58:53',148),(1109,'Available 14.00-20.15','AVL-14.00-20.15','14:00','20:15',4,1,'6:15','00:15','2023-04-21 15:00:04',149),(1110,'Available 9.30-15.00','AVL-9.30-15.00','09:30','15:00',1,1,'5:30','00:15','2023-05-04 09:59:47',150),(1111,'Available 7-5','AVL-7-5','07:00','17:00',1,1,'10:00','00.30','2023-05-05 15:49:55',151);
/*!40000 ALTER TABLE `available_master_shift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `available_requested_users`
--

DROP TABLE IF EXISTS `available_requested_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `available_requested_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avialable_request_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `available_requested_users`
--

LOCK TABLES `available_requested_users` WRITE;
/*!40000 ALTER TABLE `available_requested_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `available_requested_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `available_requests`
--

DROP TABLE IF EXISTS `available_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `available_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_id` bigint(20) NOT NULL,
  `to_unitid` bigint(20) NOT NULL,
  `from_unitid` bigint(20) DEFAULT NULL,
  `date` date NOT NULL,
  `created_date` varchar(45) NOT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  `comments` text,
  `request_count` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `available_requests`
--

LOCK TABLES `available_requests` WRITE;
/*!40000 ALTER TABLE `available_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `available_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_holiday`
--

DROP TABLE IF EXISTS `bank_holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank_holiday` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `created_date` datetime NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_holiday`
--

LOCK TABLES `bank_holiday` WRITE;
/*!40000 ALTER TABLE `bank_holiday` DISABLE KEYS */;
INSERT INTO `bank_holiday` VALUES (1,'2023-05-26','2023-05-18 16:23:30',1);
/*!40000 ALTER TABLE `bank_holiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `shift_gap` varchar(100) DEFAULT NULL,
  `from_email` varchar(100) DEFAULT NULL,
  `late_notify` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (1,'Neork Technologies','Health Care','BST','01604 844 192','29-31 St Matthews Parade','Kingsley','Northampton','uk','NN2 7HF','2020-04-22 11:22:06','2020-04-22 11:22:06',NULL,'9','chinchu@neork.com',15);
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_senses`
--

DROP TABLE IF EXISTS `daily_senses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_senses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `comment` text,
  `creation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_senses`
--

LOCK TABLES `daily_senses` WRITE;
/*!40000 ALTER TABLE `daily_senses` DISABLE KEYS */;
/*!40000 ALTER TABLE `daily_senses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designation_rates`
--

DROP TABLE IF EXISTS `designation_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `designation_rates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `designation_id` bigint(20) NOT NULL,
  `normal_rates` bigint(20) DEFAULT NULL,
  `overtime_rate` decimal(5,2) DEFAULT NULL,
  `holiday_rate` decimal(5,2) DEFAULT NULL,
  `sickness_rate` decimal(5,2) DEFAULT NULL,
  `maternity_rate` decimal(5,2) DEFAULT NULL,
  `authorised_absence_rate` decimal(5,2) DEFAULT NULL,
  `unauthorised_absence_rate` decimal(5,2) DEFAULT NULL,
  `other_rates` decimal(5,2) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_designation_rates_1_idk` (`designation_id`),
  CONSTRAINT `fk_designation_rates_1` FOREIGN KEY (`designation_id`) REFERENCES `master_designation` (`id`)
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
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(200) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_management`
--

DROP TABLE IF EXISTS `document_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  KEY `fk_document_management_1_idk` (`user_id`)
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
-- Table structure for table `email_log`
--

DROP TABLE IF EXISTS `email_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email_to` varchar(100) DEFAULT NULL,
  `email_settings` text,
  `email_body` text,
  `type` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `sendgridresponse` text,
  `created_at` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_log`
--

LOCK TABLES `email_log` WRITE;
/*!40000 ALTER TABLE `email_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_permissions`
--

DROP TABLE IF EXISTS `employee_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_permissions`
--

LOCK TABLES `employee_permissions` WRITE;
/*!40000 ALTER TABLE `employee_permissions` DISABLE KEYS */;
INSERT INTO `employee_permissions` VALUES (1,'fname','First name',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(2,'lname','Last name',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(3,'email','Email',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(4,'mobile_number','Phone Number',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(5,'dob','Date of Birth',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(6,'gender','Gender',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(7,'address1','Address 1',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(8,'address2','Address 2',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(9,'city','City',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(10,'country','Country',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(11,'Ethnicity','Ethnicity',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(12,'visa_status','Visa Status',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(13,'kin_name','Kin Name',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(14,'kin_phone','Kin Phone number',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(15,'kin_address','Kin Address',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(16,'group_id','Select Group',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(17,'weekly_hours','Weekly hours',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(18,'annual_holliday_allowance','Annual Leave Allowance',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(19,'designation_id','Select job role',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(20,'actual_holiday_allowance','Annual Leave Entitlement',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(21,'default_shift','Select default shift',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(22,'start_date','Start date',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(23,'hr_ID','HR ID',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(24,'payroll_id','Payroll ID',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(25,'payment_type','Select paymenttype',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(26,'notes','Notes',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(27,'exit_interview','Need exit interview',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(28,'exit_reason','Reason for leaving',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(29,'status','Status',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(30,'postcode','Postcode',0,'2023-03-27 11:09:08','2023-03-27 11:09:08'),(31,'change_schduler','Unit Change Scheduler',0,'2023-03-27 11:09:08','2023-03-27 11:09:08');
/*!40000 ALTER TABLE `employee_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_permissions`
--

DROP TABLE IF EXISTS `group_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `permission_id` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_group_permissions_1_idx` (`group_id`),
  KEY `index3` (`permission_id`),
  CONSTRAINT `fk_group_permissions_1` FOREIGN KEY (`group_id`) REFERENCES `master_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=483 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_permissions`
--

LOCK TABLES `group_permissions` WRITE;
/*!40000 ALTER TABLE `group_permissions` DISABLE KEYS */;
INSERT INTO `group_permissions` VALUES (1,1,1,1),(2,1,2,1),(3,1,3,1),(4,1,4,1),(5,1,5,1),(6,1,6,1),(7,1,7,1),(8,1,8,1),(9,1,9,1),(10,1,10,1),(11,1,11,1),(12,1,12,1),(13,1,13,1),(14,1,14,1),(15,1,15,1),(16,1,16,1),(17,1,17,1),(18,1,18,1),(19,1,19,1),(20,1,20,1),(21,1,21,1),(22,1,22,1),(23,1,23,1),(24,1,24,1),(25,1,25,1),(26,1,26,1),(27,1,27,1),(28,1,28,1),(29,1,29,1),(30,1,30,1),(31,1,31,1),(32,1,32,1),(33,1,33,1),(34,1,34,1),(35,1,35,1),(36,1,36,1),(37,1,37,1),(38,1,38,1),(39,1,39,1),(40,1,40,1),(41,1,41,1),(42,1,42,1),(43,1,43,1),(44,1,44,1),(45,1,45,1),(46,1,46,1),(47,1,47,1),(48,1,48,1),(49,1,49,1),(50,1,50,1),(51,1,51,1),(52,1,52,1),(53,1,53,1),(54,1,54,1),(55,1,55,1),(56,1,56,1),(57,1,57,1),(58,1,58,1),(59,1,59,1),(60,1,60,1),(61,1,61,1),(62,1,62,1),(63,1,63,1),(64,1,64,1),(65,1,65,1),(66,1,66,1),(67,1,67,1),(68,1,68,1),(69,1,69,1),(70,1,70,1),(71,1,71,1),(72,3,1,1),(73,3,2,1),(77,6,8,1),(80,2,6,1),(81,6,1,1),(82,2,71,1),(84,2,68,1),(85,2,67,1),(86,2,66,1),(87,2,65,1),(92,5,2,1),(97,6,11,1),(98,6,12,1),(99,3,11,1),(100,5,11,1),(101,5,12,1),(102,6,25,1),(103,6,26,1),(104,6,27,1),(105,6,28,1),(106,3,25,1),(107,3,26,1),(108,3,27,1),(109,3,28,1),(110,5,25,1),(111,5,26,1),(112,5,27,1),(114,6,29,1),(117,3,29,1),(118,3,31,1),(119,3,32,1),(120,6,6,1),(121,3,6,1),(122,5,6,1),(123,3,3,1),(124,3,4,1),(126,5,4,1),(127,5,3,1),(128,5,1,1),(129,6,65,1),(130,3,65,1),(132,6,33,1),(133,6,34,1),(134,6,35,1),(135,6,37,1),(136,3,34,1),(137,5,34,1),(138,3,35,1),(139,5,35,1),(140,3,37,1),(141,5,37,1),(142,6,66,1),(143,6,67,1),(144,3,66,1),(145,3,67,1),(146,5,66,1),(147,5,67,1),(148,6,38,1),(149,6,39,1),(150,6,40,1),(151,6,41,1),(152,3,41,1),(153,3,40,1),(154,3,39,1),(155,3,38,1),(156,6,68,1),(157,3,68,1),(158,5,68,1),(159,6,50,1),(160,3,50,1),(161,5,50,1),(162,6,51,1),(163,6,52,1),(164,6,53,1),(165,6,54,1),(166,6,55,1),(167,6,56,1),(168,6,57,1),(169,6,58,1),(170,6,59,1),(171,6,60,1),(172,6,61,1),(173,6,62,1),(174,6,63,1),(175,6,71,1),(176,3,55,1),(178,3,56,1),(180,3,53,1),(181,5,53,1),(182,3,57,1),(183,3,58,1),(184,3,59,1),(185,5,59,1),(186,3,60,1),(187,3,61,1),(188,5,61,1),(189,3,62,1),(190,5,62,1),(191,3,71,1),(192,5,71,1),(193,1,72,1),(197,6,31,1),(198,6,32,1),(200,6,72,1),(207,5,65,1),(208,3,12,1),(211,6,30,1),(213,9,6,1),(214,9,1,1),(216,10,1,1),(217,10,2,1),(218,10,3,1),(219,10,4,1),(220,10,6,1),(226,10,35,1),(227,10,37,1),(228,10,66,1),(229,10,67,1),(230,10,68,1),(231,10,71,1),(232,11,6,1),(233,11,65,1),(234,11,66,1),(235,11,67,1),(236,11,68,1),(237,11,71,1),(238,11,38,1),(239,11,39,1),(240,11,40,1),(241,11,41,1),(242,10,65,1),(244,3,54,1),(245,1,73,1),(246,1,74,1),(247,1,75,1),(248,1,76,1),(249,1,77,1),(250,5,54,1),(253,9,2,1),(254,9,3,1),(255,9,4,1),(256,9,11,1),(257,9,12,1),(258,9,25,1),(259,9,26,1),(260,9,27,1),(261,9,34,1),(262,9,35,1),(263,9,37,1),(264,9,50,1),(265,9,53,1),(266,9,54,1),(267,9,59,1),(268,9,61,1),(269,9,62,1),(270,9,65,1),(271,9,66,1),(272,9,67,1),(273,9,68,1),(274,9,71,1),(277,12,2,1),(278,12,3,1),(279,12,4,1),(280,12,5,1),(281,12,6,1),(282,12,7,1),(283,12,8,1),(284,12,9,1),(285,12,10,1),(286,12,11,1),(287,12,12,1),(300,12,25,1),(301,12,26,1),(302,12,27,1),(303,12,28,1),(304,12,29,1),(305,12,30,1),(306,12,31,1),(307,12,32,1),(309,12,34,1),(310,12,35,1),(311,12,37,1),(312,12,38,1),(313,12,39,1),(314,12,40,1),(315,12,41,1),(322,12,77,1),(323,12,76,1),(324,12,75,1),(325,12,74,1),(326,12,73,1),(327,12,72,1),(328,12,71,1),(329,12,70,1),(330,12,69,1),(331,12,68,1),(332,12,67,1),(333,12,66,1),(334,12,65,1),(336,12,63,1),(337,12,62,1),(338,12,61,1),(339,12,60,1),(340,12,59,1),(341,12,58,1),(342,12,57,1),(343,12,56,1),(344,12,55,1),(345,12,54,1),(346,12,53,1),(347,12,52,1),(348,12,51,1),(349,12,50,1),(352,9,77,1),(353,1,78,1),(354,9,78,1),(355,6,78,1),(356,3,78,1),(357,10,78,1),(358,1,79,1),(359,1,80,1),(360,1,81,1),(361,1,82,1),(362,1,83,1),(363,1,84,1),(364,1,85,1),(365,1,86,1),(366,1,87,1),(367,1,88,1),(368,1,89,1),(369,1,90,1),(370,1,91,1),(371,1,92,1),(372,1,93,1),(373,18,1,1),(374,18,2,1),(375,18,6,1),(377,18,25,1),(378,18,35,1),(379,18,38,1),(380,18,63,1),(381,18,50,1),(382,18,61,1),(383,18,62,1),(384,18,65,1),(385,18,66,1),(386,18,77,1),(387,18,89,1),(388,18,79,1),(390,18,87,1),(391,18,88,1),(392,18,91,1),(393,18,92,1),(394,18,93,1),(395,18,80,1),(396,18,84,1),(397,18,85,1),(398,18,81,1),(399,18,82,1),(400,18,83,1),(401,18,51,1),(402,18,60,1),(403,18,56,1),(404,18,55,1),(405,18,54,1),(406,18,53,1),(407,18,52,1),(408,18,58,1),(409,18,59,1),(410,18,57,1),(411,18,67,1),(412,18,68,1),(413,19,1,1),(414,19,2,1),(415,19,3,1),(416,19,6,1),(417,19,25,1),(418,19,26,1),(419,19,27,1),(420,19,29,1),(421,19,30,1),(422,19,31,1),(423,19,32,1),(424,19,34,1),(425,19,35,1),(426,19,37,1),(427,19,50,1),(428,19,53,1),(429,19,54,1),(430,19,61,1),(431,19,62,1),(432,19,76,1),(433,19,77,1),(434,19,85,1),(435,19,88,1),(436,19,89,1),(437,20,1,1),(438,20,2,1),(439,20,3,1),(440,20,33,1),(441,20,34,1),(442,20,35,1),(443,20,37,1),(444,20,38,1),(445,20,39,1),(446,20,40,1),(447,20,41,1),(448,20,50,1),(449,20,53,1),(450,20,54,1),(451,20,55,1),(452,20,59,1),(453,20,61,1),(454,20,62,1),(455,20,63,1),(456,20,72,1),(457,20,78,1),(458,20,77,1),(459,20,82,1),(460,20,81,1),(461,20,91,1),(462,20,92,1),(463,20,93,1),(464,20,6,1),(465,6,7,1),(466,6,9,1),(467,6,10,1),(468,6,90,1),(470,6,2,1),(471,6,3,1),(472,6,4,1),(473,6,5,1),(474,18,4,1),(475,18,3,1),(476,3,5,1),(477,10,5,1),(478,5,5,1),(479,18,5,1),(480,20,4,1),(481,20,5,1),(482,6,85,1);
/*!40000 ALTER TABLE `group_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_rota_schedule`
--

DROP TABLE IF EXISTS `history_rota_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_rota_schedule` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) DEFAULT '0',
  `shift_hours` varchar(45) DEFAULT NULL,
  `additional_hours` varchar(45) DEFAULT NULL,
  `comment` text,
  `from_unit` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) NOT NULL,
  `rota_id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) NOT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) NOT NULL,
  `day` varchar(45) DEFAULT NULL,
  `shift_category` tinyint(4) DEFAULT NULL,
  `from_userid` bigint(20) DEFAULT NULL,
  `from_rotaid` bigint(20) DEFAULT NULL,
  `rota_logid` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_rota_schedule`
--

LOCK TABLES `history_rota_schedule` WRITE;
/*!40000 ALTER TABLE `history_rota_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_rota_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_staff_address`
--

DROP TABLE IF EXISTS `history_staff_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_staff_address` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `address` text,
  `kin_name` varchar(100) DEFAULT NULL,
  `kin_address` text,
  `kin_phonenumber` varchar(45) DEFAULT NULL,
  `changed_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_history_staff_address_1_idx` (`user_id`),
  CONSTRAINT `fk_history_staff_address_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_staff_address`
--

LOCK TABLES `history_staff_address` WRITE;
/*!40000 ALTER TABLE `history_staff_address` DISABLE KEYS */;
INSERT INTO `history_staff_address` VALUES (1,12,'Thekkumkalayil house, Kaippattoor po, Thottoor,,United Kingdom,454545','chinchu','Thekkumkalayil house, Kaippattoor po, Thottoor','5656565656','2023-05-18 12:56:30');
/*!40000 ALTER TABLE `history_staff_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_user_rates`
--

DROP TABLE IF EXISTS `history_user_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_user_rates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `day_rate` decimal(5,2) DEFAULT NULL,
  `night_rate` decimal(5,2) DEFAULT NULL,
  `day_saturday_rate` decimal(5,2) DEFAULT NULL,
  `day_sunday_rate` decimal(5,2) DEFAULT NULL,
  `weekend_night_rate` decimal(5,2) DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_user_rates`
--

LOCK TABLES `history_user_rates` WRITE;
/*!40000 ALTER TABLE `history_user_rates` DISABLE KEYS */;
INSERT INTO `history_user_rates` VALUES (1,12,4.00,NULL,NULL,NULL,NULL,'2023-05-18 12:57:43',1);
/*!40000 ALTER TABLE `history_user_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holiday_applied`
--

DROP TABLE IF EXISTS `holiday_applied`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holiday_applied` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `year` varchar(100) NOT NULL,
  `holiday_id` bigint(20) NOT NULL,
  `hours` varchar(100) DEFAULT NULL,
  `calculated_hours` varchar(100) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_holiday_applied_1_idx` (`user_id`),
  KEY `fk_holiday_applied_2_idx` (`holiday_id`),
  CONSTRAINT `fk_holiday_applied_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_holiday_applied_2` FOREIGN KEY (`holiday_id`) REFERENCES `holliday` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holiday_applied`
--

LOCK TABLES `holiday_applied` WRITE;
/*!40000 ALTER TABLE `holiday_applied` DISABLE KEYS */;
INSERT INTO `holiday_applied` VALUES (1,12,'2022-2023',2,'08:00','0','2023-05-18 15:46:48'),(2,12,'2022-2023',5,'08:00','0','2023-05-18 16:11:48');
/*!40000 ALTER TABLE `holiday_applied` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holiday_comments`
--

DROP TABLE IF EXISTS `holiday_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holiday_comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `holiday_id` bigint(20) NOT NULL,
  `manager_id` bigint(20) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holiday_comments`
--

LOCK TABLES `holiday_comments` WRITE;
/*!40000 ALTER TABLE `holiday_comments` DISABLE KEYS */;
INSERT INTO `holiday_comments` VALUES (1,1,1,'Approved','2023-05-18 14:17:46',1),(2,2,1,'Approved','2023-05-18 15:46:48',1),(3,3,1,'Approved','2023-05-18 16:08:40',1),(4,5,1,'Approved','2023-05-18 16:11:48',1);
/*!40000 ALTER TABLE `holiday_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holiday_days`
--

DROP TABLE IF EXISTS `holiday_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holiday_days` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `holiday_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hour` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holiday_days`
--

LOCK TABLES `holiday_days` WRITE;
/*!40000 ALTER TABLE `holiday_days` DISABLE KEYS */;
INSERT INTO `holiday_days` VALUES (1,1,0,'2023-05-18',''),(2,3,0,'2023-05-18','');
/*!40000 ALTER TABLE `holiday_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holliday`
--

DROP TABLE IF EXISTS `holliday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holliday` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  `hollidaytype` bigint(20) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `start_time` varchar(20) DEFAULT NULL,
  `end_time` varchar(20) DEFAULT NULL,
  `days` varchar(20) DEFAULT NULL,
  `remaining_leave` varchar(20) DEFAULT NULL,
  `leave_remaining` varchar(20) DEFAULT NULL,
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
  CONSTRAINT `fk_holliday_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_holliday_2` FOREIGN KEY (`hollidaytype`) REFERENCES `holliday_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holliday`
--

LOCK TABLES `holliday` WRITE;
/*!40000 ALTER TABLE `holliday` DISABLE KEYS */;
INSERT INTO `holliday` VALUES (2,12,2,NULL,'2023-05-20',':',':','08:00','222.00',NULL,'2023-05-20','Test',1,'1','2023-05-18 15:46:48','2023-05-18 15:46:48','2023-05-18 15:46:48',1),(5,12,2,NULL,'2023-05-18',':',':','08:00','214.00',NULL,'2023-05-18','6',1,'1','2023-05-18 16:11:48','2023-05-18 16:11:48','2023-05-18 16:11:48',1);
/*!40000 ALTER TABLE `holliday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holliday_type`
--

DROP TABLE IF EXISTS `holliday_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `jobrole_group`
--

DROP TABLE IF EXISTS `jobrole_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobrole_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(45) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobrole_group`
--

LOCK TABLES `jobrole_group` WRITE;
/*!40000 ALTER TABLE `jobrole_group` DISABLE KEYS */;
INSERT INTO `jobrole_group` VALUES (1,'Test','2023-05-18 16:36:24','1');
/*!40000 ALTER TABLE `jobrole_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_log`
--

DROP TABLE IF EXISTS `leave_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leave_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shift_id` bigint(20) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `created_date` datetime NOT NULL,
  `shift_name` varchar(45) NOT NULL,
  `leave_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_log`
--

LOCK TABLES `leave_log` WRITE;
/*!40000 ALTER TABLE `leave_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_log`
--

DROP TABLE IF EXISTS `login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `IPaddress` varchar(500) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30458 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_log`
--

LOCK TABLES `login_log` WRITE;
/*!40000 ALTER TABLE `login_log` DISABLE KEYS */;
INSERT INTO `login_log` VALUES (30455,1,'127.0.0.1','2023-05-15 14:23:19'),(30456,1,'127.0.0.1','2023-05-17 12:57:43'),(30457,1,'127.0.0.1','2023-05-18 09:43:56');
/*!40000 ALTER TABLE `login_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_designation`
--

DROP TABLE IF EXISTS `master_designation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_designation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `designation_name` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `description` text,
  `part_number` tinyint(4) DEFAULT NULL,
  `availability_requests` bigint(20) DEFAULT NULL,
  `group` bigint(20) DEFAULT NULL,
  `jobrole_groupid` bigint(20) DEFAULT NULL,
  `designation_code` varchar(5) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  `sort_order` tinyint(4) DEFAULT NULL,
  `nurse_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_designation`
--

LOCK TABLES `master_designation` WRITE;
/*!40000 ALTER TABLE `master_designation` DISABLE KEYS */;
INSERT INTO `master_designation` VALUES (1,'Accounts Admin',1,'Accounts Admin',1,2,1,1,'ACAD','2019-07-01 15:52:17','2021-05-29 18:19:39',1,16,0),(2,'Activity Co-Ordinator',1,'Activity Co-Ordinator',1,2,1,4,'ACCO','2019-07-01 15:52:28','2021-05-29 18:19:48',1,18,0),(3,'Adaptation Nurse',3,'Adaptation Nurse',1,4,3,3,'AP','2019-07-01 15:52:28','2021-05-29 18:19:53',1,18,1),(4,'Administrator',1,'Administrator',1,2,1,1,'ADM','2019-07-01 15:52:40','2021-05-29 18:19:57',1,16,0),(5,'Cook',1,'Cook',1,2,7,7,'COOK','2019-07-01 15:52:40','2021-05-29 18:20:15',1,18,0),(6,'Deputy Manager ',1,'Deputy Manager',0,2,3,2,'DMGR','2019-10-09 10:58:21','2019-07-01 15:52:40',1,2,1),(7,'Director',3,'Director',1,2,2,2,'DIR','2019-10-09 10:58:46','2021-05-29 18:20:32',1,18,0),(8,'Domestic',1,'Domestic',1,2,7,6,'DOM','2019-10-09 10:59:01','2021-05-29 18:20:43',1,17,0),(9,'Driver',3,'Driver',1,2,7,4,'DRI','2019-10-09 10:59:27','2021-05-29 18:20:49',1,18,0),(10,'Health and Social Care Worker - Days',1,'Health and Social Care Worker - Days',1,2,6,5,'HSCWD','2019-10-09 10:59:27','2021-05-29 18:20:58',1,11,0),(11,'Senior Health and Social Care Worker  - Days',1,'Senior Health and Social Care Worker - Days',1,2,6,5,'SHCWD','2019-10-09 10:59:27','2021-05-29 18:22:40',1,10,0),(12,'Maintenance',1,'Maintenance',1,2,6,4,'MAIN','2019-10-09 10:59:27','2021-05-29 18:21:24',1,19,0),(13,'Manager',1,'Manager',0,2,2,2,'MAN','2019-10-09 10:59:27','2019-10-09 10:59:27',1,1,0),(14,'Registered General Nurse',1,'Registered General Nurse',1,4,3,3,'RGN','2019-10-09 10:59:27','2020-04-25 12:44:33',1,4,1),(15,'Registered Mental Health Nurse',1,'Registered MentalHealth Nurse',1,4,3,3,'RMN','2019-10-09 10:59:27','2020-04-25 12:44:53',1,3,1),(16,'Registered Learning Disability Nurse',1,'Registered Learning Disability Nurse',1,4,3,3,'RNLD','2019-10-09 10:59:27','2020-04-25 12:44:43',1,5,1),(17,'Occupational Therapist',1,'Occupational Therapist',1,2,3,4,'OT','2019-10-09 10:59:27','2021-05-29 18:21:39',1,14,0),(18,'Occupational Therapy Technical Instructor',1,'Occupational Therapy Technical Instructor',1,2,3,4,'OTTI','2019-10-09 10:59:27','2021-05-29 18:21:51',1,15,0),(19,'Supervised Practice Nurse',3,'Supervised Practice Nurse',0,2,3,3,'SPN','2019-10-09 10:59:27','2019-10-09 10:59:27',1,18,1),(20,'HR Generalist',1,'HR Generalist',1,2,5,1,'HRGT','2019-10-09 10:59:27','2021-05-29 18:21:12',1,18,0),(21,'HR Lead',3,'HR Lead',0,2,5,2,'HRL','2019-10-09 10:59:27','2019-10-09 10:59:27',1,18,0),(22,'Recruitment and Resource Partner',3,'Recruitment and Resource Partner',0,2,5,2,'RARP','2019-10-09 10:59:27','2019-10-09 10:59:27',1,18,0),(23,'Training and Development Officer',3,'Training and Development Officer',0,2,5,2,'TDO','2019-10-09 10:59:27','2019-10-09 10:59:27',1,18,0),(24,'Mental Health Admin',3,'Mental Health Admin',0,2,1,1,'MHA','2019-10-09 10:59:27','2019-10-09 10:59:27',1,16,0),(25,'Mental Health Lead',3,'Mental Health Lead',0,2,5,2,'MHL','2019-10-09 10:59:27','2019-10-09 10:59:27',1,18,0),(26,'Staf',3,'Staf',0,2,2,5,'STAF','2019-10-09 10:59:27','2019-10-09 10:59:27',1,18,1),(27,'Corporate',1,'Corporate Staff',1,2,2,1,'CORP','2019-10-09 10:59:27','2021-05-29 18:20:22',1,18,1),(28,'Test',3,'vb',1,2,NULL,5,'vvvv','2019-11-05 15:35:12',NULL,NULL,18,0),(29,'Assistant Psychologist',3,'Assistant Psychologist',1,1,NULL,4,'APSCH','2019-11-21 21:01:16','2021-05-29 18:20:10',1,18,1),(30,'Finance Director',3,'Finance Director',1,1,NULL,2,'FDIR','2019-11-21 21:07:51',NULL,NULL,18,0),(31,'Director of Clinical Services',3,'Director of Clinical Services',1,1,NULL,2,'DIRCS','2019-11-21 21:08:14',NULL,NULL,18,0),(32,'Director of Operations',3,'Director of Operations',1,1,NULL,2,'DIRO','2019-11-21 21:08:35','2019-11-21 21:11:55',1,18,0),(33,'Professional and Practice Development Nurse',3,'Professional and Practice Development Nurse',1,1,NULL,3,'PPDN','2019-11-21 21:09:44',NULL,NULL,18,1),(34,'Recruitment Admin',3,'Recruitment Admin',1,1,NULL,1,'RA','2019-11-21 21:10:19',NULL,NULL,16,0),(35,'Personal Assistant',3,'Personal Assistant',1,1,NULL,1,'PA','2019-11-21 21:11:10','2021-05-29 18:21:56',1,18,0),(36,'Occupational Therapy Lead',3,'Occupational Therapy Lead',1,1,NULL,2,'OTL','2019-11-21 21:11:43',NULL,NULL,18,1),(37,'Psychologist',3,'Psychologist',1,0,NULL,4,'PSY','2019-11-29 06:16:14','2021-05-29 18:22:12',1,18,1),(38,'Senior Nurse',3,'Senior Nurse',1,0,NULL,3,'SN','2019-11-29 06:16:41','2021-05-29 18:22:50',1,18,1),(39,'HR & MHA Manager',3,'HR & MHA Manager',1,1,NULL,2,'HRMHA','2019-12-21 12:32:05',NULL,NULL,18,0),(40,'Assistant Practitioner',1,'Assistant Practitioner',1,4,3,3,'AP','2020-02-06 11:24:25','2021-05-29 18:20:04',1,9,1),(41,'Registered General Nurse - Night',1,'Registered General Nurse - Night',1,4,NULL,3,'RGN-N','2020-04-24 07:09:50','2020-04-24 07:16:48',1,7,1),(42,'Registered Learning Disability Nurse - Night',1,'Registered Learning Disability Nurse - Night',1,4,NULL,3,'RNL-N','2020-04-24 07:18:30',NULL,NULL,8,0),(43,'Registered Mental Health Nurse - Night',1,'Registered Mental Health Nurse - Night',1,4,NULL,3,'RMN-N','2020-04-24 07:19:48',NULL,NULL,6,0),(44,'Health and Social Care Worker - Nights',1,'Health and Social Care Worker - Nights',1,2,NULL,5,'HSCWN','2020-04-24 15:27:56','2021-05-29 18:21:03',1,13,0),(45,'Senior Health and Social Care Worker  - Night',1,'Senior Health and Social Care Worker  - Night',1,2,NULL,5,'SHCWN','2020-04-24 15:30:24','2021-05-29 18:22:45',1,12,0),(46,'Research Assistant',3,'Research Assistant',1,2,NULL,4,'RA','2020-07-08 09:46:03','2021-05-29 18:22:34',1,NULL,NULL),(47,'Receptionist',1,'Reception',1,2,NULL,1,'REC','2022-06-28 16:58:12',NULL,NULL,16,NULL),(48,'Compliance Officer',1,'Compliance',1,1,NULL,4,'COMP','2022-06-28 16:58:53',NULL,NULL,16,NULL),(49,'Kitchen Assistant',1,'Kitchen',1,1,NULL,4,'KIT','2022-06-28 16:59:29',NULL,NULL,NULL,NULL),(50,'Senior HR Administrator',1,'SHR',1,2,NULL,1,'SHR','2022-11-08 10:33:14',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `master_designation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_group`
--

DROP TABLE IF EXISTS `master_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` varchar(50) DEFAULT NULL,
  `subunit_access` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_group`
--

LOCK TABLES `master_group` WRITE;
/*!40000 ALTER TABLE `master_group` DISABLE KEYS */;
INSERT INTO `master_group` VALUES (1,'Administrator',1,'2019-07-01 15:46:30','2019-11-11 16:25:51','1',1),(2,'Employee',1,'2019-07-01 15:47:05','2019-07-29 12:07:56','1',0),(3,'Unit Manager',1,'2019-07-01 00:00:00','2019-08-21 12:34:26','1',1),(5,'Unit Supervisor',1,'2019-07-12 05:04:39',NULL,NULL,1),(6,'Unit Administrator',1,'2019-09-20 09:07:29','2020-01-20 06:37:04','1',1),(7,'Deputy Manager',3,'2020-01-31 17:09:11',NULL,NULL,0),(8,'Test',3,'2020-02-07 16:43:58',NULL,NULL,0),(9,'Deputy Managers',1,'2020-02-11 04:05:23',NULL,NULL,1),(10,'Unit Senior',1,'2020-02-18 17:24:08','2020-03-12 11:14:45','1',0),(11,'Training and Development',1,'2020-02-18 17:24:26','2020-02-18 18:05:59','1',0),(12,'Super Users',1,'2020-05-02 16:18:53',NULL,NULL,0),(18,'View Only',1,'2020-05-02 16:18:53','2020-05-02 16:18:53','1',1),(19,'TeamManager',1,'2022-07-13 20:25:27','2022-07-13 20:25:48','1',0),(20,'RotaPlanner',1,'2022-08-09 12:26:19',NULL,NULL,1);
/*!40000 ALTER TABLE `master_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_payment_type`
--

DROP TABLE IF EXISTS `master_payment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_payment_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(45) DEFAULT NULL,
  `description` text,
  `status` tinyint(4) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_payment_type`
--

LOCK TABLES `master_payment_type` WRITE;
/*!40000 ALTER TABLE `master_payment_type` DISABLE KEYS */;
INSERT INTO `master_payment_type` VALUES (1,'Agency Payment','Agency Payment',1,'2019-07-01 15:52:01','2019-07-01 15:52:01',1),(8,'Hourly','Hourly',1,'2019-07-01 15:51:34','2019-09-19 06:55:34',1),(9,'Bank','Bank',1,'2019-07-01 15:51:46','2019-07-08 09:05:55',1),(10,'Fixed','FXD',1,'2019-07-01 15:51:53','2019-11-21 20:49:31',1),(11,'Salaried','Salaried',1,'2019-07-01 15:52:01','2019-09-20 09:23:03',1);
/*!40000 ALTER TABLE `master_payment_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_shift`
--

DROP TABLE IF EXISTS `master_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_shift` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(200) NOT NULL,
  `shift_shortcut` varchar(20) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `shift_category` tinyint(4) DEFAULT NULL,
  `shift_type` float DEFAULT NULL,
  `part_number` tinyint(4) DEFAULT NULL,
  `color_unit` varchar(20) DEFAULT NULL,
  `targeted_hours` varchar(10) DEFAULT NULL,
  `unpaid_break_hours` varchar(10) DEFAULT NULL,
  `total_targeted_hours` varchar(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `hours` varchar(10) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updation_userid` bigint(20) DEFAULT NULL,
  `background_color` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_shift`
--

LOCK TABLES `master_shift` WRITE;
/*!40000 ALTER TABLE `master_shift` DISABLE KEYS */;
INSERT INTO `master_shift` VALUES (1,'Offday','X','00:00:00','00:00:00',NULL,0,0,'#000000','0','0',NULL,1,'0','2019-08-02 14:01:19',NULL,NULL,'#e6e6ea'),(2,'Holiday','H','00:00:00','00:00:00',0,0,0,'#000000','0','0',NULL,1,'0','2019-09-18 18:24:49',NULL,NULL,'#63ace5'),(3,'Training','T','00:00:00','00:00:00',0,0,0,'#000000','0','0',NULL,1,'0','2019-08-02 14:01:19',NULL,NULL,'#a8d67e'),(4,'Sick','S','00:00:00','00:00:00',0,0,0,NULL,'0','0',NULL,1,'0','2019-07-31 01:14:06',NULL,NULL,'rgb(135, 255, 0)'),(5,'Absent Without Leave','AWOL','00:00:00','00:00:00',0,0,0,NULL,'0','0',NULL,1,'0','2019-07-31 01:14:06',NULL,NULL,'rgb(239, 12, 12)'),(6,'8-8','8.8','08:00','20:00',1,1,0,'#000000','12:00','00:30','11:30',1,'12.0','2021-05-09 14:16:16','2021-05-09 14:16:16',1,''),(7,'6-6','6.6','06:00','18:00',1,1,0,'rgb(0, 0, 0)','12:00','00:30','11:30',1,'12.0','2022-09-29 11:41:59','2022-09-29 11:41:59',256,'rgb(255, 255, 255)'),(8,'7.30-3.30','7.30-3.30','07:30','15:30',1,1,0,'#000000','07:15','00:00','7:15',3,'8.0','2019-12-10 04:53:21','2019-12-10 04:53:21',1,NULL);
/*!40000 ALTER TABLE `master_shift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_training`
--

DROP TABLE IF EXISTS `master_training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_training` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `time_from` varchar(15) DEFAULT NULL,
  `time_to` varchar(15) DEFAULT NULL,
  `training_hour` varchar(20) DEFAULT NULL,
  `place` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `unit` varchar(500) DEFAULT NULL,
  `point_of_person` varchar(40) NOT NULL,
  `contact_num` varchar(20) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_training`
--

LOCK TABLES `master_training` WRITE;
/*!40000 ALTER TABLE `master_training` DISABLE KEYS */;
INSERT INTO `master_training` VALUES (1,'Security','ndling ','2023-05-18','2023-05-18','01:00','01:00','10:00','Kochi','Test','2','1','2323232323','chinchu+1@neork.com',0,'2023-05-18 16:23:07',NULL,1);
/*!40000 ALTER TABLE `master_training` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_id` varchar(100) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `comment` text,
  `notification_type` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (1,'2','Test','Test',3,0,'2023-05-18 14:13:02','2023-05-18 14:13:02',1);
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes_staff`
--

DROP TABLE IF EXISTS `notes_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notes_staff` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `note_id` bigint(20) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `updation_date` datetime NOT NULL,
  `updated_userid` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes_staff`
--

LOCK TABLES `notes_staff` WRITE;
/*!40000 ALTER TABLE `notes_staff` DISABLE KEYS */;
INSERT INTO `notes_staff` VALUES (1,12,1,0,'2023-05-18 14:13:02','2023-05-18 14:13:02',1);
/*!40000 ALTER TABLE `notes_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll_comment`
--

DROP TABLE IF EXISTS `payroll_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll_comment`
--

LOCK TABLES `payroll_comment` WRITE;
/*!40000 ALTER TABLE `payroll_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Admin.Dashboard.View','Allow to manage dashboard',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(2,'Admin.Dailycensus.View','Allow to manage census',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(3,'Admin.Dailycensus.Add','Allow to add census',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(4,'Admin.Dailycensus.Edit','Allow to edit census',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(5,'Admin.Dailycensus.Delete','Allow to delete census',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(6,'Profile.View','Allow to view and edit profile',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(7,'Admin.User.View','Allow to manage users',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(8,'Admin.User.Add','Allow to add users',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(9,'Admin.User.Edit','Allow to edit users',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(10,'Admin.User.Delete','Allow to delete users',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(11,'Admin.User.Changepassword','Allow to change password',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(12,'Admin.User.Changepicture','Allow to change picture',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(13,'Admin.Designation.View','Allow to manage Designation add',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(14,'Admin.Designation.Add','Allow to add designation',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(15,'Admin.Designation.Edit','Allow to edit designation',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(16,'Admin.Designation.Delete','Allow to delete designation',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(17,'Admin.Payment type.View','Allow to manage payment type',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(18,'Admin.Payment type.Add','Allow to add payment type',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(19,'Admin.Payment type.Edit','Allow to edit payment type',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(20,'Admin.Payment type.Delete','Allow to delete payment type',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(21,'Admin.Shift.View','Allow to manage shift',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(22,'Admin.Shift.Add','Allow to addshift',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(23,'Admin.Shift.Edit','Allow to edit shift',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(24,'Admin.shift.Delete','Allow to delete shift',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(25,'Admin.Notes.View','Allow to manage notes',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(26,'Admin.Notes.Add','Allow to add notes',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(27,'Admin.Notes.Edit','Allow to edit notes',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(28,'Admin.Notes.Delete','Allow to delete notes',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(29,'Admin.Annual Leave.View','Allow to manage annual leave',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(30,'Admin.Annual Leave.Add','Allow to add annual leave',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(31,'Admin.Annual Leave.Approve','Allow to approve annual leave',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(32,'Admin.Annual Leave.Reject','Allow to reject annual leave',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(33,'Admin.Rota.Create','Allow to create rota',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(34,'Admin.Rota.Edit','Allow to edit rota',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(35,'Admin.Rota.View','Allow to view rota',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(37,'Admin.Rota.EmployeeAvailability','Allow to view employee availability',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(38,'Admin.Training.View','Allow to manage training',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(39,'Admin.Training.Add','Allow to add  training',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(40,'Admin.Training.Edit','Allow to edit training',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(41,'Admin.Training.Delete','Allow to delete training',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(42,'Admin.Unit.View','Allow to manage unit',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(43,'Admin.Unit.Add','Allow to add unit',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(44,'Admin.Unit.Edit','Allow to edit unit',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(45,'Admin.Unit.Delete','Allow to delete unit',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(46,'Admin.Group.Add','Allow to manage group',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(47,'Admin.Group.Edit','Allow to edit group',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(48,'Admin.Group.Permission','Allow to add group permission',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(49,'Admin.Group.Delete','Allow to delete group',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(50,'Admin.Report.Timelog','Allow to manage timelog report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(51,'Admin.Report.Payroll','Allow to manage payroll report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(52,'Admin.Report.Extrahours','Allow to manage extra hours report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(53,'Admin.Report.Lastlogin','Allow to manage last login report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(54,'Admin.Report.Rotahistory','Allow to manage rota history report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(55,'Admin.Report.Employeelist','Allow to manage employee list report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(56,'Admin.Report.Employeedetailedlist','Allow to manage employee detailed list report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(57,'Admin.Report.Annualleave','Allow to manage annual leave report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(58,'Admin.Report.Annualleaveallstaff','Allow to manage annual leave all staff report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(59,'Admin.Report.Training','Allow to manage training report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(60,'Admin.Report.Overstaffing','Allow to manage overstaffing report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(61,'Admin.Report.Availability','Allow to manage employee availability report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(62,'Admin.Report.Absence','Allow to manage absence report',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(63,'Admin.History.View','Allow to manage history',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(64,'Admin.Company.Edit','Allow to edit company details',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(65,'Annual Leave.View','Allow to manage annual leave',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(66,'Rota.View','Allow to view rota',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(67,'Rota.Availability','Allow to manage availability',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(68,'Admin.Mytraining','Allow to manage my training',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(69,'Admin.Holiday.Add','Allow to add bank holiday',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(70,'Admin.Holiday.Edit','Allow to edit bank holiday',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(71,'Admin.Password.Change','Allow to change paswword',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',1),(72,'Admin.Rota.Edit Max/Min','Allow to edit max/min value',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',NULL),(73,'Admin.addresshistory.View','Allow to view address change history',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',NULL),(74,'Admin.userunithistory.View','Allow to view user unit change history',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',NULL),(75,'Admin.designationhistory.View','Allow to view designation change history',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',NULL),(76,'Admin.userrateshistory.View','Allow to view user rates change history',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',NULL),(77,'Admin.rotaupdatehistory.View','Allow to view rota update history',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',NULL),(78,'Admin.Editrota.Edit/Update Unpublished','Allow to edit unpublished rota',1,'2019-07-08 16:15:30','2019-07-08 16:15:30',NULL),(79,'Admin.Report.AgencyReport','Allow to view Agency user report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(80,'Admin.Report.AnnualLeaveplanner','Allow to view Annual leave planner',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(81,'Admin.Report.Availability_report_count','Allow to view Availability count report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(82,'Admin.Report.Availability_report_user_count','Allow to view user availability count',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(83,'Admin.Report.Overtime_Report','Allow to view overtime report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(84,'Admin.Report.Requestvsactualreport','Allow to view reqsuest vs actual shift report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(85,'Admin.Report.SicknessReport','Allow to view sickness report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(86,'Admin.Report.Timesheet','Allow to view Timesheet report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(87,'Admin.Report.TransferHour','Allow to view transfer hour report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(88,'Admin.Report.Weekendsreport','Allow to view weekends worked report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(89,'Admin.Report.Workingreport','Allow to view working report',1,'2021-06-04 16:15:30','2021-06-04 16:15:30',1),(90,'Admin.rota.Moveshift','Allow to move shift',1,'2021-06-11 16:15:30','2021-06-11 16:15:30',NULL),(91,'Admin.Report.lateness_report','Allow to view late check in staffs',1,'2021-11-10 10:35:30','2021-11-10 10:35:30',1),(92,'Admin.Report.earlyleaver_report','Allow to view early leaver report',1,'2021-11-10 10:35:30','2021-11-10 10:35:30',1),(93,'Admin.Report.Agencyloginreport','Allow to view agency staff timelog report',1,'2021-11-10 10:35:30','2021-11-10 10:35:30',1);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_details`
--

DROP TABLE IF EXISTS `personal_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `fname` varchar(45) DEFAULT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `mobile_number` varchar(45) DEFAULT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `NINnumbers` varchar(45) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `address3` varchar(100) DEFAULT NULL,
  `address4` varchar(100) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `Ethnicity` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `visa_status` bigint(20) DEFAULT NULL,
  `kin_name` varchar(45) DEFAULT NULL,
  `kin_phone` varchar(45) DEFAULT NULL,
  `kin_address` varchar(500) DEFAULT NULL,
  `kin_address1` varchar(100) DEFAULT NULL,
  `kin_address2` varchar(100) DEFAULT NULL,
  `kin_address3` varchar(100) DEFAULT NULL,
  `kin_postcode` varchar(20) DEFAULT NULL,
  `kin_relationship` varchar(45) DEFAULT NULL,
  `kin_email` varchar(45) DEFAULT NULL,
  `kin_telephone` varchar(45) DEFAULT NULL,
  `kin_telephone_work` varchar(45) DEFAULT NULL,
  `bank_account` varchar(100) DEFAULT NULL,
  `bank_sortcode` varchar(100) DEFAULT NULL,
  `jobcode` varchar(45) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `taxcode` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_personal_details_1_idx` (`user_id`),
  CONSTRAINT `fk_personal_details_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_details`
--

LOCK TABLES `personal_details` WRITE;
/*!40000 ALTER TABLE `personal_details` DISABLE KEYS */;
INSERT INTO `personal_details` VALUES (1,1,'Super','Admin','M','0',NULL,NULL,NULL,'2022-08-03','.','-',NULL,NULL,'','United Kingdom',1,'Pakistani','.',0,'.','.','.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-08-03 15:38:37',967,NULL,NULL),(2,2,'Chinchu','Gopi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 14:26:44','2023-05-17 14:26:44',NULL,NULL,NULL),(3,3,'Chinchu','Gopi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 14:27:18','2023-05-17 14:27:18',NULL,NULL,NULL),(4,4,'Chinchu','Gopi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 15:56:37','2023-05-17 15:56:37',NULL,NULL,NULL),(5,5,'Chinchu','Gopi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 16:09:26','2023-05-17 16:09:26',NULL,NULL,NULL),(6,6,'Chinchu','Gopi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 16:14:04','2023-05-17 16:14:04',NULL,NULL,NULL),(7,7,'karthika','K',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 16:15:29','2023-05-17 16:15:29',NULL,NULL,NULL),(8,8,'Chinchu','Gopi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 10:39:32','2023-05-18 10:39:32',NULL,NULL,NULL),(9,9,'CHinchu','gopi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 11:56:37','2023-05-18 11:56:37',NULL,NULL,NULL),(10,10,'chincu','v',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 12:00:17','2023-05-18 12:00:17',NULL,NULL,NULL),(11,11,'chinchu','chinchu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 12:03:32','2023-05-18 12:03:32',NULL,NULL,NULL),(12,12,'chinchu','chinchu','F','5656565656',NULL,NULL,NULL,'0000-00-00','Thekkumkalayil house, Kaippattoor po, Thottoor','Thekkumkalayil house',NULL,NULL,'','United Kingdom',NULL,'Indian','454545',0,'chinchu','5656565656','Thekkumkalayil house, Kaippattoor po, Thottoor',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 12:07:22','2023-05-18 16:13:08',1,NULL,NULL);
/*!40000 ALTER TABLE `personal_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rota`
--

DROP TABLE IF EXISTS `rota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rota` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rota_settings` bigint(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_user_id` bigint(20) DEFAULT NULL,
  `published` bigint(20) DEFAULT NULL,
  `month` bigint(20) DEFAULT NULL,
  `year` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rota`
--

LOCK TABLES `rota` WRITE;
/*!40000 ALTER TABLE `rota` DISABLE KEYS */;
INSERT INTO `rota` VALUES (1,1,'2023-05-21','2023-05-27',2,'2023-05-18 16:14:41','2023-05-18 16:14:41',1,1,5,2023);
/*!40000 ALTER TABLE `rota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rota_log`
--

DROP TABLE IF EXISTS `rota_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rota_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rota_id` bigint(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `rota_details` text,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `unit_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rota_log`
--

LOCK TABLES `rota_log` WRITE;
/*!40000 ALTER TABLE `rota_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `rota_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rota_schedule`
--

DROP TABLE IF EXISTS `rota_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rota_schedule` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) DEFAULT '0',
  `shift_hours` varchar(45) DEFAULT NULL,
  `additional_hours` varchar(45) DEFAULT NULL,
  `day_additional_hours` varchar(45) DEFAULT NULL,
  `night_additional_hours` varchar(45) DEFAULT NULL,
  `additinal_hour_timelog_id` varchar(45) DEFAULT NULL,
  `comment` text,
  `from_unit` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) NOT NULL,
  `rota_id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  `day` varchar(45) DEFAULT NULL,
  `designation_id` varchar(45) DEFAULT NULL,
  `shift_category` tinyint(4) DEFAULT NULL,
  `from_userid` bigint(20) DEFAULT NULL,
  `from_rotaid` bigint(20) DEFAULT NULL,
  `request_id` bigint(20) DEFAULT NULL,
  `auto_insert` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_rota_3_idx` (`unit_id`),
  KEY `fk_rota_1_idx` (`user_id`),
  CONSTRAINT `fk_rota_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_rota_3` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rota_schedule`
--

LOCK TABLES `rota_schedule` WRITE;
/*!40000 ALTER TABLE `rota_schedule` DISABLE KEYS */;
INSERT INTO `rota_schedule` VALUES (1,12,1,'0',NULL,NULL,NULL,NULL,NULL,NULL,2,1,'2023-05-21',1,'2023-05-18 16:14:41',1,'2023-05-18 16:14:41',1,'Su','14',0,NULL,NULL,NULL,NULL),(2,12,7,'12:00',NULL,NULL,NULL,NULL,NULL,NULL,2,1,'2023-05-22',1,'2023-05-18 16:14:41',1,'2023-05-18 16:14:41',1,'Mo','14',1,NULL,NULL,NULL,NULL),(3,12,7,'12:00',NULL,NULL,NULL,NULL,NULL,NULL,2,1,'2023-05-23',1,'2023-05-18 16:14:41',1,'2023-05-18 16:14:41',1,'Tu','14',1,NULL,NULL,NULL,NULL),(4,12,7,'12:00',NULL,NULL,NULL,NULL,NULL,NULL,2,1,'2023-05-24',1,'2023-05-18 16:14:41',1,'2023-05-18 16:14:41',1,'We','14',1,NULL,NULL,NULL,NULL),(5,12,7,'12:00',NULL,NULL,NULL,NULL,NULL,NULL,2,1,'2023-05-25',1,'2023-05-18 16:14:41',1,'2023-05-18 16:14:41',1,'Th','14',1,NULL,NULL,NULL,NULL),(6,12,7,'12:00',NULL,NULL,NULL,NULL,NULL,NULL,2,1,'2023-05-26',1,'2023-05-18 16:14:41',1,'2023-05-18 16:14:41',1,'Fr','14',1,NULL,NULL,NULL,NULL),(7,12,1,'0',NULL,NULL,NULL,NULL,NULL,NULL,2,1,'2023-05-27',1,'2023-05-18 16:14:41',1,'2023-05-18 16:14:41',1,'Sa','14',0,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `rota_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rota_settings`
--

DROP TABLE IF EXISTS `rota_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rota_settings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `day_shift_min` bigint(20) DEFAULT NULL,
  `day_shift_max` bigint(20) DEFAULT NULL,
  `night_shift_min` bigint(20) DEFAULT NULL,
  `night_shift_max` bigint(20) DEFAULT NULL,
  `num_patients` bigint(20) DEFAULT NULL,
  `one_one_patients` bigint(20) DEFAULT NULL,
  `nurse_day_count` bigint(20) DEFAULT NULL,
  `nurse_night_count` bigint(20) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rota_settings`
--

LOCK TABLES `rota_settings` WRITE;
/*!40000 ALTER TABLE `rota_settings` DISABLE KEYS */;
INSERT INTO `rota_settings` VALUES (1,1,2,1,2,1,1,1,1,'2023-05-18 16:13:38');
/*!40000 ALTER TABLE `rota_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary`
--

DROP TABLE IF EXISTS `salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  CONSTRAINT `fk_salary_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
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
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `sms_log`
--

DROP TABLE IF EXISTS `sms_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mobile_number` varchar(45) NOT NULL,
  `status_code` varchar(45) NOT NULL,
  `message_id` varchar(45) NOT NULL,
  `date` varchar(45) NOT NULL,
  `sender_id` varchar(45) NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_log`
--

LOCK TABLES `sms_log` WRITE;
/*!40000 ALTER TABLE `sms_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_rota`
--

DROP TABLE IF EXISTS `staff_rota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_rota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `published` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_rota`
--

LOCK TABLES `staff_rota` WRITE;
/*!40000 ALTER TABLE `staff_rota` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_rota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staffrota_schedule`
--

DROP TABLE IF EXISTS `staffrota_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staffrota_schedule` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) DEFAULT '0',
  `shift_hours` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) NOT NULL,
  `rota_id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staffrota_schedule`
--

LOCK TABLES `staffrota_schedule` WRITE;
/*!40000 ALTER TABLE `staffrota_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `staffrota_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `time_log`
--

DROP TABLE IF EXISTS `time_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `time_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `payroll_id` varchar(45) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `shift_id` bigint(20) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `user_unit` bigint(20) DEFAULT NULL,
  `device_id` varchar(250) NOT NULL,
  `accuracy` varchar(45) DEFAULT NULL,
  `date` date NOT NULL,
  `time_to` time DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `IPaddress` varchar(500) DEFAULT NULL,
  `with_userid` bigint(20) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `offline_status` bigint(20) DEFAULT '0',
  `offline_id` bigint(20) DEFAULT NULL,
  `unique_id` varchar(100) DEFAULT NULL,
  `jsonid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_time_log_1` (`user_id`),
  KEY `fk_time_log_2` (`shift_id`),
  KEY `fk_time_log_3_idx` (`unit_id`)
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
-- Table structure for table `time_log_data`
--

DROP TABLE IF EXISTS `time_log_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `time_log_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data` longtext,
  `json_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_log_data`
--

LOCK TABLES `time_log_data` WRITE;
/*!40000 ALTER TABLE `time_log_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `time_log_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `time_log_json`
--

DROP TABLE IF EXISTS `time_log_json`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `time_log_json` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  `count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_log_json`
--

LOCK TABLES `time_log_json` WRITE;
/*!40000 ALTER TABLE `time_log_json` DISABLE KEYS */;
/*!40000 ALTER TABLE `time_log_json` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_feedback`
--

DROP TABLE IF EXISTS `training_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  CONSTRAINT `fk_training_feedback_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_training_feedback_2` FOREIGN KEY (`training_id`) REFERENCES `master_training` (`id`)
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_staff` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `training_id` bigint(20) DEFAULT NULL,
  `shift_id` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_training_staff_1_idx` (`user_id`),
  KEY `fk_training_staff_2_idx` (`training_id`),
  CONSTRAINT `fk_training_staff_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_training_staff_2` FOREIGN KEY (`training_id`) REFERENCES `master_training` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_staff`
--

LOCK TABLES `training_staff` WRITE;
/*!40000 ALTER TABLE `training_staff` DISABLE KEYS */;
INSERT INTO `training_staff` VALUES (1,12,1,NULL,0,'2023-05-18 16:23:07','2023-05-18 16:23:07',1,0);
/*!40000 ALTER TABLE `training_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_titles`
--

DROP TABLE IF EXISTS `training_titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_titles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_titles`
--

LOCK TABLES `training_titles` WRITE;
/*!40000 ALTER TABLE `training_titles` DISABLE KEYS */;
INSERT INTO `training_titles` VALUES (1,'Security','ndling ','2022-12-16 15:57:45');
/*!40000 ALTER TABLE `training_titles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  CONSTRAINT `fk_unit_1` FOREIGN KEY (`unit_type`) REFERENCES `unit_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit`
--

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` VALUES (1,'Agency',2,0,'A',2,2,2,'2',1,'#000000','2023-05-18 14:03:29','Test','2323232323',NULL,NULL),(2,'Kochi',1,0,'KC',2,2,2,'2',1,'#b46161','2023-05-18 14:04:14','Test','2323232323',NULL,NULL);
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit_designation_maxleave`
--

DROP TABLE IF EXISTS `unit_designation_maxleave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit_designation_maxleave` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) NOT NULL,
  `designation_id` bigint(20) NOT NULL,
  `max_leave` int(11) DEFAULT NULL,
  `max_leave_hour` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_unit_designation_maxleave_1_idx` (`unit_id`),
  KEY `fk_unit_designation_maxleave_2_idx` (`designation_id`),
  CONSTRAINT `fk_unit_designation_maxleave_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`),
  CONSTRAINT `fk_unit_designation_maxleave_2` FOREIGN KEY (`designation_id`) REFERENCES `master_designation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_designation_maxleave`
--

LOCK TABLES `unit_designation_maxleave` WRITE;
/*!40000 ALTER TABLE `unit_designation_maxleave` DISABLE KEYS */;
INSERT INTO `unit_designation_maxleave` VALUES (1,13,1,0,NULL,'2022-07-13 00:00:00',1),(2,13,2,0,NULL,'2022-07-13 00:00:00',1),(3,13,4,0,NULL,'2022-07-13 00:00:00',1),(4,13,5,0,NULL,'2022-07-13 00:00:00',1),(5,13,6,0,NULL,'2022-07-13 00:00:00',1),(6,13,8,0,NULL,'2022-07-13 00:00:00',1),(7,13,10,0,NULL,'2022-07-13 00:00:00',1),(8,13,11,0,NULL,'2022-07-13 00:00:00',1),(9,13,12,0,NULL,'2022-07-13 00:00:00',1),(10,13,13,0,NULL,'2022-07-13 00:00:00',1),(11,13,14,0,NULL,'2022-07-13 00:00:00',1),(12,13,15,0,NULL,'2022-07-13 00:00:00',1),(13,13,16,0,NULL,'2022-07-13 00:00:00',1),(14,13,17,0,NULL,'2022-07-13 00:00:00',1),(15,13,18,0,NULL,'2022-07-13 00:00:00',1),(16,13,20,0,NULL,'2022-07-13 00:00:00',1),(17,13,27,0,NULL,'2022-07-13 00:00:00',1),(18,13,40,0,NULL,'2022-07-13 00:00:00',1),(19,13,41,0,NULL,'2022-07-13 00:00:00',1),(20,13,42,0,NULL,'2022-07-13 00:00:00',1),(21,13,43,0,NULL,'2022-07-13 00:00:00',1),(22,13,44,0,NULL,'2022-07-13 00:00:00',1),(23,13,45,0,NULL,'2022-07-13 00:00:00',1),(24,13,47,0,NULL,'2022-07-13 00:00:00',1),(25,13,48,0,NULL,'2022-07-13 00:00:00',1),(26,13,49,0,NULL,'2022-07-13 00:00:00',1),(27,1,1,0,NULL,'2023-05-17 00:00:00',1),(28,1,2,0,NULL,'2023-05-17 00:00:00',1),(29,1,4,0,NULL,'2023-05-17 00:00:00',1),(30,1,5,0,NULL,'2023-05-17 00:00:00',1),(31,1,6,0,NULL,'2023-05-17 00:00:00',1),(32,1,8,0,NULL,'2023-05-17 00:00:00',1),(33,1,10,0,NULL,'2023-05-17 00:00:00',1),(34,1,11,0,NULL,'2023-05-17 00:00:00',1),(35,1,12,0,NULL,'2023-05-17 00:00:00',1),(36,1,13,0,NULL,'2023-05-17 00:00:00',1),(37,1,14,2,NULL,'2023-05-17 00:00:00',1),(38,1,15,0,NULL,'2023-05-17 00:00:00',1),(39,1,16,0,NULL,'2023-05-17 00:00:00',1),(40,1,17,0,NULL,'2023-05-17 00:00:00',1),(41,1,18,0,NULL,'2023-05-17 00:00:00',1),(42,1,20,0,NULL,'2023-05-17 00:00:00',1),(43,1,27,0,NULL,'2023-05-17 00:00:00',1),(44,1,40,0,NULL,'2023-05-17 00:00:00',1),(45,1,41,0,NULL,'2023-05-17 00:00:00',1),(46,1,42,0,NULL,'2023-05-17 00:00:00',1),(47,1,43,0,NULL,'2023-05-17 00:00:00',1),(48,1,44,0,NULL,'2023-05-17 00:00:00',1),(49,1,45,0,NULL,'2023-05-17 00:00:00',1),(50,1,47,0,NULL,'2023-05-17 00:00:00',1),(51,1,48,0,NULL,'2023-05-17 00:00:00',1),(52,1,49,0,NULL,'2023-05-17 00:00:00',1),(53,1,50,0,NULL,'2023-05-17 00:00:00',1),(54,2,1,0,NULL,'2023-05-17 00:00:00',1),(55,2,2,0,NULL,'2023-05-17 00:00:00',1),(56,2,4,0,NULL,'2023-05-17 00:00:00',1),(57,2,5,0,NULL,'2023-05-17 00:00:00',1),(58,2,6,0,NULL,'2023-05-17 00:00:00',1),(59,2,8,0,NULL,'2023-05-17 00:00:00',1),(60,2,10,0,NULL,'2023-05-17 00:00:00',1),(61,2,11,0,NULL,'2023-05-17 00:00:00',1),(62,2,12,0,NULL,'2023-05-17 00:00:00',1),(63,2,13,0,NULL,'2023-05-17 00:00:00',1),(64,2,14,2,NULL,'2023-05-17 00:00:00',1),(65,2,15,0,NULL,'2023-05-17 00:00:00',1),(66,2,16,0,NULL,'2023-05-17 00:00:00',1),(67,2,17,0,NULL,'2023-05-17 00:00:00',1),(68,2,18,0,NULL,'2023-05-17 00:00:00',1),(69,2,20,0,NULL,'2023-05-17 00:00:00',1),(70,2,27,0,NULL,'2023-05-17 00:00:00',1),(71,2,40,0,NULL,'2023-05-17 00:00:00',1),(72,2,41,0,NULL,'2023-05-17 00:00:00',1),(73,2,42,0,NULL,'2023-05-17 00:00:00',1),(74,2,43,0,NULL,'2023-05-17 00:00:00',1),(75,2,44,0,NULL,'2023-05-17 00:00:00',1),(76,2,45,0,NULL,'2023-05-17 00:00:00',1),(77,2,47,0,NULL,'2023-05-17 00:00:00',1),(78,2,48,0,NULL,'2023-05-17 00:00:00',1),(79,2,49,0,NULL,'2023-05-17 00:00:00',1),(80,2,50,0,NULL,'2023-05-17 00:00:00',1),(81,1,1,0,NULL,'2023-05-18 00:00:00',1),(82,1,2,0,NULL,'2023-05-18 00:00:00',1),(83,1,4,0,NULL,'2023-05-18 00:00:00',1),(84,1,5,0,NULL,'2023-05-18 00:00:00',1),(85,1,6,0,NULL,'2023-05-18 00:00:00',1),(86,1,8,0,NULL,'2023-05-18 00:00:00',1),(87,1,10,0,NULL,'2023-05-18 00:00:00',1),(88,1,11,0,NULL,'2023-05-18 00:00:00',1),(89,1,12,0,NULL,'2023-05-18 00:00:00',1),(90,1,13,0,NULL,'2023-05-18 00:00:00',1),(91,1,14,2,NULL,'2023-05-18 00:00:00',1),(92,1,15,0,NULL,'2023-05-18 00:00:00',1),(93,1,16,0,NULL,'2023-05-18 00:00:00',1),(94,1,17,0,NULL,'2023-05-18 00:00:00',1),(95,1,18,0,NULL,'2023-05-18 00:00:00',1),(96,1,20,0,NULL,'2023-05-18 00:00:00',1),(97,1,27,0,NULL,'2023-05-18 00:00:00',1),(98,1,40,0,NULL,'2023-05-18 00:00:00',1),(99,1,41,0,NULL,'2023-05-18 00:00:00',1),(100,1,42,0,NULL,'2023-05-18 00:00:00',1),(101,1,43,0,NULL,'2023-05-18 00:00:00',1),(102,1,44,0,NULL,'2023-05-18 00:00:00',1),(103,1,45,0,NULL,'2023-05-18 00:00:00',1),(104,1,47,0,NULL,'2023-05-18 00:00:00',1),(105,1,48,0,NULL,'2023-05-18 00:00:00',1),(106,1,49,0,NULL,'2023-05-18 00:00:00',1),(107,1,50,0,NULL,'2023-05-18 00:00:00',1),(108,2,1,0,NULL,'2023-05-18 00:00:00',1),(109,2,2,0,NULL,'2023-05-18 00:00:00',1),(110,2,4,0,NULL,'2023-05-18 00:00:00',1),(111,2,5,0,NULL,'2023-05-18 00:00:00',1),(112,2,6,0,NULL,'2023-05-18 00:00:00',1),(113,2,8,0,NULL,'2023-05-18 00:00:00',1),(114,2,10,0,NULL,'2023-05-18 00:00:00',1),(115,2,11,0,NULL,'2023-05-18 00:00:00',1),(116,2,12,0,NULL,'2023-05-18 00:00:00',1),(117,2,13,0,NULL,'2023-05-18 00:00:00',1),(118,2,14,2,NULL,'2023-05-18 00:00:00',1),(119,2,15,0,NULL,'2023-05-18 00:00:00',1),(120,2,16,0,NULL,'2023-05-18 00:00:00',1),(121,2,17,0,NULL,'2023-05-18 00:00:00',1),(122,2,18,0,NULL,'2023-05-18 00:00:00',1),(123,2,20,0,NULL,'2023-05-18 00:00:00',1),(124,2,27,0,NULL,'2023-05-18 00:00:00',1),(125,2,40,0,NULL,'2023-05-18 00:00:00',1),(126,2,41,0,NULL,'2023-05-18 00:00:00',1),(127,2,42,0,NULL,'2023-05-18 00:00:00',1),(128,2,43,0,NULL,'2023-05-18 00:00:00',1),(129,2,44,0,NULL,'2023-05-18 00:00:00',1),(130,2,45,0,NULL,'2023-05-18 00:00:00',1),(131,2,47,0,NULL,'2023-05-18 00:00:00',1),(132,2,48,0,NULL,'2023-05-18 00:00:00',1),(133,2,49,0,NULL,'2023-05-18 00:00:00',1),(134,2,50,0,NULL,'2023-05-18 00:00:00',1);
/*!40000 ALTER TABLE `unit_designation_maxleave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit_type`
--

DROP TABLE IF EXISTS `unit_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `unit_type` VALUES (1,'Hospital',1,'2019-07-01 15:48:30',NULL,NULL),(2,'Care Home',1,'2019-07-01 15:48:30',NULL,NULL);
/*!40000 ALTER TABLE `unit_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_edit_activitylog`
--

DROP TABLE IF EXISTS `user_edit_activitylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_edit_activitylog` (
  `id` bigint(29) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `current_data` text,
  `previous_data` text,
  `activity_type` varchar(100) DEFAULT NULL,
  `activity_date` datetime DEFAULT NULL,
  `activity_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_edit_activitylog`
--

LOCK TABLES `user_edit_activitylog` WRITE;
/*!40000 ALTER TABLE `user_edit_activitylog` DISABLE KEYS */;
INSERT INTO `user_edit_activitylog` VALUES (1,12,'email:chinchuneork@gmail.com,fname:chinchu,lname:chinchu,mobile:5656565656,dob:--,gender:F,ethnicity:Indian,visa_status:0,kin_name:chinchu,kin_phone:5656565656,kin_address:Thekkumkalayil house, Kaippattoor po, Thottoor','email:chinchuneork@gmail.com,fname:chinchu,lname:chinchu,mobile:,dob:,gender:,ethnicity:,visa_status:,kin_name:,kin_phone:,kin_address:','Personal Details','2023-05-18 12:56:30',1),(2,12,'group_id:3,weekly_hours:08:00,status:1,annual_holliday_allowance:222.00,actual_holiday_allowance:222.00,designation_id:14,start_date:2023-05-18,final_date:,notes:,payroll_id:123,default_shift:148,payment_type:9,exit_interview:1,exit_reason:,hr_id:','group_id:2,weekly_hours:00:00,status:1,annual_holliday_allowance:00.00,actual_holiday_allowance:00.00,designation_id:14,start_date:,final_date:,notes:,payroll_id:,default_shift:,payment_type:9,exit_interview:,exit_reason:,hr_id:','Employee Details','2023-05-18 12:57:43',1),(3,12,'sunday:1,monday:0,tuesday:0,wednesday:0,thursday:0,friday:0,saturday:1','sunday:0,monday:0,tuesday:0,wednesday:0,thursday:0,friday:0,saturday:0','workschedule','2023-05-18 12:57:55',1),(4,12,'email:chinchuneork@gmail.com,fname:chinchu,lname:chinchu,mobile:5656565656,dob:-0001-11-30,gender:F,ethnicity:Indian,visa_status:0,kin_name:chinchu,kin_phone:5656565656,kin_address:Thekkumkalayil house, Kaippattoor po, Thottoor','email:chinchuneork@gmail.com,fname:chinchu,lname:chinchu,mobile:5656565656,dob:0000-00-00,gender:F,ethnicity:Indian,visa_status:0,kin_name:chinchu,kin_phone:5656565656,kin_address:Thekkumkalayil house, Kaippattoor po, Thottoor','Personal Details','2023-05-18 16:13:08',1),(5,12,'group_id:3,weekly_hours:08:00,status:1,annual_holliday_allowance:222.00,actual_holiday_allowance:222.00,designation_id:14,start_date:2023-05-18,final_date:,notes:,payroll_id:123,default_shift:7,payment_type:9,exit_interview:1,exit_reason:,hr_id:','group_id:3,weekly_hours:08:00,status:1,annual_holliday_allowance:222.00,actual_holiday_allowance:222.00,designation_id:14,start_date:2023-05-18,final_date:0000-00-00,notes:,payroll_id:123,default_shift:148,payment_type:9,exit_interview:1,exit_reason:,hr_id:','Employee Details','2023-05-18 16:13:12',1);
/*!40000 ALTER TABLE `user_edit_activitylog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_email_send`
--

DROP TABLE IF EXISTS `user_email_send`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_email_send` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `mail_send_status` tinyint(4) DEFAULT NULL,
  `password_change_status` tinyint(4) DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_email_send_1_idx` (`user_id`),
  CONSTRAINT `fk_user_email_send_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_email_send`
--

LOCK TABLES `user_email_send` WRITE;
/*!40000 ALTER TABLE `user_email_send` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_email_send` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rates`
--

DROP TABLE IF EXISTS `user_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_rates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `day_rate` decimal(5,2) DEFAULT NULL,
  `night_rate` decimal(5,2) DEFAULT NULL,
  `day_saturday_rate` decimal(5,2) DEFAULT NULL,
  `day_sunday_rate` decimal(5,2) DEFAULT NULL,
  `weekend_night_rate` decimal(5,2) DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_new_table_1_idx` (`user_id`),
  CONSTRAINT `fk_new_table_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_rates`
--

LOCK TABLES `user_rates` WRITE;
/*!40000 ALTER TABLE `user_rates` DISABLE KEYS */;
INSERT INTO `user_rates` VALUES (1,2,0.00,0.00,0.00,0.00,0.00,'2023-05-17 14:26:44',NULL),(2,3,0.00,0.00,0.00,0.00,0.00,'2023-05-17 14:27:18',NULL),(3,4,0.00,0.00,0.00,0.00,0.00,'2023-05-17 15:56:37',NULL),(4,5,0.00,0.00,0.00,0.00,0.00,'2023-05-17 16:09:26',NULL),(5,6,0.00,0.00,0.00,0.00,0.00,'2023-05-17 16:14:04',NULL),(6,7,0.00,0.00,0.00,0.00,0.00,'2023-05-17 16:15:29',NULL),(7,8,0.00,0.00,0.00,0.00,0.00,'2023-05-18 10:39:32',NULL),(8,9,0.00,0.00,0.00,0.00,0.00,'2023-05-18 11:56:37',NULL),(9,10,0.00,0.00,0.00,0.00,0.00,'2023-05-18 12:00:17',NULL),(10,11,0.00,0.00,0.00,0.00,0.00,'2023-05-18 12:03:32',NULL),(11,12,4.00,NULL,NULL,NULL,NULL,'2023-05-18 16:13:12',1);
/*!40000 ALTER TABLE `user_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_unit`
--

DROP TABLE IF EXISTS `user_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  CONSTRAINT `fk_user_unit_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_user_unit_2` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_unit`
--

LOCK TABLES `user_unit` WRITE;
/*!40000 ALTER TABLE `user_unit` DISABLE KEYS */;
INSERT INTO `user_unit` VALUES (1,2,2,'2023-05-17 14:26:44','2023-05-17 14:26:44',NULL,1),(2,3,2,'2023-05-17 14:27:18','2023-05-17 14:27:18',NULL,1),(3,4,2,'2023-05-17 15:56:37','2023-05-17 15:56:37',NULL,1),(4,5,2,'2023-05-17 16:09:26','2023-05-17 16:09:26',NULL,1),(5,6,2,'2023-05-17 16:14:04','2023-05-17 16:14:04',NULL,1),(6,7,2,'2023-05-17 16:15:29','2023-05-17 16:15:29',NULL,1),(7,8,2,'2023-05-18 10:39:32','2023-05-18 10:39:32',NULL,1),(8,9,2,'2023-05-18 11:56:37','2023-05-18 11:56:37',NULL,1),(9,10,2,'2023-05-18 12:00:17','2023-05-18 12:00:17',NULL,1),(10,11,2,'2023-05-18 12:03:32','2023-05-18 12:03:32',NULL,1),(11,12,2,'2023-05-18 12:07:22','2023-05-18 12:07:22',NULL,1);
/*!40000 ALTER TABLE `user_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(120) NOT NULL,
  `password` char(100) NOT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `designation_id` bigint(20) DEFAULT NULL,
  `payment_type` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `weekly_hours` varchar(100) DEFAULT NULL,
  `annual_holliday_allowance` varchar(20) DEFAULT NULL,
  `annual_allowance_type` tinyint(4) DEFAULT NULL,
  `actual_holiday_allowance` varchar(20) DEFAULT NULL,
  `actual_holiday_allowance_type` tinyint(4) DEFAULT NULL,
  `remaining_holiday_allowance` varchar(20) DEFAULT NULL,
  `payroll_id` varchar(100) DEFAULT NULL,
  `default_shift` bigint(20) DEFAULT NULL,
  `thumbnail` text,
  `start_date` date DEFAULT NULL,
  `final_date` date DEFAULT NULL,
  `notes` text,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updation_userid` varchar(45) DEFAULT NULL,
  `reset_password` bigint(20) DEFAULT NULL,
  `key` varchar(45) DEFAULT NULL,
  `temp_email` varchar(120) DEFAULT NULL,
  `user_session` bigint(20) DEFAULT NULL,
  `lastlogin_date` datetime DEFAULT NULL,
  `unit_change_date` date DEFAULT NULL,
  `to_unit` bigint(20) DEFAULT NULL,
  `pass_enable` int(11) DEFAULT NULL,
  `app_pass` varchar(45) DEFAULT NULL,
  `user_size_session` bigint(20) DEFAULT NULL,
  `exit_interview` tinyint(4) DEFAULT NULL,
  `exit_reason` text,
  `hr_ID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_1_idx` (`payment_type`),
  KEY `fk_users_2_idx` (`group_id`),
  KEY `fk_users_3_idx` (`designation_id`),
  KEY `fk_users_4_idx` (`default_shift`),
  CONSTRAINT `fk_users_1` FOREIGN KEY (`payment_type`) REFERENCES `master_payment_type` (`id`),
  CONSTRAINT `fk_users_2` FOREIGN KEY (`group_id`) REFERENCES `master_group` (`id`),
  CONSTRAINT `fk_users_3` FOREIGN KEY (`designation_id`) REFERENCES `master_designation` (`id`),
  CONSTRAINT `fk_users_4` FOREIGN KEY (`default_shift`) REFERENCES `master_shift` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'jan.singarajah@smhc.uk.com','9c1f5117ea12459aa66b5aa34b2f31bd',1,1,11,1,'40:00','100.00',2,'100.00',2,'NaN','001',4,'','2001-01-01','2001-01-01','','2019-07-01 15:53:13','2022-12-16 10:54:36','510',2,'H6cvNZCGD8tkdK1n',NULL,NULL,'2023-05-18 09:43:56',NULL,NULL,NULL,NULL,3,0,'',''),(2,'chinchu@neork.com','12920474f3bc1ad9315ff13e2571969b',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 14:26:44','2023-05-17 14:26:44',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'chinchu+1@neork.com','cbcb158a230f4b3a262fafa269b9d13d',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 14:27:18','2023-05-17 14:27:18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'chinchugopi89@gmail.com','28438c2cdc2c208a7a66a1528594d1b1',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 15:56:37','2023-05-17 15:56:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'anoopoxford@gmail.com','74d58150930244dacb535df300675bc9',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 16:09:26','2023-05-17 16:09:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'anoopoxfordg@gmail.com','7ea26e311d896e04275de017759982ee',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 16:14:04','2023-05-17 16:14:04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,'karthika@neork.com','911d8700e9a4a5f53c1b752c0b8984e0',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-17 16:15:29','2023-05-17 16:15:29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,'chinchugopi8998@gmail.com','070b4ed886b0b686d35632b3f6e1af19',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 10:39:32','2023-05-18 10:39:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,'chinchua@neork.com','05020d4665e5ed3277da3fbff39da72b',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 11:56:37','2023-05-18 11:56:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,'chinchuas@neork.com','51e071b732b5c2275259384e71ca837f',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 12:00:17','2023-05-18 12:00:17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,'chinchufg@neork.com','29aacd29210de10b1ec052c186bb6aec',2,14,9,3,'00:00','00.00',2,'00.00',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-05-18 12:03:32','2023-05-18 12:03:32',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,'chinchuneork@gmail.com','450e24cb5717cbf5345274614b10c32f',3,14,9,1,'08:00','222.00',2,'222.00',2,'NaN','123',7,NULL,'2023-05-18','0000-00-00','','2023-05-18 12:07:22','2023-05-18 16:13:12','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_back`
--

DROP TABLE IF EXISTS `users_back`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_back` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(120) NOT NULL,
  `password` char(100) NOT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `designation_id` bigint(20) DEFAULT NULL,
  `payment_type` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `weekly_hours` varchar(100) DEFAULT NULL,
  `annual_holliday_allowance` varchar(20) DEFAULT NULL,
  `annual_allowance_type` tinyint(4) DEFAULT NULL,
  `actual_holiday_allowance` varchar(20) DEFAULT NULL,
  `actual_holiday_allowance_type` tinyint(4) DEFAULT NULL,
  `remaining_holiday_allowance` varchar(20) DEFAULT NULL,
  `payroll_id` varchar(100) DEFAULT NULL,
  `default_shift` bigint(20) DEFAULT NULL,
  `thumbnail` text,
  `start_date` date DEFAULT NULL,
  `final_date` date DEFAULT NULL,
  `notes` text,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updation_userid` varchar(45) DEFAULT NULL,
  `reset_password` bigint(20) DEFAULT NULL,
  `key` varchar(45) DEFAULT NULL,
  `temp_email` varchar(120) DEFAULT NULL,
  `user_session` bigint(20) DEFAULT NULL,
  `lastlogin_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_back`
--

LOCK TABLES `users_back` WRITE;
/*!40000 ALTER TABLE `users_back` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_back` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workschedule`
--

DROP TABLE IF EXISTS `workschedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workschedule` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `sunday` tinyint(4) DEFAULT NULL,
  `monday` tinyint(4) DEFAULT NULL,
  `tuesday` tinyint(4) DEFAULT NULL,
  `wednesday` tinyint(4) DEFAULT NULL,
  `thursday` tinyint(4) DEFAULT NULL,
  `friday` tinyint(4) DEFAULT NULL,
  `saturdy` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_workschedule_1` (`user_id`),
  CONSTRAINT `fk_workschedule_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workschedule`
--

LOCK TABLES `workschedule` WRITE;
/*!40000 ALTER TABLE `workschedule` DISABLE KEYS */;
INSERT INTO `workschedule` VALUES (1,2,0,0,0,0,0,0,0,'2023-05-17 14:26:44',NULL,NULL),(2,3,0,0,0,0,0,0,0,'2023-05-17 14:27:18',NULL,NULL),(3,4,0,0,0,0,0,0,0,'2023-05-17 15:56:37',NULL,NULL),(4,5,0,0,0,0,0,0,0,'2023-05-17 16:09:26',NULL,NULL),(5,6,0,0,0,0,0,0,0,'2023-05-17 16:14:04',NULL,NULL),(6,7,0,0,0,0,0,0,0,'2023-05-17 16:15:29',NULL,NULL),(7,8,0,0,0,0,0,0,0,'2023-05-18 10:39:32',NULL,NULL),(8,9,0,0,0,0,0,0,0,'2023-05-18 11:56:37',NULL,NULL),(9,10,0,0,0,0,0,0,0,'2023-05-18 12:00:17',NULL,NULL),(10,11,0,0,0,0,0,0,0,'2023-05-18 12:03:32',NULL,NULL),(11,12,1,0,0,0,0,0,1,'2023-05-18 12:57:55','2023-05-18 12:57:55',1);
/*!40000 ALTER TABLE `workschedule` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-18 17:25:26
