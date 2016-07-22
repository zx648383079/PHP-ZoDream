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
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<img src="<?=$this->asset('images/404.png')?>">
				<p><?php $this->out('message', ':(很抱歉，您访问的页面不存在！');?></p>
				<p class="text-center home"><a href="<?php $this->url('/');?>">返回首页</a></p>
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