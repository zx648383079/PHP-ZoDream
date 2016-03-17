<?php
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
?>
<div class="ms-Grid">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-mdpush3 ms-u-md6">
			<h1><?php $this->ech('status', 404);?></h1>
			<p><?php $this->ech('error', '页面已丢失！');?></p>
			<a href="<?php $this->url('/');?>">返回首页</a>
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