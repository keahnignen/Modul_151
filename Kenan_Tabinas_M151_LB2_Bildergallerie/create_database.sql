-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema images
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema images
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `images` DEFAULT CHARACTER SET utf8 ;
USE `images` ;

-- -----------------------------------------------------
-- Table `images`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `images`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `isAdmin` TINYINT NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `images`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `images`.`comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text` VARCHAR(255) NOT NULL,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_comment_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `images`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `images`.`gallery`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `images`.`gallery` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_topic_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_topic_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `images`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `images`.`picture`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `images`.`picture` (
  `path` VARCHAR(45) NOT NULL,
  `gallery_id` INT NOT NULL,
  `path_thumpnail` VARCHAR(45) NULL,
  INDEX `fk_picture_gallery1_idx` (`gallery_id` ASC),
  CONSTRAINT `fk_picture_gallery1`
    FOREIGN KEY (`gallery_id`)
    REFERENCES `images`.`gallery` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `images`.`tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `images`.`tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tag` VARCHAR(45) NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `images`.`tag_gallery`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `images`.`tag_gallery` (
  `tag_id` INT NOT NULL,
  `gallery_id` INT NOT NULL,
  INDEX `fk_tag_gallery_tag1_idx` (`tag_id` ASC),
  INDEX `fk_tag_gallery_gallery1_idx` (`gallery_id` ASC),
  CONSTRAINT `fk_tag_gallery_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `images`.`tag` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tag_gallery_gallery1`
    FOREIGN KEY (`gallery_id`)
    REFERENCES `images`.`gallery` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
