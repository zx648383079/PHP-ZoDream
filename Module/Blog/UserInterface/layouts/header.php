<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@blog.css');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?=$this->title?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Keywords" content="zodream,zodream 博客, 博客，个人博客" />
    <meta name="Description" content="记录自己的想法，记录人生的点点滴滴，个人博客" />
    <meta name="author" content="zodream" />
    <?=$this->header()?>
</head>
<body>
<div class="book-skin">