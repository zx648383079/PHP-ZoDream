<?php
use Zodream\Template\View;
/** @var $this View */
$this->title = '找回密码';
$this->extend('layouts/header');
?>
    <section class="container">
        <div class="login-box">
            <form class="form-ico login-form" action="<?= $this->url('./password/send') ?>" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="请输入邮箱" required>
                    <i class="fa fa-at" aria-hidden="true"></i>
                </div>

                <button type="submit" class="btn btn-full">发送验证邮件</button>
                <div class="other-box">
                    <a href="<?=$this->url('./')?>">返回登录</a>
                </div>
            </form>
            
        </div>
    </section>
<?php
$this->extend('layouts/footer');
?>