<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@zodream.css')
    ->registerCssFile('@login.css')
    ->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?=$this->title?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>
<body class="login-page">