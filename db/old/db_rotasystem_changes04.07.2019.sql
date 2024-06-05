
ALTER TABLE `rotacloud`.`unit` 
ADD COLUMN `address` TEXT NULL AFTER `unit_type`,
ADD COLUMN `phone_number` BIGINT(20) NULL AFTER `address`;

