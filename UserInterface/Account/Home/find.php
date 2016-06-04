<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\Response\View */
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
        <input type="email" name="email"  required placeholder="邮箱"> </br>
        <p class="text-danger"><?=$this->get('message')?></p>
        <button type="submit">发送验证邮件</button>
        <p>返回 <?=Html::a('登录', '/')?> 或  <?=Html::a('注册', 'account/register')?> </p>
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