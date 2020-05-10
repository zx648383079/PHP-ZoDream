<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@dialog.css',
    '@zodream.css',
    '@book_mobile.css'
  ])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./@mobile/', false)), View::HTML_HEAD);
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
   <body class="chapter min-mode" ondragstart="return false" oncopy="return false;" oncut="return false;" oncontextmenu="return false">