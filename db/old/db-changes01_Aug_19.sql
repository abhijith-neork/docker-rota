ALTER TABLE `rotacloud`.`time_log` 
DROP FOREIGN KEY `fk_time_log_2`,
DROP FOREIGN KEY `fk_time_log_3`;
ALTER TABLE `rotacloud`.`time_log` 
DROP COLUMN `updation_date`,
CHANGE COLUMN `time_to` `time_to` TIME NULL AFTER `date`,
CHANGE COLUMN `shift_id` `shift_id` BIGINT(20) NOT NULL ,
CHANGE COLUMN `unit_id` `unit_id` BIGINT(20) NOT NULL ,
CHANGE COLUMN `date` `date` DATE NOT NULL ,
CHANGE COLUMN `status` `status` TINYINT(4) NOT NULL ,
ADD COLUMN `device_id` BIGINT(20) NOT NULL AFTER `unit_id`;
ALTER TABLE `rotacloud`.`time_log` 
ADD CONSTRAINT `fk_time_log_2`
  FOREIGN KEY (`shift_id`)
  REFERENCES `rotacloud`.`master_shift` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_time_log_3`
  FOREIGN KEY (`unit_id`)
  REFERENCES `rotacloud`.`unit` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

