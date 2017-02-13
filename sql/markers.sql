DROP TABLE IF EXISTS `markers`;
CREATE TABLE `markers` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 60 ) NOT NULL ,
  `address` VARCHAR( 80 ) NOT NULL ,
  `nd` VARCHAR( 60 ) NOT NULL ,
  `lat` DECIMAL( 10, 6 ) NOT NULL ,
  `lng` DECIMAL( 10, 6 ) NOT NULL,
  `website` VARCHAR( 200 ) NOT NULL 
) ENGINE = InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

LOAD DATA INFILE "C:\xampp\htdocs\timandkimberly\sql\markers.csv" IGNORE INTO TABLE `markers`
FIELDS TERMINATED BY ',' ENCLOSED BY '' ESCAPED BY ''
 (`name`, `address`, `lat`, `lng`);

