<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@zodream.css',
    '@home.css']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$this->title?>-ZoDream</title>
    <meta name="Keywords" content="<?=$this->get('keywords', 'zodream,开发博客,个人博客,zodream文档')?>" />
    <meta name="Description" content="<?=$this->get('description', '本站首先是为zodream框架演示及文档站，其次才是开发中个人博客')?>" />
    <meta name="author" content="ZoDream" />
    <link rel="icon" href="/assets/images/favicon.png">
    <?=$this->header()?>
</head>
<body>
<header>
    <div class="container">
        <?=$this->node('nav-bar')?>
    </div>
</header>