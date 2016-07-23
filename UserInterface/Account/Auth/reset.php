<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
	'layout' => array(
		'head'
	)), array(
        'zodream/login.css'
    )
);
?>

<div class="login">
    <h2 class="text-center">重置密码</h2>
    <form method="POST">
        <input type="hidden" name="email" value="<?=$this->gain('email')?>">
        <input type="hidden" name="token" value="<?=$this->gain('token')?>">
        <input type="password" class="form-control" name="password" value="" required placeholder="密码"> </br>
        <input type="password" class="form-control" name="repassword" value="" required placeholder="重复密码"> </br>
        <p class="text-danger"><?=$this->gain('message')?></p>
        <button class="btn btn-primary" type="submit">重置</button>
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