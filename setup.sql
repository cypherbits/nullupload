-- MySQL Script generated by MySQL Workbench
-- vie 09 oct 2020 10:56:06
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema nullupload
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema nullupload
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `nullupload` ;
USE `nullupload` ;

-- -----------------------------------------------------
-- Table `nullupload`.`files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nullupload`.`files` (
  `id` VARCHAR(10) NOT NULL,
  `origName` VARCHAR(240) NULL DEFAULT NULL,
  `filename` VARCHAR(64) NOT NULL,
  `extension` VARCHAR(10) NOT NULL,
  `uploadDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nDownloads` INT(11) NOT NULL DEFAULT '0',
  `lastDownload` TIMESTAMP NULL DEFAULT NULL,
  `type` VARCHAR(64) NULL DEFAULT NULL,
  `password` VARCHAR(64) NULL DEFAULT NULL,
  `deleteDate` TIMESTAMP NULL,
  `deletePassword` VARCHAR(64) NOT NULL,
  `integrity` VARCHAR(64) NOT NULL,
  `fileSize` BIGINT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `nullupload`.`news`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nullupload`.`news` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(240) NOT NULL,
  `newText` TEXT NULL DEFAULT NULL,
  `dateCreation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `nullupload`.`config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nullupload`.`config` (
  `name` VARCHAR(50) NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nullupload`.`bannedFiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `nullupload`.`bannedFiles` (
  `fileHash` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`fileHash`),
  UNIQUE INDEX `fileHash_UNIQUE` (`fileHash` ASC) VISIBLE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `nullupload`.`config`
-- -----------------------------------------------------
START TRANSACTION;
USE `nullupload`;
INSERT INTO `nullupload`.`config` (`name`, `value`) VALUES ('configDisableUpload', '0');
INSERT INTO `nullupload`.`config` (`name`, `value`) VALUES ('configDisableUploadMessage', 'Upload manually disabled');
INSERT INTO `nullupload`.`config` (`name`, `value`) VALUES ('histoTotalFileUpload', '0');
INSERT INTO `nullupload`.`config` (`name`, `value`) VALUES ('histoTotalFileSize', '0');
INSERT INTO `nullupload`.`config` (`name`, `value`) VALUES ('histoTotalFileDownloads', '0');

COMMIT;

