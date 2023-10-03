<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '找回密码';
?>
<section class="container">
    <div class="login-box">
        <form class="form-ico login-form" action="<?= $this->url('./password/reset', false) ?>" method="POST">
            <div class="input-group">
                <input type="email" placeholder="请输入邮箱" name="email" required>
                <i class="fa fa-at" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="password" placeholder="请输入密码" name="password" required>
                <i class="fa fa-lock" aria-hidden="true"></i>
            </div>
            <div class="input-group">
                <input type="password" placeholder="请确认密码" name="rePassword" required>
                <i class="fa fa-circle" aria-hidden="true"></i>
            </div>

            <button type="submit" class="btn btn-full">确定修改</button>
            <input type="hidden" name="code" value="<?=$code?>">
        </form>
        
    </div>
</section>