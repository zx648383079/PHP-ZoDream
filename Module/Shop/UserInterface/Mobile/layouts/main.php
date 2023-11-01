<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@zodream.css',
    '@dialog.css',
    '@shop_mobile.css'
])->registerJsFile([
    '@js.cookie.min.js',
    '@jquery.min.js',
    '@jquery.dialog.min.js',
    '@jquery.lazyload.min.js',
    '@main.min.js',
    '@shop.min.js'
])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./@mobile/', false)), View::HTML_HEAD);
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
   <?=$this->contents()?>
   <div class="demo-tip"></div>
   <?=$this->footer()?>
   </body>
</html>