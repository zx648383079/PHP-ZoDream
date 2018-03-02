<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '欢迎使用博客管理平台';

$this->extend('Admin/layouts/header');
?>

    <h2>欢迎使用博客管理平台 ！</h2>

<?php
$this->extend('Admin/layouts/footer');
?>