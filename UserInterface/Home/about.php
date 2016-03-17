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
		<div class="ms-Grid-col ms-u-md8">
			<h3>关于</h3>
			<?php $this->ech('data.0.value');?>
		</div>
		<div class="ms-Grid-col ms-u-md4">
			<h4>个人简介</h4>
			<?php $this->ech('data.0.value');?>
		</div>
	</div>
	<hr>
	<div class="ms-Grid-row">
   		<div class="ms-Grid-col ms-u-md4">
				   <form method="post" action="<?php $this->url();?>">
					 <input type="text" name="name" class="textbox" placeholder="姓名" value="<?php $this->ech('name');?>">
					 <input type="email" name="email" class="textbox" placeholder="邮箱" value="<?php $this->ech('email');?>" required>
					 <input type="text" name="title" class="textbox" placeholder="标题">
					 <textarea name="content" placeholder="内容" required></textarea>
					 <input type="submit" value="发送">
				   </form>
		</div>
   		<div class="ms-Grid-col ms-u-md8">
			<iframe src="/map.html" frameborder="0" style="border:0"></iframe>
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