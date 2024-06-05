
ALTER TABLE `rotacloud`.`holliday` 
ADD COLUMN `start_time` VARCHAR(20) NULL AFTER `from_date`,
ADD COLUMN `end_time` VARCHAR(20) NULL AFTER `start_time`,
ADD COLUMN `days` BIGINT(20) NULL AFTER `end_time`;


