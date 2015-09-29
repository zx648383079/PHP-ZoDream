<?php 
App::extend(array(
	'~layout'=>array(
		'head',
		'nav'
		)
	)
);
?>
<div class="container">
  <form class="method" action="<?php App::url(); ?>" method="POST">
	标&nbsp;&nbsp;&nbsp;题：<input type="text" name="title" value="<?php App::ech('data.title');?>" placeholder="标题" required><br>
	关键字：<input type="text" name="keys" value="<?php App::ech('data.keys');?>" placeholder="关键字" required><br>
	分&nbsp;&nbsp;&nbsp;类：<select name="kind" value="<?php App::ech('data.keys');?>">
	<option value="0">PHP</option>
	<option value="1">C#</option>
	<option value="2">JS</option>
	</select><br>
	<textarea name="content" placeholder="Method" rows="20" required><?php App::ech('data.content');?></textarea>
	<p class="fail"><?php App::ech('error'); ?></p>
	<button type="submit">提交</button>
 </form>
</div>
  
	
	
<?php App::extend('~layout.foot');?>