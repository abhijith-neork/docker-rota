ALTER TABLE `rotacloud`.`users` 
ADD INDEX `fk_users_3_idx` (`designation_id` ASC),
ADD INDEX `fk_users_4_idx` (`default_shift` ASC);
ALTER TABLE `rotacloud`.`users` 
ADD CONSTRAINT `fk_users_3`
  FOREIGN KEY (`designation_id`)
  REFERENCES `rotacloud`.`master_designation` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_users_4`
  FOREIGN KEY (`default_shift`)
  REFERENCES `rotacloud`.`master_shift` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;



ALTER TABLE `rotacloud`.`rota_schedule` 
ADD INDEX `fk_rota_2_idx` (`shift_id` ASC),
ADD INDEX `fk_rota_3_idx` (`unit_id` ASC),
ADD INDEX `fk_rota_4_idx` (`rota_id` ASC);
ALTER TABLE `rotacloud`.`rota_schedule` 
ADD CONSTRAINT `fk_rota_2`
  FOREIGN KEY (`shift_id`)
  REFERENCES `rotacloud`.`master_shift` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_rota_3`
  FOREIGN KEY (`unit_id`)
  REFERENCES `rotacloud`.`unit` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_rota_4`
  FOREIGN KEY (`rota_id`)
  REFERENCES `rotacloud`.`rota` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;



SELECT * FROM rotacloud.rota_schedule where user_id=3 and date >= curdate() order by date desc;

