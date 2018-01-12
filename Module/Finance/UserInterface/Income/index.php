<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '欢迎使用生成器';

$this->extend('layouts/header');
?>

    <h2>欢迎使用生成器！</h2>

<?php
$this->extend('layouts/footer');
?>