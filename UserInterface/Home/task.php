<?php
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
//$page = $this->get('page');
?>
<div class="ms-Grid">
	<div class="ms-Grid-row">
		<h3>任务列表</h3>

	</div>
	<hr>
	<div class="ms-Grid-row">
		<h3>提交任务</h3>
		<form method="post" action="<?php $this->url();?>">
			<input type="text" name="name" class="textbox" placeholder="名称" value="<?php $this->ech('name');?>">
			<textarea name="content" placeholder="详情"></textarea>
			<p class="text-danger"><?php $this->ech('status'); ?></p>
			<input type="submit" value="提交">
		</form>
   	</div>
</div>
<?php
$this->extend(array(
		'layout' => array(
				'foot'
		))
);
?>