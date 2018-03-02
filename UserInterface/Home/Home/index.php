<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('layouts/header');
?>
<h1>欢迎使用 ZoDream！</h1>
<h3>
    <a href="https://zodream.cn/document" target="_blank">查看文档</a>
</h3>
<?php $this->extend('layouts/footer');?>