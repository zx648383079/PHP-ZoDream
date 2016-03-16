<?php
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
?>
<div class="main">
		<div class="container">
			<div class="error-404 text-center">
				<h1><?php $this->ech('status', 404);?></h1>
				<p><?php $this->ech('error', '页面已丢失！');?></p>
				<a class="b-home" href="<?php $this->url('/');?>">返回首页</a>
			</div>
		</div>
	</div>
</div>	
<?php
$this->extend(array(
		'layout' => array(
				'foot'
		))
);
?>