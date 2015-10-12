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
<div class="fixed-height">
  <form class="center" action="<?php App::url('?c=auth&v=register'); ?>" method="POST">
	<input type="text" name="name" value="<?php App::ech('name');?>" placeholder="用户名" required>
	<input type="email" name="email" value="<?php App::ech('email');?>" placeholder="邮箱" required>
	<input type="password" name="pwd" placeholder="密码" required>
	<input type="password" name="cpwd" placeholder="确认密码" required>
	<p class="fail"><?php App::ech('error'); ?></p>
	<button type="submit">注册</button>
	<a href="<?php App::url('?c=auth'); ?>">登录</a>
 </form>
 </div>
</div>
  
	
	
<?php App::extend('~layout.foot');?>