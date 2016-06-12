<?php
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	)), array(
		'zodream/error.css'
	), false
);
?>

<div class="container">
	<div class="center">
		<h1><?php $this->ech('status', 404);?></h1>
		<p><?php $this->ech('message', '页面已丢失！');?></p>
		<a href="<?php $this->url('/');?>">返回首页</a>
	</div>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>
