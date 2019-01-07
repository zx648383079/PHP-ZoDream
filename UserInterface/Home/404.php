<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '您访问的页面不存在';
?>

<div class="container page-not-found">
	<div class="content">
		<img src="<?=$this->asset('images/404.png')?>">
		<p><?=isset($message)? $message:':(很抱歉，您访问的页面不存在！'?></p>
		<p class="text-center">
			<a class="btn btn-show" href="<?=$this->url('/')?>">返回首页</a>
		</p>
	</div>
</div>
