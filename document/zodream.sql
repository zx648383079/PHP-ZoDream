-- MySQL Script generated by MySQL Workbench
-- 04/04/16 21:21:56
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema zodream_cms
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema zodream_cms
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `zodream_cms` DEFAULT CHARACTER SET utf8 ;
USE `zodream_cms` ;

-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_user` (
  `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '用户表主键Id',
  `name` VARCHAR(30) NOT NULL COMMENT '用户名',
  `email` VARCHAR(100) NOT NULL COMMENT '邮箱',
  `password` VARCHAR(64) NOT NULL COMMENT '密码',
  `token` VARCHAR(60) NULL COMMENT '自动登陆的认证码',
  `login_num` INT(10) NULL DEFAULT 1 COMMENT '登录次数',
  `update_ip` VARCHAR(20) NULL COMMENT '最近登录IP',
  `update_at` INT(10) NULL COMMENT '最近登录时间',
  `previous_ip` VARCHAR(20) NULL COMMENT '上一次登录ip',
  `previous_at` INT(10) NULL COMMENT '上一次登录时间',
  `create_ip` VARCHAR(20) NULL,
  `create_at` INT(10) NULL COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_role` (
  `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '角色的主键',
  `name` VARCHAR(45) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_authorization`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_authorization` (
  `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '权限主键',
  `name` VARCHAR(45) NOT NULL COMMENT '权限名',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_role_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_role_user` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) NOT NULL COMMENT '用户id',
  `role_id` INT(10) NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`),
  INDEX `fk_user_idx` (`user_id` ASC),
  INDEX `fk_role_idx` (`role_id` ASC),
  CONSTRAINT `fk_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `zodream_cms`.`zd_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `zodream_cms`.`zd_role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_authorization_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_authorization_role` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `role_id` INT(10) NOT NULL,
  `authorization_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_role_idx` (`role_id` ASC),
  INDEX `fk_authorization_idx` (`authorization_id` ASC),
  CONSTRAINT `fk_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `zodream_cms`.`zd_role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_authorization`
    FOREIGN KEY (`authorization_id`)
    REFERENCES `zodream_cms`.`zd_authorization` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_log` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(20) NOT NULL,
  `user` VARCHAR(30) NOT NULL COMMENT '用户名',
  `event` VARCHAR(20) NOT NULL COMMENT '事件',
  `data` TEXT NULL COMMENT '事件的详细情况',
  `create_at` INT(10) NOT NULL COMMENT '发生时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_login_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_login_log` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(20) NOT NULL,
  `user` VARCHAR(45) NOT NULL COMMENT '用户名',
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '用户登录的状态 成功或失败',
  `mode` VARCHAR(45) NULL COMMENT '登录的方式',
  `create_at` INT(10) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_tree`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_tree` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL COMMENT '标题名',
  `url` VARCHAR(45) NULL COMMENT '所代表的网址',
  `left` INT(10) NULL COMMENT '左值',
  `right` INT(10) NULL COMMENT '右值',
  `parent_id` INT(10) NULL DEFAULT 0,
  `level` INT(10) NULL DEFAULT 0 COMMENT '水平深度',
  `position` INT(10) NULL DEFAULT 0 COMMENT '位置，顺序',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_navigation_catagory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_navigation_catagory` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL COMMENT '导航分类的类名',
  `position` INT(10) NULL DEFAULT 0 COMMENT '排放顺序',
  `user_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_idx` (`user_id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  CONSTRAINT `fk_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `zodream_cms`.`zd_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zodream_cms`.`zd_navigation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zodream_cms`.`zd_navigation` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `catagory_id` INT(10) NOT NULL,
  `position` INT(10) NULL DEFAULT 0,
  `user_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_idx` (`user_id` ASC),
  INDEX `fk_catagory_idx` (`catagory_id` ASC),
  CONSTRAINT `fk_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `zodream_cms`.`zd_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_catagory`
    FOREIGN KEY (`catagory_id`)
    REFERENCES `zodream_cms`.`zd_navigation_catagory` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
