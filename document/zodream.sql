-- MySQL Script generated by MySQL Workbench
-- 03/18/16 12:45:57
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema zodream
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema zodream
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `zodream` DEFAULT CHARACTER SET utf8 ;
USE `zodream` ;

-- -----------------------------------------------------
-- Table `zodream`.`zd_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `remember_token` VARCHAR(60) NULL,
  `role` INT(2) NULL DEFAULT 1,
  `create_at` INT(11) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `password_UNIQUE` (`password` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_blog_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_blog_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `decription` VARCHAR(255) NULL,
  `status` ENUM('visible', 'hide') NULL DEFAULT 'visible',
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_category_idx` (`user_id` ASC),
  CONSTRAINT `user_category`
    FOREIGN KEY (`user_id`)
    REFERENCES `zodream`.`zd_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_blogs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_blogs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `image` VARCHAR(45) NULL,
  `keyword` VARCHAR(45) NULL,
  `description` VARCHAR(255) NULL,
  `content` VARCHAR(45) NOT NULL,
  `class_id` INT(3) NULL,
  `user_id` INT(11) NOT NULL,
  `comment_count` INT(11) NULL COMMENT '评论总数',
  `status` TINYINT(1) NULL DEFAULT 1 COMMENT '是否可见',
  `allow_comment` TINYINT(1) NULL DEFAULT 1 COMMENT '是否允许评论',
  `template` INT(3) NULL DEFAULT 0,
  `update_at` INT(11) NOT NULL,
  `create_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_blog_idx` (`user_id` ASC),
  INDEX `class_blog_idx` (`class_id` ASC),
  CONSTRAINT `user_blog`
    FOREIGN KEY (`user_id`)
    REFERENCES `zodream`.`zd_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `category_blog`
    FOREIGN KEY (`class_id`)
    REFERENCES `zodream`.`zd_blog_category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_password_reset`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_password_reset` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(60) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `create_at` INT(11) NULL,
  PRIMARY KEY (`id`),
  INDEX `user_password_reset_idx` (`email` ASC),
  CONSTRAINT `user_password_reset`
    FOREIGN KEY (`email`)
    REFERENCES `zodream`.`zd_users` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_options` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_blog_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_blog_comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(255) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `ip` VARCHAR(20) NULL,
  `user_id` INT(11) NULL DEFAULT 0,
  `blog_id` INT(11) NULL,
  `parent_id` INT(11) NULL DEFAULT 0,
  `create_at` INT(11) NULL,
  PRIMARY KEY (`id`),
  INDEX `blog_comment_idx` (`blog_id` ASC),
  CONSTRAINT `blog_comment`
    FOREIGN KEY (`blog_id`)
    REFERENCES `zodream`.`zd_blogs` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_tasks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `content` TEXT NULL,
  `status` ENUM('审核', '成功', '失败') NULL DEFAULT '审核',
  `progress` INT(2) NULL DEFAULT 0,
  `user_id` INT(11) NULL,
  `update_at` INT(11) NULL,
  `create_at` INT(11) NULL,
  PRIMARY KEY (`id`),
  INDEX `user_task_idx` (`user_id` ASC),
  CONSTRAINT `user_task`
    FOREIGN KEY (`user_id`)
    REFERENCES `zodream`.`zd_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `action` VARCHAR(45) NULL,
  `data` TEXT NULL,
  `url` VARCHAR(255) NULL,
  `ip` VARCHAR(20) NULL,
  `create_at` INT(11) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `title` VARCHAR(45) NOT NULL COMMENT '标题',
  `content` VARCHAR(255) NULL COMMENT '联系信息',
  `ip` VARCHAR(20) NULL,
  `readed` TINYINT(1) NULL DEFAULT 0 COMMENT '是否已查看',
  `create_at` INT(11) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_product_classes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_product_classes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `decription` VARCHAR(255) NULL,
  `status` ENUM('visible', 'hide') NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `image` VARCHAR(45) NULL,
  `keyword` VARCHAR(45) NULL,
  `description` VARCHAR(255) NULL,
  `content` VARCHAR(45) NOT NULL,
  `class_id` INT(3) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `comment_count` INT(11) NULL,
  `status` TINYINT(1) NULL DEFAULT 1,
  `allow_comment` TINYINT(1) NULL DEFAULT 1,
  `template` INT(3) NULL DEFAULT 0,
  `update_at` INT(11) NOT NULL,
  `create_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_product_idx` (`user_id` ASC),
  INDEX `class_product_idx` (`class_id` ASC),
  CONSTRAINT `user_product`
    FOREIGN KEY (`user_id`)
    REFERENCES `zodream`.`zd_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `class_product`
    FOREIGN KEY (`class_id`)
    REFERENCES `zodream`.`zd_product_classes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream`.`zd_product_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream`.`zd_product_comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(255) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `ip` VARCHAR(20) NULL,
  `user_id` INT(11) NULL DEFAULT 0,
  `product_id` INT(11) NULL,
  `parent_id` INT(11) NULL DEFAULT 0,
  `create_at` INT(11) NULL,
  PRIMARY KEY (`id`),
  INDEX `product_comment_idx` (`product_id` ASC),
  CONSTRAINT `product_comment`
    FOREIGN KEY (`product_id`)
    REFERENCES `zodream`.`zd_products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
