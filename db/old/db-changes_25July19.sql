CREATE TABLE `rotacloud`.`devices` (
  `id` BIGINT(20) NOT NULL,
  `device_id` VARCHAR(200) NOT NULL,
  `unit_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `creation_date` DATETIME NULL,
  `updation_date` DATETIME NULL,
  `status` TINYINT(5) NULL,
  PRIMARY KEY (`id`));
ALTER TABLE `rotacloud`.`users` 
ADD COLUMN `thumbnail` TEXT NULL AFTER `payroll_id`;

ALTER TABLE `rotacloud`.`devices` 
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT ;


