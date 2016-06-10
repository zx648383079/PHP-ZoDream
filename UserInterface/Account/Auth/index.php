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
$code = $this->get('code');
?>

<div class="login">
    <h2 class="text-center">后台登录</h2>
    <form method="POST">
        <input type="email" id="email" name="email" value="" class="form-control" required placeholder="邮箱">
        <input type="password" name="password" class="form-control" value="" required placeholder="密码">
        <?php if (!is_null($code)) {?>
        <input type="text" name="code" class="form-control code" required placeholder="验证码">
        <img id="verify" src="<?php $this->url('verify');?>" title="验证码"> </br>
        <?php }?>
        <input type="checkbox" name="remember" value="1">记住我</br>
        <p class="text-danger"><?=$this->get('message')?></p>
        <button class="btn btn-primary" type="submit">登录</button>
        <p><?=Html::a('忘记密码?', 'auth/find')?> 或 没有账号？先 <a href="<?php $this->url('auth/register');?>">注册</a>  </p>
    </form>
    <div class="row oauth">
        <span>其他方式登录：</span>
        <a href="javascript:;" class="qq" title="QQ"></a>
        <a href="javascript:;" class="weibo" title="微博"></a>
        <a href="javascript:;" class="baidu" title="百度"></a>
        <a href="javascript:;" class="weixin" title="微信"></a>
        <a href="javascript:;" class="github" title="Github"></a>
    </div>
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