new table 



CREATE TABLE `rotacloud`.`holiday_comments` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `holiday_id` BIGINT(20) NOT NULL,
  `manager_id` BIGINT(20) NOT NULL,
  `comment` VARCHAR(100) NOT NULL,
  `date` DATETIME NOT NULL,
  `status` TINYINT(4) NOT NULL,
  PRIMARY KEY (`id`));