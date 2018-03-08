-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema blog
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8 ;
USE `blog` ;

-- -----------------------------------------------------
-- Table `blog`.`picture`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog`.`picture` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `blog`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `isAdmin` TINYINT NOT NULL,
  `picture_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_picture1_idx` (`picture_id` ASC),
  CONSTRAINT `fk_user_picture1`
    FOREIGN KEY (`picture_id`)
    REFERENCES `blog`.`picture` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `blog`.`topic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog`.`topic` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_topic_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_topic_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `blog`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `blog`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog`.`post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text` VARCHAR(1000) NOT NULL,
  `user_id` INT NOT NULL,
  `topic_id` INT NOT NULL,
  `title` VARCHAR(45) NULL,
  `update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_post_user_idx` (`user_id` ASC),
  INDEX `fk_post_topic1_idx` (`topic_id` ASC),
  CONSTRAINT `fk_post_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `blog`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_topic1`
    FOREIGN KEY (`topic_id`)
    REFERENCES `blog`.`topic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `blog`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog`.`comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text` VARCHAR(255) NOT NULL,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_comment_post1_idx` (`post_id` ASC),
  INDEX `fk_comment_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_comment_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `blog`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `blog`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `blog`.`post_picture`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog`.`post_picture` (
  `post_id` INT NOT NULL,
  `picture_id` INT NOT NULL,
  PRIMARY KEY (`post_id`, `picture_id`),
  INDEX `fk_post_picture_picture1_idx` (`picture_id` ASC),
  CONSTRAINT `fk_post_picture_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `blog`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_picture_picture1`
    FOREIGN KEY (`picture_id`)
    REFERENCES `blog`.`picture` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
