<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@zodream.css',
    '@forum.css'
])->registerJsFile([
    '@jquery.min.js',
    '@main.min.js',
    '@forum.min.js'
]);
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



<footer class="page-footer">
    <a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
</footer>
<?=$this->footer()?>
</body>
</html>