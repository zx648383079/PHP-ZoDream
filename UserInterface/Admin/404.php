<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@error.css');
?>

<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<img src="<?=$this->getAssetFile('images/404.png')?>">
			<p><?= $message ?? ':(很抱歉，您访问的页面不存在！' ?></p>
			<p class="text-center home"><a href="/admin.php">返回首页</a></p>
		</div>
	</div>
</div>

