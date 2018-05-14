<?php
use Zodream\Template\View;
/** @var $this View */
$this->title = '项目接口文档';
$this->extend('layouts/header');
?>

    <h1><?=$this->title?></h1>

<?php
$this->extend('layouts/footer');
?>