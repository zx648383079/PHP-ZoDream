<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('暂停服务通知');
$this->registerCssFile([
    '@zodream.css',
    '@home.css'
]);
?>
<!DOCTYPE html>
<html lang="<?=trans()->getLanguage()?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$this->text($this->title)?></title>
    <?=$this->header();?>
</head>
<body class="close-page">
    
    <div class="box">
        <h1><?=$this->text($this->title)?></h1>

        <div class="content">
            <?=$content?>
        </div>
    </div>

    <?=$this->footer()?>
</body>
</html>