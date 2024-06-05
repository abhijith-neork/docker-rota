
/*21.08.19*/

ALTER TABLE `rotacloud`.`designation_rates` 
ADD COLUMN `normal_rates` BIGINT(20) NULL AFTER `designation_id`;

ALTER TABLE `rotacloud`.`designation_rates` 
CHANGE COLUMN `maternity-rate` `maternity_rate` DECIMAL(5,2) NULL DEFAULT NULL ;


/*26.08.19*/
ALTER TABLE `rotacloud`.`unit` 
ADD COLUMN `unit_shortname` VARCHAR(20) NULL AFTER `unit_type`;



ALTER TABLE `rotacloud`.`master_shift` 
ADD COLUMN `shift_category` TINYINT(4) NULL AFTER `end_time`,
ADD COLUMN `targeted_hours` VARCHAR(10) NULL AFTER `shift_category`,
ADD COLUMN `unpaid_break_hours` VARCHAR(10) NULL AFTER `targeted_hours`;


ALTER TABLE `rotacloud`.`notes` 
ADD COLUMN `notification_type` TINYINT(4) NULL AFTER `comment`;



CREATE TABLE `rotacloud`.`user_rates` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `day_rate` DECIMAL(5,2) NULL,
  `night_rate` DECIMAL(5,2) NULL,
  `day_saturday_rate` DECIMAL(5,2) NULL,
  `day_sunday_rate` DECIMAL(5,2) NULL,
  `weekend_night_rate` DECIMAL(5,2) NULL,
  `updation_date` DATETIME NULL,
  `updated_userid` BIGINT(20) NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud`.`user_rates` 
ADD INDEX `fk_new_table_1_idx` (`user_id` ASC);
ALTER TABLE `rotacloud`.`user_rates` 
ADD CONSTRAINT `fk_new_table_1`
  FOREIGN KEY (`user_id`)
  REFERENCES `rotacloud`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `rotacloud`.`master_designation` 
ADD COLUMN `part_number` TINYINT(4) NULL DEFAULT NULL AFTER `description`;


INSERT INTO `rotacloud`.`master_shift` (`id`, `shift_name`, `start_time`, `status`, `hours`, `end_time`, `shift_shortcut`, `creation_date`) VALUES ('4', 'Holliday', '09:00:00', '0', '0', '09:00:00', 'HD', '2019-07-31 01:14:06');


INSERT INTO `rotacloud`.`master_shift` (`id`, `shift_name`, `start_time`, `status`, `hours`, `end_time`, `shift_shortcut`, `creation_date`) VALUES ('5', 'Training', '09:00:00', '0', '0', '09:00:00', 'T', '2019-07-31 01:14:06');


ALTER TABLE `rotacloud`.`holliday` 
CHANGE COLUMN `days` `days` VARCHAR(20) NULL DEFAULT NULL ;
/*06.09.19*/
ALTER TABLE `rotacloud`.`unit` 
ADD COLUMN `parent_unit` BIGINT(20) NULL AFTER `unit_type`;
update rotacloud.unit set parent_unit=0 where id >0;
/*06.09.19*/
ALTER TABLE `rotacloud`.`master_designation` 
ADD COLUMN `designation_code` VARCHAR(5) NULL AFTER `part_number`;

/*06.09.19*/

ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `actual_holiday_allowance` BIGINT(20) NULL AFTER `annual_allowance_type`,
ADD COLUMN `actual_holiday_allowance_type` TINYINT(4) NULL AFTER `actual_holiday_allowance`;

/*13/9/2019*/

CREATE TABLE `rotacloud`.`staff_rota` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `unit_id` BIGINT(20) NOT NULL,
  `created_date` DATETIME NULL DEFAULT NULL,
  `updated_date` DATETIME NULL DEFAULT NULL,
  `user_id` BIGINT(20) NULL DEFAULT NULL,
  `published` BIGINT(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `rotacloud`.`staffrota_schedule` (
  `id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `shift_id` BIGINT(20) NULL DEFAULT 0,
  `shift_hours` BIGINT(20) NULL DEFAULT NULL,
  `unit_id` BIGINT(20) NOT NULL,
  `rota_id` BIGINT(20) NOT NULL,
  `date` DATE NULL DEFAULT NULL,
  `status` TINYINT(4) NULL DEFAULT NULL,
  `creation_date` DATETIME NULL DEFAULT NULL,
  `updation_date` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`));


/*16.09.19*/

CREATE TABLE `rotacloud`.`rota_settings` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `day_shift_min` BIGINT(20) NULL,
  `day_shift_max` BIGINT(20) NULL,
  `night_shift_min` BIGINT(20) NULL,
  `night_shift_max` BIGINT(20) NULL,
  `num_patients` BIGINT(20) NULL,
  `one_one_patients` BIGINT(20) NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud`.`rota` 
ADD COLUMN `rota_settings` BIGINT(20) NOT NULL AFTER `id`;


ALTER TABLE `rotacloud`.`staffrota_schedule` 
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT ;

/*18.09.19*/
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('28', 'Admin.Report.Add', 'Allow to manage report', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30', '1');

ALTER TABLE `rotacloud`.`rota_settings` 
ADD COLUMN `creation_date` DATETIME NULL AFTER `one_one_patients`;


CREATE TABLE `rotacloud`.`sms_log` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `mobile_number` VARCHAR(45) NOT NULL,
  `status_code` VARCHAR(45) NOT NULL,
  `message_id` VARCHAR(45) NOT NULL,
  `date` DATETIME NOT NULL,
  `sender_id` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `day` VARCHAR(45) NULL AFTER `updated_userid`;

ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `shift_category` TINYINT(4) NULL AFTER `day`;

/*24/09/2019*/

CREATE TABLE `rotacloud`.`history_staff_address` (
  `id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NULL,
  `address` TEXT NULL,
  `kin_name` VARCHAR(100) NULL,
  `kin_address` TEXT NULL,
  `kin_phonenumber` VARCHAR(45) NULL,
  `changed_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_history_staff_address_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_history_staff_address_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `rotacloud`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `rotacloud`.`history_staff_address` 
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT ;



ALTER TABLE `rotacloud`.`personal_details` 
ADD COLUMN `kin_name` VARCHAR(45) NULL AFTER `postcode`,
ADD COLUMN `kin_phone` VARCHAR(45) NULL AFTER `kin_name`,
ADD COLUMN `kin_address` VARCHAR(40) NULL AFTER `kin_phone`;

CREATE TABLE `rotacloud`.`training_titles` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud`.`rota_settings` 
ADD COLUMN `nurse_count` BIGINT(20) NULL AFTER `one_one_patients`;

/*25/09/2019*/

ALTER TABLE `rotacloud`.`rota_settings` 
CHANGE COLUMN `nurse_count` `nurse_day_count` BIGINT(20) NULL DEFAULT NULL ,
ADD COLUMN `nurse_night_count` BIGINT(20) NULL AFTER `nurse_day_count`;


ALTER TABLE `rotacloud`.`personal_details` 
ADD COLUMN `gender` VARCHAR(45) NULL AFTER `lname`;

CREATE TABLE `unit_designation_maxleave` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) NOT NULL,
  `designation_id` bigint(20) NOT NULL,
  `max_leave` int(20) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_userid` bigint(20) DEFAULT NULL,
  `updation_date` datetime DEFAULT NULL,
  `updated_userid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_unit_designation_maxleave_1_idx` (`unit_id`),
  KEY `fk_unit_designation_maxleave_2_idx` (`designation_id`),
  CONSTRAINT `fk_unit_designation_maxleave_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_unit_designation_maxleave_2` FOREIGN KEY (`designation_id`) REFERENCES `master_designation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*26/09/2019*/

ALTER TABLE `rotacloud`.`master_training` 
CHANGE COLUMN `place` `place` VARCHAR(100) NOT NULL ;

ALTER TABLE `rotacloud`.`unit_designation_maxleave` 
DROP COLUMN `updated_userid`,
DROP COLUMN `updation_date`;

/*27/09/2019*/

CREATE TABLE `rotacloud`.`history_user_rates` (
  `id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `day_rate` DECIMAL(5,2) NULL,
  `night_rate` DECIMAL(5,2) NULL,
  `day_saturday_rate` DECIMAL(5,2) NULL,
  `day_sunday_rate` DECIMAL(5,2) NULL,
  `weekend_night_rate` DECIMAL(5,2) NULL,
  `updation_date` DATETIME NULL,
  `updated_userid` BIGINT(20) NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud`.`history_user_rates` 
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT ;

/*30/09/2019*/

INSERT INTO `rotacloud`.`master_shift` (`shift_name`, `start_time`, `status`, `hours`, `end_time`, `shift_category`, `targeted_hours`, `unpaid_break_hours`, `shift_shortcut`, `creation_date`) VALUES ('Sick', '09:00:00', '0', '0', '09:00:00', '0', '0', '0', 'S', '2019-07-31 01:14:06');
INSERT INTO `rotacloud`.`master_shift` (`shift_name`, `start_time`, `status`, `hours`, `end_time`, `shift_category`, `targeted_hours`, `unpaid_break_hours`, `shift_shortcut`, `creation_date`) VALUES ('Absent Without Leave', '09:00:00', '0', '0', '09:00:00', '0', '0', '0', 'AWOL', '2019-07-31 01:14:06');




/*01/09/2019*/

UPDATE `rotacloud`.`master_shift` SET `shift_shortcut` = 'SL' WHERE (`id` = '6');


CREATE TABLE `rotacloud`.`leave_log` (
  `id` BIGINT(20) NOT NULL,
  `shift_id` BIGINT(20) NOT NULL,
  `unit_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `date` DATE NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud`.`leave_log` 
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT ;

/*03/09/2019*/

INSERT INTO `rotacloud`.`master_shift` (`id`, `shift_name`, `start_time`, `status`, `hours`, `end_time`, `shift_category`, `targeted_hours`, `unpaid_break_hours`, `shift_shortcut`, `creation_date`) VALUES ('8', 'Early Late', '09:00:00', '1', '12', '09:00:00', '1', '12', '2', 'EL', '2019-07-31 01:14:06');

ALTER TABLE `rotacloud`.`leave_log` 
ADD COLUMN `shift_name` VARCHAR(45) NOT NULL AFTER `created_date`;

/*02/10/2019 - Siva */
ALTER TABLE `rotacloud`.`time_log` 
CHANGE COLUMN `user_id` `user_id` BIGINT(20) NULL ,
ADD COLUMN `payroll_id` VARCHAR(45) NOT NULL AFTER `id`;

/*03/10/2019*/
ALTER TABLE `rotacloud`.`activity_log` 
DROP FOREIGN KEY `fk_activity_log_1`;
ALTER TABLE `rotacloud`.`activity_log` 
DROP INDEX `fk_activity_log_1_idk` ;

/*04/10/2019*/
INSERT INTO `rotacloud`.`master_payment_type` (`id`, `payment_type`, `description`, `status`, `created_date`, `updated_date`, `updated_userid`) VALUES ('1', 'Agency Payment', 'Agency Payment', '1', '2019-07-01 15:52:01', '2019-07-01 15:52:01', '1');

/*07/10/2019*/
ALTER TABLE `rotacloud`.`rota` 
ADD COLUMN `month` BIGINT(20) NULL DEFAULT NULL AFTER `published`,
ADD COLUMN `year` BIGINT(20) NULL DEFAULT NULL AFTER `month`;

INSERT INTO `rotacloud`.`master_designation` (`id`, `designation_name`, `status`, `description`, `part_number`, `designation_code`, `creation_date`, `updation_date`) VALUES ('4', 'Carers', '1', 'Carers', '1', 'CR', '2019-07-01 15:52:40', '2019-07-03 07:02:59');

/*08/10/2019*/

ALTER TABLE `rotacloud`.`master_shift` 
ADD COLUMN `part_number` TINYINT(4) NULL DEFAULT NULL AFTER `shift_category`;


ALTER TABLE `rotacloud`.`time_log` 
CHANGE COLUMN `device_id` `device_id` VARCHAR(250) NOT NULL ;

/*server updated so far on Oct 8*/

/*09/10/2019*/

ALTER TABLE `rotacloud`.`unit` 
ADD COLUMN `number_of_beds` BIGINT(20) NULL AFTER `max_patients`;

/*10/10/2019*/

ALTER TABLE `rotacloud`.`master_designation` 
ADD COLUMN `group` BIGINT(20) NULL AFTER `part_number`;
LOCK TABLES `master_shift` WRITE;
UPDATE `rotacloud`.`master_designation` SET `group` = '1' WHERE (`id` = '1');
UPDATE `rotacloud`.`master_designation` SET `group` = '1' WHERE (`id` = '2');
UPDATE `rotacloud`.`master_designation` SET `group` = '1' WHERE (`id` = '4');
UPDATE `rotacloud`.`master_designation` SET `group` = '1' WHERE (`id` = '24');
UPDATE `rotacloud`.`master_designation` SET `group` = '2' WHERE (`id` = '6');
UPDATE `rotacloud`.`master_designation` SET `group` = '2' WHERE (`id` = '7');
UPDATE `rotacloud`.`master_designation` SET `group` = '3' WHERE (`id` = '3');
UPDATE `rotacloud`.`master_designation` SET `group` = '3' WHERE (`id` = '14');
UPDATE `rotacloud`.`master_designation` SET `group` = '3' WHERE (`id` = '15');
UPDATE `rotacloud`.`master_designation` SET `group` = '3' WHERE (`id` = '16');
UPDATE `rotacloud`.`master_designation` SET `group` = '3' WHERE (`id` = '19');
UPDATE `rotacloud`.`master_designation` SET `group` = '4' WHERE (`id` = '17');
UPDATE `rotacloud`.`master_designation` SET `group` = '4' WHERE (`id` = '18');
UPDATE `rotacloud`.`master_designation` SET `group` = '5' WHERE (`id` = '20');
UPDATE `rotacloud`.`master_designation` SET `group` = '5' WHERE (`id` = '21');
UPDATE `rotacloud`.`master_designation` SET `group` = '5' WHERE (`id` = '22');
UPDATE `rotacloud`.`master_designation` SET `group` = '5' WHERE (`id` = '23');
UPDATE `rotacloud`.`master_designation` SET `group` = '5' WHERE (`id` = '25');
UPDATE `rotacloud`.`master_designation` SET `group` = '2' WHERE (`id` = '13');
UPDATE `rotacloud`.`master_designation` SET `group` = '6' WHERE (`id` = '10');
UPDATE `rotacloud`.`master_designation` SET `group` = '6' WHERE (`id` = '11');
UPDATE `rotacloud`.`master_designation` SET `group` = '6' WHERE (`id` = '12');
UPDATE `rotacloud`.`master_designation` SET `group` = '7' WHERE (`id` = '5');
UPDATE `rotacloud`.`master_designation` SET `group` = '7' WHERE (`id` = '8');
UPDATE `rotacloud`.`master_designation` SET `group` = '7' WHERE (`id` = '9');



UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '5');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '6');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '7');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '8');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '9');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '10');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '11');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '12');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '13');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '14');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '15');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '16');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '17');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '18');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '19');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '1' WHERE (`id` = '20');
UPDATE `rotacloud`.`master_shift` SET `part_number` = '0' WHERE (`id` = '21');
UNLOCK TABLES;

ALTER TABLE `rotacloud`.`master_designation` 
ADD COLUMN `availability_requests` BIGINT(20) NULL AFTER `part_number`;



UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '4' WHERE (`id` = '14');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '4' WHERE (`id` = '15');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '4' WHERE (`id` = '16');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '4' WHERE (`id` = '3');


INSERT INTO `rotacloud`.`master_shift` (`id`, `shift_name`, `shift_shortcut`, `start_time`, `end_time`, `shift_category`, `part_number`, `status`) VALUES ('22', 'Available Shift', 'AL', '00:00:00', '00:00:00', '0', '0', '1');


UPDATE `rotacloud_V1`.`master_shift` SET `status` = '1' WHERE (`id` = '22');

/*14/10/2019*/

INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('29', 'Admin.History.Add', 'Allow to manage history', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30', '1');
INSERT INTO `rotacloud`.`group_permissions` (`id`, `group_id`, `permission_id`, `status`) VALUES ('29', '1', '29', '1');


ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `from_userid` BIGINT(20) NULL DEFAULT NULL AFTER `shift_category`,
ADD COLUMN `from_rotaid` BIGINT(20) NULL DEFAULT NULL AFTER `from_userid`;



UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '1');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '2');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '4');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '5');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '6');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '7');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '8');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '9');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '10');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '11');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '12');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '13');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '17');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '18');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '19');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '20');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '21');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '22');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '23');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '24');
UPDATE `rotacloud`.`master_designation` SET `availability_requests` = '2' WHERE (`id` = '25');

/*15/10/2019*/

CREATE TABLE `rotacloud`.`agency_staffs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `agency_staffid` BIGINT(20) NOT NULL,
  `date` DATE NOT NULL,
  `unit_id` BIGINT(20) NOT NULL,
  `shift_id` BIGINT(20) NOT NULL,
  `created_date` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));

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
);

INSERT INTO `available_master_shift` VALUES 
(1,'Available 8-4','AVL-8-4','08:00:00','16:00:00',1,1,'07:30','0','2019-07-31 01:14:06',5),
(2,'Available 6-1.30','AVL-6-1.30','06:00:00','13:00:00',1,1,'06:30','0','2019-07-31 01:14:06',6),
(3,'Available 6-6','AVL-6-6','06:00:00','18:00:00',1,1,'11:30','0','2019-07-31 01:14:06',7),
(4,'Available 7.30-3.30','AVL-7.30-3.30','07:30:00','15:30:00',1,1,'07:15','0','2019-07-31 01:14:06',8),
(5,'Available 8-5','AVL-8-5','08:00:00','17:00:00',1,1,'08:30','0','2019-07-31 01:14:06',9),
(6,'Available 9-4.30','AVL-9-4.30','09:00:00','16:30:00',1,1,'07:00','0','2019-07-31 01:14:06',10),
(7,'Available 9-5','AVL-9-5','09:00:00','17:00:00',1,1,'07:30','0','2019-07-31 01:14:06',11),
(8,'Available 6-1.30','AVL-6-1.30','06:00:00','13:00:00',1,1,'06:30','0','2019-07-31 01:14:06',12),
(9,'Available Early','AVL-E','07:15:00','13:45:00',1,1,'06:30','0','2019-07-31 01:14:06',13),
(10,'Available Late','AVL-L','13:15:00','19:45:00',1,1,'06:30','0','2019-07-31 01:14:06',14),
(11,'Available LongDay','AVL-EL','07:15:00','19:45:00',1,1,'11:30','0','2019-07-31 01:14:06',15),
(12,'Available Night','AVL-N','19:30:00','07:30:00',2,1,'12:00','0','2019-07-31 01:14:06',16),
(13,'Available OliverStreet','AVL-O12','07:15:00','19:45:00',1,1,'11:30','0','2019-07-31 01:14:06',17),
(14,'Available 9-2.30','AVL-9-2.30','09:00:00','14:00:00',1,1,'05:00','0','2019-07-31 01:14:06',18),
(15,'Available 10-4','AVL-10-4','10:00:00','16:00:00',1,1,'05:30','0','2019-07-31 01:14:06',19),
(16,'Available 7.30-6','AVL-7.30-6','07:30:00','18:00:00',1,1,'10:00','0','2019-07-31 01:14:06',20);

/*17/10/2019*/

ALTER TABLE `rotacloud`.`master_designation` 
ADD COLUMN `sort_order` TINYINT(4) NULL AFTER `updated_userid`;

UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='1';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='3' WHERE `id`='3';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='1' WHERE `id`='4';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='4' WHERE `id`='5';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='4' WHERE `id`='10';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='3' WHERE `id`='19';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='3' WHERE `id`='16';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='3' WHERE `id`='15';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='3' WHERE `id`='14';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='13';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='4' WHERE `id`='11';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='6';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='24';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='2';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='1' WHERE `id`='7';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='4' WHERE `id`='8';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='4' WHERE `id`='9';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='4' WHERE `id`='12';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='3' WHERE `id`='17';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='3' WHERE `id`='18';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='20';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='21';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='22';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='23';
UPDATE `rotacloud`.`master_designation` SET `sort_order`='2' WHERE `id`='25';

/*19/10/2019*/

ALTER TABLE `rotacloud`.`leave_log` 
ADD COLUMN `shift_name` VARCHAR(45) NOT NULL AFTER `user_id`,
ADD COLUMN `leave_type` VARCHAR(45) NOT NULL AFTER `shift_name`;


CREATE TABLE `rotacloud`.`available_requests` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `shift_id` BIGINT(20) NOT NULL,
  `unit_id` BIGINT(20) NOT NULL,
  `date` DATE NOT NULL,
  `created_date` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `rotacloud`.`available_requested_users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `avialable_request_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `status` TINYINT(4) NOT NULL,
  `created_date` DATETIME NOT NULL,
  `updated_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`));


ALTER TABLE `rotacloud`.`available_requests` 
ADD COLUMN `to_unitid` BIGINT(20) NOT NULL AFTER `shift_id`,
CHANGE COLUMN `unit_id` `from_unitid` BIGINT(20) NOT NULL ;

/*server updated till this on oct 20/*

/*21/10/2019*/

ALTER TABLE `rotacloud`.`personal_details` 
CHANGE COLUMN `status` `status` TINYINT(4) NULL DEFAULT NULL AFTER `country`,
CHANGE COLUMN `address1` `address1` VARCHAR(100) NULL DEFAULT NULL ,
CHANGE COLUMN `address2` `address2` VARCHAR(100) NULL DEFAULT NULL ,
ADD COLUMN `telephone` VARCHAR(45) NULL AFTER `mobile_number`,
ADD COLUMN `NINnumbers` VARCHAR(45) NULL AFTER `profile_image`,
ADD COLUMN `address3` VARCHAR(100) NULL AFTER `address2`,
ADD COLUMN `address4` VARCHAR(100) NULL AFTER `address3`,
ADD COLUMN `kin_address1` VARCHAR(100) NULL AFTER `kin_address`,
ADD COLUMN `kin_address2` VARCHAR(100) NULL AFTER `kin_address1`,
ADD COLUMN `kin_address3` VARCHAR(100) NULL AFTER `kin_address2`,
ADD COLUMN `kin_postcode` VARCHAR(20) NULL AFTER `kin_address3`,
ADD COLUMN `kin_relationship` VARCHAR(45) NULL AFTER `kin_postcode`,
ADD COLUMN `kin_email` VARCHAR(45) NULL AFTER `kin_relationship`,
ADD COLUMN `kin_telephone` VARCHAR(45) NULL AFTER `kin_email`,
ADD COLUMN `bank_account` BIGINT(20) NULL AFTER `kin_telephone`,
ADD COLUMN `bank_sortcode` BIGINT(20) NULL AFTER `bank_account`,
ADD COLUMN `jobcode` VARCHAR(45) NULL AFTER `bank_sortcode`,
ADD COLUMN `title` VARCHAR(100) NULL AFTER `updated_userid`,
ADD COLUMN `taxcode` VARCHAR(20) NULL AFTER `title`;

ALTER TABLE `rotacloud`.`personal_details` 
CHANGE COLUMN `kin_address` `kin_address` VARCHAR(100) NULL DEFAULT NULL ;
ALTER TABLE `rotacloud`.`personal_details` 
CHANGE COLUMN `postcode` `postcode` VARCHAR(20) NULL DEFAULT NULL ;
UPDATE `rotacloud`.`master_designation` SET `designation_name`='Registered Mental Health Nurse' WHERE `id`='15';
INSERT INTO `rotacloud`.`master_designation` (`id`, `designation_name`, `status`, `description`, `part_number`, `availability_requests`, `group`, `designation_code`, `sort_order`) VALUES ('26', 'Staf', '1', 'Staf', '0', '2', '2', 'STAF', '3');
INSERT INTO `rotacloud`.`master_designation` (`designation_name`, `status`, `description`, `part_number`, `availability_requests`, `group`, `designation_code`, `creation_date`, `updation_date`, `updated_userid`, `sort_order`) VALUES ('Copr', '1', 'Copr', '0', '2', '2', 'COPR', '2019-10-09 10:59:27', '2019-10-09 10:59:27', '1', '3');
UPDATE `rotacloud`.`master_designation` SET `creation_date`='2019-10-09 10:59:27', `updation_date`='2019-10-09 10:59:27', `updated_userid`='1' WHERE `id`='26';


ALTER TABLE `rotacloud`.`personal_details` 
CHANGE COLUMN `postcode` `postcode` VARCHAR(100) NULL DEFAULT NULL ,
CHANGE COLUMN `bank_account` `bank_account` VARCHAR(100) NULL DEFAULT NULL ,
CHANGE COLUMN `bank_sortcode` `bank_sortcode` VARCHAR(100) NULL DEFAULT NULL ;

/*server updated */
/*22/10/2019*/

INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('30', 'Admin.Dashboard.Add', 'Allow to manage dashboard', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30', '1');

INSERT INTO `rotacloud`.`group_permissions` (`id`, `group_id`, `permission_id`, `status`) VALUES ('30', '1', '30', '1');


/*23/10/2019*/
ALTER TABLE `rotacloud`.`personal_details` 
ADD COLUMN `kin_telephone_work` VARCHAR(45) NULL AFTER `kin_telephone`;

ALTER TABLE `rotacloud`.`unit_designation_maxleave` 
ADD COLUMN `max_leave_hour` INT(20) NULL DEFAULT NULL AFTER `max_leave`;

UPDATE `rotacloud`.`unit_designation_maxleave` SET `max_leave_hour`='16' where id!=0;










