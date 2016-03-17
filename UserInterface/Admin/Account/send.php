<?php
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
?>

<body id="login">
  <div class="login-logo">
    <a href="<?php $this->url('/');?>"><img src="<?php $this->asset('admin/images/logo.png');?>" alt=""/></a>
  </div>
  <h2 class="form-heading">忘记密码</h2>
  <div class="app-cam">
	  <form action="<?php $this->url();?>" method="POST">
		<input type="email" class="text" name="email" value="<?php $this->ech('email');?>" placeholder="您的邮箱" required>
		<?php if ($this->has('email')) {?>
		<p class="text-danger">邮件已发送，请点击邮件中的链接更改密码！</p>
		<?php }?>
		<div class="submit"><input type="submit" onclick="myFunction()" value="发送邮件"></div>
		<ul class="new">
			<li class="new_left"><p><a href="<?php $this->url('account');?>">返回登录</a></p></li>
			<li class="new_right"><p class="sign">新的 ?<a href="<?php $this->url('account/register');?>"> 注册</a></p></li>
			<div class="clearfix"></div>
		</ul>
	</form>
  </div>
<?php 
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>