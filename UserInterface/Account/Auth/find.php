<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
	'layout' => array(
		'head'
	)), array(
        'zodream/login.css'
    )
);
?>

<div class="login">
    <h2 class="text-center">找回密码</h2>
    <form method="POST">
        <input type="email" id="email" class="form-control" name="email"  required placeholder="邮箱">
        <p class="text-danger"><?=$this->gain('message')?></p>
        <button class="btn btn-primary" type="submit">发送验证邮件</button>
        <p>返回 <?=Html::a('登录', 'auth')?> 或  <?=Html::a('注册', 'auth/register')?> </p>
    </form>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        '!js require(["admin/login"]);'
    )
);
?>