<?php
use Zodream\Template\View;
/** @var $this View */
$this->title = '授权登录';
$this->extend('layouts/header');
?>
    <section class="container">
        <div class="login-box">
            <form class="form-ico" action="<?= $this->url('./qr/authorize', false) ?>" method="POST">
                <div class="avatar">
                    <img src="<?=$user ? $user->avatar : '/assets/images/avatar/1.png'?>">
                </div>
                <div class="name">
                    您正在授权使用此账号登录电脑端！
                </div>

                <button type="submit" class="btn" name="confirm" value="1">确认登录电脑端</button>
                <button type="submit" class="btn" name="reject" value="1">取消登录</button>
                <input type="hidden" name="token" value="<?=$token?>">
            </form>
            
        </div>
    </section>
<?php
$this->extend('layouts/footer');
?>