<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('../layouts/header');
?>
<div>
    <h1>小说管理平台</h1>
</div>
<?php $this->extend('../layouts/footer');?>