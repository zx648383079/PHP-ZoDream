<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@zodream.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@cms_admin.css');
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
   <head>
       <meta name="viewport" content="width=device-width, initial-scale=1"/>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="Description" content="<?=$this->description?>" />
       <meta name="keywords" content="<?=$this->keywords?>" />
       <title><?=$this->title?></title>
       <?=$this->header();?>
   </head>
   <body>
   <header>
        <div class="container">
            ZoDream CMS Admin
        </div>
    </header>
    <div class="container">
        <div class="left-catelog navbar">
            <?php $this->extend('./navbar')?>
        </div>
        <div class="right-content">