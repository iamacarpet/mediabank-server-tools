CREATE TABLE IF NOT EXISTS `downloads` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `URL` varchar(650) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `URL` (`URL`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `fails` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `URL` varchar(650) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `URL` (`URL`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `feeds` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `URL` varchar(350) NOT NULL,
  `DownloadTo` varchar(250) NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Enabled` (`Enabled`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `filters` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Feed` int(10) NOT NULL,
  `Filter` varchar(350) NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Feed` (`Feed`),
  KEY `Enabled` (`Enabled`)
) ENGINE=InnoDB;


ALTER TABLE `filters`
  ADD CONSTRAINT `filters_ibfk_1` FOREIGN KEY (`Feed`) REFERENCES `feeds` (`ID`);
