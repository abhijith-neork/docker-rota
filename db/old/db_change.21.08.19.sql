
ALTER TABLE `rotacloud`.`designation_rates` 
ADD COLUMN `normal_rates` BIGINT(20) NULL AFTER `designation_id`;

ALTER TABLE `rotacloud`.`designation_rates` 
CHANGE COLUMN `maternity-rate` `maternity_rate` DECIMAL(5,2) NULL DEFAULT NULL ;


 

