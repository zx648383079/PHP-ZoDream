<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@zodream.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@gzo.css');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?=$this->title?>-ZoDream Generator</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>
<body>
<header>
    <div class="container">
        ZoDream Generator
    </div>
</header>
<div class="container">
    <div class="left-catelog navbar">
        <?php $this->extend('./navbar')?>
    </div>
    <div class="right-content">