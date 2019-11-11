<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@exam.css')
    ->registerJsFile('@exam.min.js');
?>