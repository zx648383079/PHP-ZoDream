<?php
/** @var $this \Zodream\Domain\View\View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@blog.min.css');
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
<div class="book-skin">