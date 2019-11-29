<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@book_mobile.css')->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./@mobile/', false)), View::HTML_HEAD);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
   <head>
        <meta name="viewport" content="width=device-width,user-scalable=no" />
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="Description" content="<?=$this->description?>" />
       <meta name="keywords" content="<?=$this->keywords?>" />
       <title><?=$this->title?></title>
       <?=$this->header();?>
   </head>
   <body class="<?=$this->body_class?>">
   <div class="header">
        <a href="<?=$this->url('./mobile')?>" class="logo">
            <img src="/assets/images/wap_logo.png" height="40" alt="" />
        </a>
        <div class="bottom">
            <div class="nav">
                <a href="<?=$this->url('./mobile')?>">首页</a>
                <a href="<?=$this->url('./mobile/search/top')?>">排行</a>
                <a href="<?=$this->url('./mobile/search/list', ['status' => 2])?>">全本</a>
                <a href="<?=$this->url('./mobile/history')?>">阅读史</a>
            </div>
        </div>
    </div>