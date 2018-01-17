<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@zodream.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@datetimer.css')
    ->registerCssFile('@finance.css');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?=$this->title?>-ZoDream Finance</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>
<body>
<header>
    <div class="container">
        ZoDream Finance
    </div>
</header>
<div class="container">
    <div class="left-catelog navbar">
        <?php $this->extend('./navbar')?>
    </div>
    <div class="right-content">