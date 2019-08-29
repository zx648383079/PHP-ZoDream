## 博客模块

## 本模块包括

> 前台博客显示

> 后台博客发布

> 博客评论


```sql
ALTER TABLE `zodream`.`blog` 
CHANGE COLUMN `language` `programming_language` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '编程语言' ;

ALTER TABLE `zodream`.`blog` 
ADD COLUMN `parent_id` INT NOT NULL DEFAULT 0 AFTER `programming_language`,
ADD COLUMN `language` ENUM('zh', 'en') NOT NULL DEFAULT 'zh' AFTER `parent_id`;


```