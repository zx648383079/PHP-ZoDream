<?php
defined('APP_DIR') || die();
/** @var $this \Zodream\Domain\View\View */
$this->title = '后台管理系统';
?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <title><?=$this->title?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="/assets/css/AdminLTE.min.css" rel="stylesheet">
        <link href="/assets/css/skin.css" rel="stylesheet">
        <link href="/assets/css/admin.min.css" rel="stylesheet">
        <?=$this->header()?>
    </head>
    <body>