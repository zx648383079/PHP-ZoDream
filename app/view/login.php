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
  <form class="center" action="<?php App::url('?c=auth'); ?>" method="POST">
    <input type="email" name="email" placeholder="邮箱" value="<?php App::ech('email'); ?>" required>
    <input type="password" name="pwd" placeholder="密码" required>
	<input type="checkbox" name="remember" value="1">记住我
	<p class="fail"><?php App::ech('error'); ?></p>
    <button>Sign in</button>
	<a href="<?php App::url('?c=auth&v=register'); ?>">注册</a>
  </form>
 </div>
</div>

<?php App::extend('~layout.foot');?>