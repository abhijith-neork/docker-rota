/* 22-11-2019 */

ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `additional_hours` VARCHAR(45) NULL AFTER `shift_hours`,
ADD COLUMN `comment` TEXT NULL AFTER `additional_hours`;

/* 25-11-2019 */

CREATE TABLE `rotacloud`.`bank_holiday` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `date` DATE NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `status` TINYINT(4) NULL
  PRIMARY KEY (`id`)); 

INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('Admin.Holiday.Edit', 'Allow to edit holiday', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('Admin.Holiday.Add', 'Allow to manage holiday', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30', '1');

INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '32', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '31', '1');
 
/* 26-11-2019 */

ALTER TABLE `rotacloud`.`master_shift` 
ADD COLUMN `color_unit` VARCHAR(20) NULL AFTER `part_number`;
 

/* 26-11-2019 */
ALTER TABLE `rotacloud`.`available_requests` 
ADD COLUMN `request_count` TINYINT(4) NULL DEFAULT NULL AFTER `comments`; 


/* 27-11-2019 */
ALTER TABLE `rotacloud`.`daily_senses` 
ADD COLUMN `date` DATE NULL AFTER `user_id`;

/* 28-11-2019 */
ALTER TABLE `rotacloud`.`daily_senses` 
ADD COLUMN `status` TINYINT(4) NULL AFTER `created_userid`;

ALTER TABLE `rotacloud`.`users` 
CHANGE COLUMN `annual_holliday_allowance` `annual_holliday_allowance` VARCHAR(20) NULL DEFAULT NULL ,
CHANGE COLUMN `actual_holiday_allowance` `actual_holiday_allowance` VARCHAR(20) NULL DEFAULT NULL ;

INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('35', 'Admin.Dailycensus.Add', 'Allow to manage census', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('36', 'Admin.Dailycensus.Edit', 'Allow to edit census', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30', '1');

INSERT INTO `rotacloud`.`group_permissions` (`id`, `group_id`, `permission_id`, `status`) VALUES ('136', '1', '35', '1');
INSERT INTO `rotacloud`.`group_permissions` (`id`, `group_id`, `permission_id`, `status`) VALUES ('137', '1', '36', '1');

/* 02-12-2019 */

ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `lastlogin_date` DATETIME NULL AFTER `user_session`;

/* 05-12-2019 */

ALTER TABLE `rotacloud`.`company` 
ADD COLUMN `shift_gap` VARCHAR(100) NULL AFTER `updated_userid`;

/* 23-12-2019 */
ALTER TABLE `rotacloud`.`company` 
ADD COLUMN `from_email` VARCHAR(100) NULL AFTER `shift_gap`;

/* 26-12-2019 */

ALTER TABLE `rotacloud`.`company` 
ADD COLUMN `late_notify` BIGINT(10) NULL AFTER `from_email`;

/* 27-12-2019 */
CREATE TABLE `rotacloud`.`rota_log` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `rota_id` BIGINT(20) NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `user_id` BIGINT(20) NULL,
  `rota_details` VARCHAR(45) NULL,
  `updated_by` BIGINT(20) NULL,
  `updated_datetime` DATETIME NULL,
  PRIMARY KEY (`id`));

/* 30-12-2019 */
CREATE TABLE `rotacloud`.`User_thumb` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `thumbnail` TEXT NULL,
  `thumbnail1` TEXT NULL,
  `thumbnail2` TEXT NULL,
  `created_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  `entrolled_from` BIGINT(20) NULL,
  PRIMARY KEY (`id`));

/* 31-12-2019 */
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
  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `rotacloud`.`rota_log` 
ADD COLUMN `type` VARCHAR(45) NULL AFTER `updated_datetime`;

ALTER TABLE `rotacloud`.`rota_log` 
CHANGE COLUMN `rota_details` `rota_details` TEXT NULL DEFAULT NULL ;

ALTER TABLE `rotacloud`.`rota_log` 
ADD COLUMN `unit_id` BIGINT(20) NULL AFTER `type`;

/* 01-01-2020 */

update `rotacloud`.`users` set annual_allowance_type=2  where id!=1;
update `rotacloud`.`users` set actual_holiday_allowance_type=2  where id!=1;

/* 02-01-2020 */

ALTER TABLE `rotacloud`.`training_titles` 
ADD COLUMN `description` TEXT NULL AFTER `title`;

/* 03-01-2020 */

CREATE TABLE `rotacloud`.`User_Unit_Designation_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Previous_unit` BIGINT(20) NULL,
  `Current_unit` BIGINT(20) NULL,
  `Previous_designation` BIGINT(20) NULL,
  `Current_designation` BIGINT(20) NULL,
  `Updation_date` DATETIME NULL,
  `Updated_by` BIGINT(20) NULL,
  PRIMARY KEY (`id`));
ALTER TABLE `rotacloud`.`User_Unit_Designation_history` 
ADD COLUMN `Status` TINYINT(4) NULL AFTER `Updated_by`;


ALTER TABLE `rotacloud`.`User_Unit_Designation_history` 
ADD COLUMN `User_id` BIGINT(20) NULL AFTER `id`,
ADD INDEX `fk_User_Unit_Designation_history_1_idx` (`User_id` ASC);
ALTER TABLE `rotacloud`.`User_Unit_Designation_history` 
ADD CONSTRAINT `fk_User_Unit_Designation_history_1`
  FOREIGN KEY (`User_id`)
  REFERENCES `rotacloud`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

/* 15-01-2020 */


ALTER TABLE `rotacloud`.`master_shift` 
ADD COLUMN `shift_type` FLOAT NULL DEFAULT NULL AFTER `shift_category`;

/* 23-01-2020 */

ALTER TABLE `rota_cloud`.`users` 
ADD COLUMN `remaining_holiday_allowance` VARCHAR(20) NULL AFTER `actual_holiday_allowance_type`;

/* 29-01-2020 */


ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `request_id` BIGINT(20) NULL DEFAULT NULL AFTER `from_rotaid`;

/* 03-02-2020 */

CREATE TABLE `rotacloud_22`.`holiday_applied` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `year` VARCHAR(100) NOT NULL,
  `holiday_id` BIGINT(20) NOT NULL,
  `hours` VARCHAR(100) NULL,
  `creation_date` DATETIME NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud_22`.`holiday_applied` 
ADD INDEX `fk_holiday_applied_1_idx` (`user_id` ASC),
ADD INDEX `fk_holiday_applied_2_idx` (`holiday_id` ASC);
ALTER TABLE `rotacloud_22`.`holiday_applied` 
ADD CONSTRAINT `fk_holiday_applied_1`
  FOREIGN KEY (`user_id`)
  REFERENCES `rotacloud_22`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_holiday_applied_2`
  FOREIGN KEY (`holiday_id`)
  REFERENCES `rotacloud_22`.`holliday` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

CREATE TABLE `rotacloud_22`.`holiday_days` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `holiday_id` BIGINT(20) NOT NULL,
  `date` DATETIME NULL,
  `hour` VARCHAR(100) NULL,
  PRIMARY KEY (`id`));

/* 04-02-2020 */

ALTER TABLE `rotacloud_22`.`holiday_applied` 
ADD COLUMN `calculated_hours` VARCHAR(100) NULL AFTER `hours`;

ALTER TABLE `rotacloud_22`.`holiday_days` 
ADD COLUMN `shift_id` BIGINT(20) NULL AFTER `holiday_id`;

ALTER TABLE `rotacloud_22`.`holiday_days` 
CHANGE COLUMN `date` `date` DATE NULL DEFAULT NULL ;


/* 21-02-2020 */
CREATE TABLE `rotacloud`.`payroll_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `comment` TEXT NOT NULL,
  `creation_date` DATETIME NULL,
  `created_by` VARCHAR(45) NULL,
  PRIMARY KEY (`id`));
ALTER TABLE `rotacloud`.`payroll_comment` 
ADD COLUMN `date` DATE NOT NULL AFTER `user_id`;

ALTER TABLE `rotacloud`.`payroll_comment` 
CHANGE COLUMN `date` `month` INT NOT NULL ,
ADD COLUMN `year` INT NULL AFTER `month`;


/* 26-02-2020 */
ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `designation_id` VARCHAR(45) NULL AFTER `day`;

/* 27-04-2020*/

 INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`) VALUES ('Admin.addresshistory.View', 'Allow to view address change history', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30');
INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`) VALUES ('Admin.userunithistory.View', 'Allow to view user unit change history', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30');
INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`) VALUES ('Admin.designationhistory.View', 'Allow to view designation change history', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30');
INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`) VALUES ('Admin.userrateshistory.View', 'Allow to view user rates change history', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30');
INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`) VALUES ('Admin.rotaupdatehistory.View', 'Allow to view rota update history', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30');

 INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '73', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '74', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '75', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '76', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '77', '1');


/* 27-04-2020*/


UPDATE `rotacloud`.`master_designation` SET `sort_order` = '1' WHERE (`id` = '13');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '2' WHERE (`id` = '6');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '4' WHERE (`id` = '14');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '5' WHERE (`id` = '16');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '6' WHERE (`id` = '43');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '7' WHERE (`id` = '41');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '8' WHERE (`id` = '42');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '9' WHERE (`id` = '11');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '10' WHERE (`id` = '10');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '11' WHERE (`id` = '45');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '12' WHERE (`id` = '44');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '13' WHERE (`id` = '17');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '14' WHERE (`id` = '18');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '15' WHERE (`id` = '4');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '15' WHERE (`id` = '1');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '15' WHERE (`id` = '24');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '15' WHERE (`id` = '34');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '16' WHERE (`id` = '8');

UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '2');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '3');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '5');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '7');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '9');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '12');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '15');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '19');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '20');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '21');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '22');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '23');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '25');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '26');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '27');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '28');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '29');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '30');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '31');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '32');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '33');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '35');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '36');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '37');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '38');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '39');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '40');


ALTER TABLE `rotacloud`.`master_designation` 
ADD COLUMN `nurse_count` INT NULL AFTER `sort_order`;


UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '41');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '3');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '6');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '14');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '15');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '16');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '17');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '18');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '19');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '26');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '27');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '29');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '33');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '36');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '37');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '38');


UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '1');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '2');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '4');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '5');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '7');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '8');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '9');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '10');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '11');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '12');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '13');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '20');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '21');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '22');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '23');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '24');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '25');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '28');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '30');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '31');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '32');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '34');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '35');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '39');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '40');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '42');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '43');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '44');
UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '0' WHERE (`id` = '45');

/* 08-05-2020*/

ALTER TABLE `rotacloud`.`holliday` 
ADD COLUMN `remaining_leave` VARCHAR(20) NULL AFTER `days`;

/* 11-05-2020*/
ALTER TABLE `rotacloud`.`time_log` 
ADD COLUMN `user_unit` BIGINT(20) NULL AFTER `unit_id`;
 
/* 13-05-2020*/

INSERT INTO `rotacloud`.`master_shift` (`shift_name`, `shift_shortcut`, `start_time`, `end_time`, `shift_category`, `shift_type`, `part_number`, `targeted_hours`, `unpaid_break_hours`, `status`, `hours`, `creation_date`) VALUES ('Absent', 'ABS', '00:00:00', '00:00:00', '0', '0', '0', '0', '0', '1', '0', '2020-05-13 18:56:00');

/* 18-05-2020*/


UPDATE `rotacloud`.`master_designation` SET `nurse_count` = '1' WHERE (`id` = '40');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '10' WHERE (`id` = '40');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '11' WHERE (`id` = '10');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '12' WHERE (`id` = '45');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '13' WHERE (`id` = '44');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '14' WHERE (`id` = '17');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '15' WHERE (`id` = '18');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '16' WHERE (`id` = '1');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '16' WHERE (`id` = '4');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '16' WHERE (`id` = '24');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '16' WHERE (`id` = '34');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '17' WHERE (`id` = '8');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '2');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '3');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '5');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '7');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '9');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '12');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '19');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '20');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '21');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '22');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '23');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '25');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '26');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '27');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '28');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '29');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '30');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '31');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '32');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '33');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '35');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '36');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '37');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '38');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '18' WHERE (`id` = '39');


UPDATE `rotacloud`.`master_designation` SET `sort_order` = '9' WHERE (`id` = '40');
UPDATE `rotacloud`.`master_designation` SET `sort_order` = '10' WHERE (`id` = '11');


/*04-06-2020*/

ALTER TABLE `rotacloud`.`master_shift` 
ADD COLUMN `background_color` VARCHAR(20) NULL AFTER `updation_userid`;

UPDATE `rotacloud`.`master_shift` SET `background_color` = 'rgb(135, 255, 0)' WHERE (`id` = '3');

UPDATE `rotacloud`.`master_shift` SET `background_color` = 'rgb(239, 12, 12)' WHERE (`id` = '4');

UPDATE `rotacloud`.`master_shift` SET `background_color` = 'rgb(240, 160, 160)' WHERE (`id` = '93');

/* 05-06-2020*/

ALTER TABLE `rotacloud`.`activity_log` 
ADD COLUMN `creation_date` VARCHAR(45) NULL AFTER `primary_id`;
 
/* 26-06-2020*/

ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `unit_change_date` DATE NULL AFTER `lastlogin_date`,
ADD COLUMN `to_unit` BIGINT(20) NULL AFTER `unit_change_date`;

/* 07-07-2020*/

ALTER TABLE `rotacloud`.`available_requests` 
CHANGE COLUMN `from_unitid` `from_unitid` BIGINT(20) NULL ;

ALTER TABLE `rotacloud`.`available_requested_users` 
ADD COLUMN `unit_id` BIGINT(20) NULL AFTER `updated_date`;

/* 17-07-2020*/


UPDATE `rotacloud`.`master_shift` SET `color_unit` = '#000000', `background_color` = '#63ace5' WHERE (`id` = '1');

UPDATE `rotacloud`.`master_shift` SET `color_unit` = '#000000', `background_color` = '#a8d67e' WHERE (`id` = '2');

UPDATE `rotacloud`.`master_shift` SET `color_unit` = '#000000', `background_color` = '#e6e6ea' WHERE (`id` = '0');

UPDATE `rotacloud`.`master_shift` SET `color_unit` = '#000000', `background_color` = '#ead5dc' WHERE (`id` = '68');

/* 30-07-2020*/


ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `day_additional_hours` VARCHAR(45) NULL AFTER `additional_hours`,
ADD COLUMN `night_additional_hours` VARCHAR(45) NULL AFTER `day_additional_hours`;

/* 12-08-2020*/

ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `additinal_hour_timelog_id` VARCHAR(45) NULL AFTER `night_additional_hours`;

/* 04-09-2020*/

ALTER TABLE `rotacloud`.`master_training`
CHANGE COLUMN `unit` `unit` VARCHAR(500) NULL DEFAULT NULL ,
CHANGE COLUMN `point_of_person` `point_of_person` VARCHAR(40) NOT NULL ;


ALTER TABLE `rotacloud`.`master_training` 
ADD COLUMN `training_hour` VARCHAR(20) NULL AFTER `time_to`;

/* 24-09-2020*/
ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `user_size_session` BIGINT(10) NULL AFTER `app_pass`;


/* 30-09-2020*/
INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`) VALUES ('Admin.Editrota.Edit/Update Unpublished', 'Allow to edit unpublished rota', '1', '2019-07-08 16:15:30', '2019-07-08 16:15:30');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '78', '1');

/* 05-10-2020*/
CREATE TABLE `rotacloud`.`jobrole_group` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(45) NULL,
  `creation_date` DATETIME NULL,
  `created_by` VARCHAR(50) NULL,
  PRIMARY KEY (`id`));

INSERT INTO `rotacloud`.`jobrole_group` (`id`, `group_name`, `creation_date`, `created_by`) VALUES ('1', 'Admins', '2020-10-05 16:07:19', '1');
INSERT INTO `rotacloud`.`jobrole_group` (`id`, `group_name`, `creation_date`, `created_by`) VALUES ('2', 'Managers', '2020-10-05 16:07:19', '1');
INSERT INTO `rotacloud`.`jobrole_group` (`id`, `group_name`, `creation_date`, `created_by`) VALUES ('3', 'Nurses', '2020-10-05 16:07:19', '1');
INSERT INTO `rotacloud`.`jobrole_group` (`id`, `group_name`, `creation_date`, `created_by`) VALUES ('4', 'Workers', '2020-10-05 16:07:19', '1');
INSERT INTO `rotacloud`.`jobrole_group` (`id`, `group_name`, `creation_date`, `created_by`) VALUES ('5', 'Employees', '2020-10-05 16:07:19', '1');

ALTER TABLE `rotacloud`.`master_designation` 
ADD COLUMN `jobrole_groupid` BIGINT(20) NULL AFTER `group`;

/* 06-10-2020*/
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='1' WHERE `id`='1';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='1' WHERE `id`='2';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='3';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='14';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='15';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='16';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='19';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='33';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='38';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='41';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='42';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='3' WHERE `id`='43';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='1' WHERE `id`='4';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='5';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='6';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='1' WHERE `id`='7';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='8';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='9';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='4' WHERE `id`='10';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='4' WHERE `id`='11';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='12';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='13';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='17';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='18';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='20';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='21';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='22';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='23';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='1' WHERE `id`='24';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='25';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='26';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='27';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='28';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='29';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='30';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='31';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='32';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='1' WHERE `id`='34';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='35';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='36';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='37';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='39';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='2' WHERE `id`='40';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='4' WHERE `id`='44';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='4' WHERE `id`='45';
UPDATE `rotacloud`.`master_designation` SET `jobrole_groupid`='5' WHERE `id`='46';

/* 14-10-2020*/
CREATE TABLE `rotacloud`.`login_log` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NULL,
  `IPaddress` VARCHAR(500) NULL,
  `creation_date` DATETIME NULL,
  PRIMARY KEY (`id`));

/* 27-10-2020*/
ALTER TABLE `rota_new`.`activity_log` 
ADD COLUMN `unit_id` BIGINT(20) NULL AFTER `user_id`;


/* 04-11-2020 */

CREATE TABLE `rota_nov`.`additional_hours` (
  `id` BIGINT(20) NOT NULL,
  `additional_hours` VARCHAR(45) NULL,
  `day_additional_hours` VARCHAR(45) NULL,
  `night_additional_hours` VARCHAR(45) NULL,
  `additinal_hour_timelog_id` VARCHAR(45) NULL,
  `comment` TEXT NULL,
  `updation_date` DATETIME NULL,
  `updated_userid` BIGINT(20) NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rota_nov`.`additional_hours` 
ADD COLUMN `date` DATE NULL AFTER `id`,
ADD COLUMN `user_id` BIGINT(20) NULL AFTER `date`,
ADD COLUMN `unit_id` BIGINT(20) NULL AFTER `user_id`,
ADD COLUMN `creation_date` DATETIME NULL AFTER `comment`,
ADD COLUMN `created_userid` BIGINT(20) NULL AFTER `creation_date`;

ALTER TABLE `rotacloud.`additional_hours`
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT ;


/* 16-11-2020 */

CREATE TABLE `rotacloud`.`Ethnicity` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `Ethnic_group` VARCHAR(150) NULL,
  `parent` BIGINT(20) NULL,
  `status` TINYINT(4) NULL,
  `other_status` TINYINT(4) NULL,
  PRIMARY KEY (`id`));

INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (1,'White',0,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (2,'English/Welsh/Scottish/Northern Irish/British',1,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (3,'Irish',1,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (4,'Gypsy or Irish Traveller',1,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (5,'Any other White background, write in',1,1,1);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (6,'Mixed/multiple ethnic groups',0,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (7,'White and Black Caribbean',6,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (8,'White and Black African',6,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (9,'White and Asian',6,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (10,'Any other mixed/multiple ethnic background, write in',6,1,1);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (11,'Asian/Asian British',0,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (12,'Indian',11,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (13,'Pakistani',11,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (14,'Bangladeshi',11,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (15,'Chinese',11,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (16,'Any other Asian background, write in',11,1,1);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (17,'Black/African/Caribbean/Black British',0,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (18,'African',17,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (19,'Caribbean',17,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (20,'Any other Black/African/Caribbean background, write in',17,1,1);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (21,'Other ethnic group',0,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (22,'Arab',21,1,0);
INSERT INTO `Ethnicity` (`id`,`Ethnic_group`,`parent`,`status`,`other_status`) VALUES (23,'Any other ethnic group,write in',21,1,1);
 

ALTER TABLE `rotacloud`.`personal_details` 
ADD COLUMN `Ethnicity` VARCHAR(100) NULL AFTER `status`;

/* 30-11-2020 */

UPDATE `rota_cloud`.`Ethnicity` SET `Ethnic_group`='Any other White background' WHERE `id`='5';
UPDATE `rota_cloud`.`Ethnicity` SET `Ethnic_group`='Any other mixed/multiple ethnic background' WHERE `id`='10';
UPDATE `rota_cloud`.`Ethnicity` SET `Ethnic_group`='Any other Asian background' WHERE `id`='16';
UPDATE `rota_cloud`.`Ethnicity` SET `Ethnic_group`='Any other Black/African/Caribbean background' WHERE `id`='20';
UPDATE `rota_cloud`.`Ethnicity` SET `Ethnic_group`='Any other ethnic group' WHERE `id`='23';


/* 11-12-2020 */

ALTER TABLE `rota_cloud`.`personal_details` 
ADD COLUMN `visa_status` BIGINT(20) NULL AFTER `postcode`;

/* 08-01-2021 */

CREATE TABLE `rota_cloud`.`user_edit_activitylog` (
  `id` BIGINT(29) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NULL,
  `current_data` TEXT NULL,
  `previous_data` TEXT NULL,
  `activity_type` VARCHAR(100) NULL,
  `activity_date` DATETIME NULL,
  `activity_by` BIGINT(20) NULL,
  PRIMARY KEY (`id`));

/* 14-01-2021 */

CREATE TABLE `rota_cloud`.`History_post_data` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `description` TEXT NULL,
  `type` VARCHAR(50) NULL,
  `created_by` BIGINT(20) NULL,
  `creation_date` DATETIME NULL,
  PRIMARY KEY (`id`));

/* 02-02-2021 */

ALTER TABLE `rota_cloud`.`available_requests` 
ADD COLUMN `created_userid` BIGINT(20) NULL AFTER `created_date`;


/* 07-06-2021 */

ALTER TABLE `rota_log` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;

/* 10-06-2021 */

ALTER TABLE `rota_cloud`.`rota_schedule`
ADD COLUMN `auto_insert` BIGINT(20) NULL DEFAULT 0 AFTER `request_id`;

/* 11-06-2021 */

INSERT INTO `rotacloud`.`permissions` (`name`, `description`, `status`, `creation_date`, `updation_date`) VALUES ('Admin.rota.Moveshift', 'Allow to move shift', '1', '2021-06-11 16:15:30', '2021-06-11 16:15:30');

INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '90', '1');




/* 16-06-2021 */

ALTER TABLE `rotacloud`.`master_group` 
ADD COLUMN `subunit_access` TINYINT(4) NULL AFTER `updated_userid`;

UPDATE `rotacloud`.`master_group` SET `subunit_access` = '1' WHERE (`id` = '1');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '0' WHERE (`id` = '2');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '1' WHERE (`id` = '3');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '1' WHERE (`id` = '5');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '1' WHERE (`id` = '6');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '1' WHERE (`id` = '9');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '0' WHERE (`id` = '10');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '0' WHERE (`id` = '11');
UPDATE `rotacloud`.`master_group` SET `subunit_access` = '0' WHERE (`id` = '12');

 


/* 18-06-2021 */

ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `exit_interview` TINYINT(4) NULL AFTER `user_size_session`,
ADD COLUMN `exit_reason` TEXT NULL AFTER `exit_interview`;


/* 10-11-2021 */


INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('79', 'Admin.Report.AgencyReport', 'Allow to view Agency user report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('80', 'Admin.Report.AnnualLeaveplanner', 'Allow to view Annual leave planner', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('81', 'Admin.Report.Availability_report_count', 'Allow to view Availability count report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('82', 'Admin.Report.Availability_report_user_count', 'Allow to view user availability count', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('83', 'Admin.Report.Overtime_Report', 'Allow to view overtime report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('84', 'Admin.Report.Requestvsactualreport', 'Allow to view reqsuest vs actual shift report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('85', 'Admin.Report.SicknessReport', 'Allow to view sickness report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('86', 'Admin.Report.Timesheet', 'Allow to view Timesheet report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('87', 'Admin.Report.TransferHour', 'Allow to view transfer hour report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('88', 'Admin.Report.Weekendsreport', 'Allow to view weekends worked report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('89', 'Admin.Report.Workingreport', 'Allow to view working report', '1', '2021-06-04 16:15:30', '2021-06-04 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('90', 'Admin.rota.Moveshift', 'Allow to move shift', '1', '2021-06-11 16:15:30', '2021-06-11 16:15:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('91', 'Admin.Report.lateness_report', 'Allow to view late check in staffs', '1', '2021-11-10 10:35:30', '2021-11-10 10:35:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('92', 'Admin.Report.earlyleaver_report', 'Allow to view early leaver report', '1', '2021-11-10 10:35:30', '2021-11-10 10:35:30', '1');
INSERT INTO `rotacloud`.`permissions` (`id`, `name`, `description`, `status`, `creation_date`, `updation_date`, `created_userid`) VALUES ('93', 'Admin.Report.Agencyloginreport', 'Allow to view agency staff timelog report', '1', '2021-11-10 10:35:30', '2021-11-10 10:35:30', '1');


INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '79', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '80', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '81', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '82', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '83', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '84', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '85', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '86', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '87', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '88', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '89', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '90', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '91', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '92', '1');
INSERT INTO `rotacloud`.`group_permissions` (`group_id`, `permission_id`, `status`) VALUES ('1', '93', '1');

/* 02-12-2021 */

ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `hr_ID` VARCHAR(50) NULL AFTER `exit_reason`;


 













