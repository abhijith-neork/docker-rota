/*created on26/10/2019*/

CREATE TABLE `rotacloud`.`user_email_send` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `mail_send_status` TINYINT NULL,
  `password_change_status` TINYINT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_email_send_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_email_send_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `rotacloud`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
ALTER TABLE `rotacloud`.`user_email_send` 
ADD COLUMN `updation_date` DATETIME NULL AFTER `password_change_status`,
ADD COLUMN `updated_userid` VARCHAR(45) NULL AFTER `updation_date`;
ALTER TABLE `rotacloud`.`user_email_send` 
DROP COLUMN `updated_userid`;

/*created on28/10/2019*/

DELETE FROM `rotacloud`.`master_shift` WHERE `id`='22';

ALTER TABLE `rotacloud`.`training_titles` 
ADD COLUMN `creation_date` DATETIME NULL AFTER `title`;


UPDATE `rotacloud`.`available_master_shift` SET `id`='100' WHERE `id`='1';
UPDATE `rotacloud`.`available_master_shift` SET `id`='101' WHERE `id`='2';
UPDATE `rotacloud`.`available_master_shift` SET `id`='102' WHERE `id`='3';
UPDATE `rotacloud`.`available_master_shift` SET `id`='103' WHERE `id`='4';
UPDATE `rotacloud`.`available_master_shift` SET `id`='104' WHERE `id`='5';
UPDATE `rotacloud`.`available_master_shift` SET `id`='105' WHERE `id`='6';
UPDATE `rotacloud`.`available_master_shift` SET `id`='106' WHERE `id`='7';
UPDATE `rotacloud`.`available_master_shift` SET `id`='107' WHERE `id`='8';
UPDATE `rotacloud`.`available_master_shift` SET `id`='108' WHERE `id`='9';
UPDATE `rotacloud`.`available_master_shift` SET `id`='109' WHERE `id`='10';
UPDATE `rotacloud`.`available_master_shift` SET `id`='110' WHERE `id`='11';
UPDATE `rotacloud`.`available_master_shift` SET `id`='111' WHERE `id`='12';
UPDATE `rotacloud`.`available_master_shift` SET `id`='112' WHERE `id`='13';
UPDATE `rotacloud`.`available_master_shift` SET `id`='113' WHERE `id`='14';
UPDATE `rotacloud`.`available_master_shift` SET `id`='114' WHERE `id`='15';
UPDATE `rotacloud`.`available_master_shift` SET `id`='115' WHERE `id`='16';


INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('1', '7', '1', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('2', '7', '2', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('3', '7', '3', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('4', '7', '4', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('5', '7', '5', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('6', '7', '6', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('7', '7', '7', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('8', '7', '8', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('9', '7', '9', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('10', '7', '10', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('11', '7', '11', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('12', '7', '12', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('13', '7', '13', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('14', '7', '14', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('15', '7', '15', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('16', '7', '16', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('17', '7', '17', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('18', '7', '18', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('19', '7', '19', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('20', '7', '20', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('21', '7', '21', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('22', '7', '22', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('23', '7', '23', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('24', '7', '24', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('25', '7', '25', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('26', '7', '26', '4', '16', '2019-10-09 15:53:43', '1');
INSERT INTO `rotacloud`.`unit_designation_maxleave` (`id`, `unit_id`, `designation_id`, `max_leave`, `max_leave_hour`, `creation_date`, `created_userid`) VALUES ('27', '7', '27', '4', '16', '2019-10-09 15:53:43', '1');


/*both server updated*/

/*created on04/11/2019*/

UPDATE `rotacloud`.`master_shift` SET `targeted_hours`='0', `unpaid_break_hours`='0', `hours`='0' WHERE `id`='21';

/*created on 05/11/2019*/

ALTER TABLE `rotacloud`.`training_staff` 
ADD COLUMN `published` TINYINT(4) NOT NULL AFTER `updated_userid`;

/*created on 06/11/2019*/



INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (116,'Available 7.30-12.30','AVL-7.30-12.30','07:30','12:30',1,0,'5:00','0','2019-10-30 21:34:45',46);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (117,'Available 7.30-4','AVL-7.30-4','07:30','16:00',1,0,'8:30','1','2019-10-30 21:35:45',47);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (118,'Available 12-8','AVL-12-8','12:00','20:00',1,0,'8:00','0','2019-10-30 21:36:05',48);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (119,'Available 7-1.30','AVL-7-1.30','07:00','13:30',1,0,'6:30','0','2019-10-30 21:36:21',49);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (120,'Available 7-3','AVL-7-3','07:00','15:00',1,0,'8:00','0','2019-10-30 21:36:37',50);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (121,'Available 8.30-4.15','AVL-8.30-4.15','08:30','16:15',1,0,'7:45','0.5','2019-10-30 21:37:07',51);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (122,'Available 8.30-4.30','AVL-8.30-4.30','08:30','16:30',1,0,'8:00','0','2019-10-30 21:37:27',52);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (123,'Available 8-6','AVL-8-6','08:00','18:00',1,0,'10:00','0','2019-10-30 21:37:40',53);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (124,'Available 9-4.15','AVL-9-4.15','09:00','16:15',1,0,'7:15','0','2019-10-30 21:38:06',54);
INSERT INTO `available_master_shift` (`id`,`shift_name`,`shift_shortcut`,`start_time`,`end_time`,`shift_category`,
`part_number`,`targeted_hours`,`unpaid_break_hours`,`creation_date`,`parent_id`)
VALUES (125,'Available 9.40.3.50','AVL-9.40.3.50','07:00','17:00',1,1,'10:00','0','2019-11-06 10:45:02',55);

/*created on 07/11/2019*/


ALTER TABLE `rotacloud`.`master_shift` 
ADD COLUMN `total_targeted_hours` VARCHAR(10) NULL AFTER `unpaid_break_hours`;

ALTER TABLE `rotacloud`.`holliday` 
ADD COLUMN `leave_remaining` VARCHAR(20) NULL AFTER `days`;

/*created on 12/11/2019*/


ALTER TABLE `rotacloud`.`available_requests` 
ADD COLUMN `comments` VARCHAR(45) NULL DEFAULT NULL AFTER `created_date`;

ALTER TABLE `rotacloud`.`available_requests` 
CHANGE COLUMN `comments` `comments` TEXT NULL DEFAULT NULL ;


CREATE TABLE `rotacloud`.`daily_senses` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `unit_id` BIGINT(20) NULL,
  `user_id` BIGINT(20) NULL,
  `comment` TEXT NULL,
  `creation_date` DATETIME NULL,
  `created_userid` BIGINT(20) NULL,
  PRIMARY KEY (`id`)); 

/*created on 15/11/2019*/

ALTER TABLE `rotacloud`.`rota_schedule` 
CHANGE COLUMN `shift_hours` `shift_hours` VARCHAR(45) NULL DEFAULT NULL ;


/*created on 18/11/2019*/
UPDATE `rotacloud`.`master_shift` SET `start_time` = '00:00:00', `end_time` = '00:00:00' WHERE (`id` = '0');





