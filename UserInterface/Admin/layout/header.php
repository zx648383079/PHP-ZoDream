<?php
defined('APP_DIR') || die();
/** @var $this \Zodream\Domain\View\View */
?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <title><?=$this->title?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="/assets/css/dialog.css" rel="stylesheet">
        <link href="/assets/css/zodream.css" rel="stylesheet">
        <link href="/assets/css/admin.css" rel="stylesheet">
        <?=$this->header()?>
    </head>
    <body>