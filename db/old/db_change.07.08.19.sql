ALTER TABLE `rotacloud`.`rota_schedule` 
DROP FOREIGN KEY `fk_rota_2`;
ALTER TABLE `rotacloud`.`rota_schedule` 
CHANGE COLUMN `shift_id` `shift_id` BIGINT(20) NOT NULL DEFAULT 0 ;
ALTER TABLE `rotacloud`.`rota_schedule` 
ADD CONSTRAINT `fk_rota_2`
  FOREIGN KEY (`shift_id`)
  REFERENCES `rotacloud`.`master_shift` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `rotacloud`.`rota_schedule` 
DROP FOREIGN KEY `fk_rota_2`;
ALTER TABLE `rotacloud`.`rota_schedule` 
CHANGE COLUMN `shift_id` `shift_id` BIGINT(20) NULL DEFAULT '0' ;
ALTER TABLE `rotacloud`.`rota_schedule` 
ADD CONSTRAINT `fk_rota_2`
  FOREIGN KEY (`shift_id`)
  REFERENCES `rotacloud`.`master_shift` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `rotacloud`.`rota_schedule` 
DROP FOREIGN KEY `fk_rota_2`;
ALTER TABLE `rotacloud`.`rota_schedule` 
DROP INDEX `fk_rota_2_idx` ;

ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `shift_hours` BIGINT(20) NULL AFTER `shift_id`;

ALTER TABLE `rotacloud`.`rota_schedule` 
ADD COLUMN `from_unit` BIGINT(20) NULL AFTER `shift_hours`;

