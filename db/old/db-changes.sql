ALTER TABLE `rotacloud`.`holliday` 
ADD COLUMN `unit_id` BIGINT(20) NULL AFTER `user_id`;

CREATE TABLE `rotacloud`.`holiday_comments` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `holiday_id` BIGINT(20) NOT NULL,
  `manager_id` BIGINT(20) NOT NULL,
  `comment` VARCHAR(100) NOT NULL,
  `date` DATETIME NOT NULL,
  `status` TINYINT(4) NOT NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `rotacloud`.`master_training` 
CHANGE COLUMN `date_from` `date_from` DATE NULL ,
CHANGE COLUMN `date_to` `date_to` DATE NULL ,
CHANGE COLUMN `time_from` `time_from` VARCHAR(15) NULL ,
CHANGE COLUMN `time_to` `time_to` VARCHAR(15) NULL,
CHANGE COLUMN `unit` `unit` VARCHAR(20) NULL ;
ALTER TABLE `rotacloud`.`master_training` 
ADD COLUMN `description` TEXT NULL AFTER `title`;
ALTER TABLE `rotacloud`.`master_training` 
CHANGE COLUMN `contact_num` `contact_num` VARCHAR(20) NOT NULL ;
ALTER TABLE `rotacloud`.`notes` 
ADD COLUMN `unit_id` VARCHAR(100) NULL AFTER `title`;

