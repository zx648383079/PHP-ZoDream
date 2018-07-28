<?php
use Zodream\Template\View;
/** @var $this View */
$this->extend('layouts/header');
?>
    <section class="container">
        <div class="login-box">
            <form class="form-ico login-form" action="<?= $this->url('./register/post') ?>" method="POST">
            <div class="input-group error">
                    <input type="text" placeholder="请输入账号" required>
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>
                <div class="input-group">
                    <input type="email" placeholder="请输入邮箱" required>
                    <i class="fa fa-at" aria-hidden="true"></i>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="请输入密码" required>
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="请确认密码" required>
                    <i class="fa fa-circle-o" aria-hidden="true"></i>
                </div>

                <div class="input-group">
                    <div class="checkbox">
                        <input type="checkbox" id="checkboxInput"/>
                        <label for="checkboxInput"></label>
                    </div>
                    同意本站协议
                </div>

                <button type="submit" class="btn">注册</button>
                <div class="other-box">
                    <a href="<?=$this->url('./')?>">返回登录</a>
                </div>
                
            </form>
            
        </div>
    </section>
<?php
$this->extend('layouts/footer');
?>