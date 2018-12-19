<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@blog.css']);
?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <title><?=$this->title?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Keywords" content="<?=isset($keywords) ? $this->text($keywords) : 'zodream,zodream 博客, 博客，个人博客'?>" />
        <meta name="Description" content="<?=isset($description) ? $description :'记录自己的想法，记录人生的点点滴滴，个人博客'?>" />
        <meta name="author" content="zodream" />
        <?=$this->header()?>
    </head>
<body>
<div id="book-page" class="book-skin">