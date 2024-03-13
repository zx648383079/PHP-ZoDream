<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@animate.min.css',
    '@zodream.min.css',
    '@bot.min.css'
])->registerJsFile([
    '@js.cookie.min.js',
    '@jquery.min.js',
    '@bot.min.js'
])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
   <head>
       <meta name="viewport" content="bot_idth=device-bot_idth, initial-scale=1"/>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="Description" content="<?=$this->description?>" />
       <meta name="keywords" content="<?=$this->keywords?>" />
       <title><?=$this->title?></title>
       <?=$this->header();?>
   </head>
   <body>
   <?=$this->contents()?>
   <?=$this->footer()?>
   </body>
</html>