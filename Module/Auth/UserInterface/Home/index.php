<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$url = $this->url('./', false);
$js = <<<JS
bindLogin('{$url}');
JS;

$this->extend('layouts/header')
    ->registerJs($js);
?>
    <section class="container">
        <div class="login-box">
            <form class="form-ico login-form" action="<?= $this->url('./home/login', false) ?>" method="POST">
                <div class="input-box">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="请输入账号" required autocomplete="off">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="请输入密码" required autocomplete="off">
                        <i class="fa fa-lock" aria-hidden="true" ></i>
                    </div>

                    <div class="input-group">
                        <div class="checkbox">
                            <input type="checkbox" name="rememberMe" value="1" id="checkboxInput"/>
                            <label for="checkboxInput"></label>
                        </div>
                        记住我
                        <a class="find-link" href="<?= $this->url('./password/find') ?>">找回密码</a>
                    </div>

                    <button type="submit" class="btn">登录</button>
                    <div class="other-box">
                        <a href="<?= $this->url('./register') ?>">注册账号</a>
                        <i class="fa fa-qrcode"></i>
                    </div>

                    <input type="hidden" name="redirect_uri" value="<?= $redirect_uri ?>">
                </div>
                <div class="login-qr-box">
                    <div class="qr-box">
                        <img lazy-src="<?=$this->url('./qr')?>" alt="">
                        <i class="fa fa-sync"></i>
                    </div>
                    <div class="scan-box">
                        <i class="fa fa-check-circle"></i>
                        <p>扫描成功，等待确认</p>
                    </div>
                    <div class="success-box">
                        <i class="fa fa-check-circle"></i>
                        <p>登陆成功</p>
                    </div>
                    <div class="failure-box">
                        <i class="fa fa-sync"></i>
                        <p>登陆失败</p>
                    </div>
                    <button type="button" class="btn">返回登录</button>
                </div>
            </form>
            <div class="login-oauth-box">
                <div class="box-title">第三方登录</div>
                <a href="<?=$this->url('./oauth', ['type' => 'qq', 'redirect_uri' => $redirect_uri], false)?>" title="QQ登录"><i class="fab fa-qq"></i></a>
                <a href="<?=$this->url('./oauth', ['type' => 'wechat', 'redirect_uri' => $redirect_uri], false)?>" title="微信登录"><i class="fab fa-weixin"></i></a>
                <a href="<?=$this->url('./oauth', ['type' => 'weibo', 'redirect_uri' => $redirect_uri], false)?>" title="微博登录"><i class="fab fa-weibo"></i></a>
                <a href="<?=$this->url('./oauth', ['type' => 'github', 'redirect_uri' => $redirect_uri], false)?>" title="GITHUB登录"><i class="fab fa-github"></i></a>
                <a class="login-webauth" href="javascript:;" title="生物识别">
                    <i class="fa fa-fingerprint"></i>
                </a>
            </div>
        </div>
    </section>
<?php
$this->extend('layouts/footer');
?>