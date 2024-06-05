ALTER TABLE `rotacloud`.`master_training` 
CHANGE COLUMN `date_from` `date_from` DATE NULL ,
CHANGE COLUMN `date_to` `date_to` DATE NULL ,
CHANGE COLUMN `time_from` `time_from` VARCHAR(15) NULL ,
CHANGE COLUMN `time_to` `time_to` VARCHAR(15) NULL,
CHANGE COLUMN `unit` `unit` VARCHAR(20) NULL ;


ALTER TABLE `rotacloud`.`master_training` 
ADD COLUMN `description` TEXT NULL AFTER `title`;

