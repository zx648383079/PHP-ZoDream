<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@zodream.css',
    '@dialog.css',
    '@home.css',
    '@install.css'
])->registerJsFile([
    '@jquery.min.js',
    '@jquery.dialog.min.js',
    '@main.min.js',
    '@install.min.js'
])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
   <head>
       <meta name="viewport" content="width=device-width, initial-scale=1"/>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="Description" content="<?=$this->description?>" />
       <meta name="keywords" content="<?=$this->keywords?>" />
       <title><?=$this->title?>-ZoDream 安装</title>
       <?=$this->header();?>
   </head>
   <body>
    <div class="container">
        <?=$content?>
    </div>
   <?=$this->footer()?>
   </body>
</html>