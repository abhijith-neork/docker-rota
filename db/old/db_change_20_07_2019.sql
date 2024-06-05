Table : notes

ALTER TABLE `rotacloud`.`notes` 
ADD COLUMN `unit_id` VARCHAR(100) NULL AFTER `user_id`;


New table : notes_staff

CREATE TABLE `rotacloud`.`notes_staff` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `note_id` BIGINT(20) NOT NULL,
  `status` TINYINT(4) NULL,
  `creation_date` DATETIME NOT NULL,
  `updation_date` DATETIME NOT NULL,
  `updated_userid` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`));