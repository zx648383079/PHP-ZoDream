<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Layout;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@dialog.css',
        '@poker.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@main.min.js',
        '@jquery.pjax.min.js',
        '@poker.min.js'
    ]);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$this->get('title', '扎金花游戏')?></title>
    <?=$this->header()?>
</head>
<body>
    <div id="game-box">
    <?=$content?>
    </div>

    <?=$this->footer()?>
</body>
</html>