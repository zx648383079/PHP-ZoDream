<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Http\URL;
/** @var $this \Zodream\Template\View */
$this->extend('layouts/header');
?>

<div class="main">
<h1>完成</h1>
<p>成功完成相关设置，请尽情享受。。。</p>
<a class="btn btn-primary" href="<?=URL::to('/');?>">进入首页</a>
</div>

<?php $this->extend('layouts/footer');?>