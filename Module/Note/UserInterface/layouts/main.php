<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@dialog.css',
    '@zodream.css',
    '@note.css'
])->registerJsFile([
    '@jquery.min.js',
    '@jquery.dialog.min.js',
    '@jquery.lazyload.min.js',
    '@main.min.js',
    '@note.min.js'
]);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="便签" />
    <title><?=$this->title?></title>
    <?=$this->header()?>
</head>
<body>
    <?=$content?>
    <?=$this->footer()?>
</body>
</html>