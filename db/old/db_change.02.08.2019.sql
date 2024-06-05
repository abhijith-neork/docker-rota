ALTER TABLE `rotacloud`.`unit` 
ADD COLUMN `color_unit` VARCHAR(20) NULL AFTER `status`;


ALTER TABLE `rotacloud`.`master_shift` 
ADD COLUMN `hours` VARCHAR(10) NULL AFTER `status`;


