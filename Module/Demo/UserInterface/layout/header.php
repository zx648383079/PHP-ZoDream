<?php
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?=$this->title?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=Html::link('/assets/css/font-awesome.min.css')?>
    <?=Html::link('/assets/css/demo.min.css')?>
    <?=$this->header()?>
</head>
<body>
<div class="book-skin">