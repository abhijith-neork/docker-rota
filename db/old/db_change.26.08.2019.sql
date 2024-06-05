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







