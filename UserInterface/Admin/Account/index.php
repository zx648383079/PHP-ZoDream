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
  <h2 class="form-heading">登录</h2>
  <div class="app-cam">
	  <form action="<?php $this->url();?>" method="POST">
		<input type="email" class="text" name="email" value="<?php $this->ech('email');?>" placeholder="邮箱" required>
		<input type="password" name="password" placeholder="密码" required>
		<label class="checkbox-custom check-success">
          <input type="checkbox" name="remember" value="yes" id="checkbox1"> <label for="checkbox1">记住我！</label>
      	</label>
		  <p class="text-danger"><?php $this->ech('error');?></p>
		<div class="submit"><input type="submit" onclick="myFunction()" value="登录"></div>
		<ul class="new">
			<li class="new_left"><p><a href="<?php $this->url('account/send');?>">忘记密码 ?</a></p></li>
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