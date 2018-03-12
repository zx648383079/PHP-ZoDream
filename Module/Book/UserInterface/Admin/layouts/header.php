<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@zodream.css')
    ->registerCssFile('@book_admin.css');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?=$this->title?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>
<body>
<header>
    <div class="container">
        ZoDream Book
    </div>
</header>
<div class="container">
    <div class="left-catelog navbar">
        <?php $this->extend('./navbar')?>
    </div>
    <div class="right-content">