<?php
use Zodream\Template\View;
/** @var $this View */
$this->extend('layouts/header');
?>
    <section class="container">
        <div class="login">
            <form class="form-ico login-form" action="<?= $this->url('/auth/home/login') ?>" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="请输入账号" required>
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="请输入密码" required>
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </div>

                <div class="input-group">
                    <div class="checkbox">
                        <input type="checkbox" name="rememberMe" value="1" id="checkboxInput"/>
                        <label for="checkboxInput"></label>
                    </div>
                    记住我
                </div>

                <button type="submit" class="btn">登录</button>
                <input type="hidden" name="redirect_uri" value="<?= $redirect_uri ?>">
            </form>
        </div>
    </section>
<?php
$this->extend('layouts/footer');
?>