ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `weekly_hours` BIGINT(20) NULL AFTER `status`,
ADD COLUMN `annual_holliday_allowance` BIGINT(20) NULL AFTER `weekly_hours`,
ADD COLUMN `annual_allowance_type` TINYINT NULL AFTER `annual_holliday_allowance`,
ADD COLUMN `start_date` DATE NULL AFTER `annual_allowance_type`,
ADD COLUMN `final_date` DATE NULL AFTER `start_date`,
ADD COLUMN `notes` TEXT NULL AFTER `final_date`;

ALTER TABLE `rotacloud`.`users` 
DROP FOREIGN KEY `fk_users_3`;
ALTER TABLE `rotacloud`.`users` 
DROP INDEX `fk_users_3_idx` ;


