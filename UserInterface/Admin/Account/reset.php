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
  <h2 class="form-heading">重置密码</h2>
  <form class="form-signin app-cam" action="<?php $this->url();?>" method="POST">
      <input type="password" class="form-control1" name="password" placeholder="新密码">
      <input type="password" class="form-control1" name="cpassword" placeholder="确认密码">
      <button class="btn btn-lg btn-success1 btn-block" type="submit">重置</button>
      <div class="registration">
          返回
          <a class="" href="<?php $this->url('account');?>">
              登录
          </a>
      </div>
  </form>
  
<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
