<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Template\View */
$this->extend('layout/header');
?>

<div class="main">
<h1>完成</h1>
<p>成功完成相关设置，请尽情享受。。。</p>
<a class="ms-Button ms-Button--primary" href="<?=Url::to('/index.php');?>">进入首页</a>
</div>

<?php $this->extend('layout/footer');?>