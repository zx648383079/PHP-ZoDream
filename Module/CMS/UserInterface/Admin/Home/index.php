<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream CMS Admin';
$this->extend('Admin/layouts/header');
?>




<?php $this->extend('Admin/layouts/footer');?>