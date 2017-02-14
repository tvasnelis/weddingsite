DROP TABLE IF EXISTS `markers`;
CREATE TABLE `markers` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 60 ) NOT NULL ,
  `address` VARCHAR( 80 ) NOT NULL ,
  `nd` VARCHAR( 60 ) NOT NULL ,
  `lat` DECIMAL( 10, 6 ) NOT NULL ,
  `lng` DECIMAL( 10, 6 ) NOT NULL,
  `website` VARCHAR( 200 ) NOT NULL,
  `type` VARCHAR(50),
  `html` VARCHAR(50)
) ENGINE = InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;


 

