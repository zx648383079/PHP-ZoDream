<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('../layouts/header_main');
?>

<div class="has-header">
    <div class="login-type-box">
        <div class="logo">
            <img src="/assets/images/wap_logo.png" alt="">
        </div>
        <a href="" class="btn">手机号登录</a>
        <a href="" class="btn btn-none">邮箱登录</a>
        <a href="">手机号快捷注册</a>

        <div class="login-oauth-box">
            <a href="<?=$this->url('./oauth', ['type' => 'qq', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-qq"></i></a>
            <a href="<?=$this->url('./oauth', ['type' => 'wechat', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-weixin"></i></a>
            <a href="<?=$this->url('./oauth', ['type' => 'weibo', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-weibo"></i></a>
            <a href="<?=$this->url('./oauth', ['type' => 'github', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-github"></i></a>
        </div>
    </div>
    <div class="login-box hide">
        <div class="logo">
            <img src="/assets/images/wap_logo.png" alt="">
        </div>
        <div class="phone-code">
            <div class="input-box">
                <input type="text">
            </div>
            <div class="code-input">
                <input type="text">
                <a href="">获取验证码</a>
            </div>
            <div class="unlogin">
                <a href="">遇到问题？</a>
                <a href="">使用密码验证登录</a>
            </div>
            <button>登录</button>
            <a href="" class="btn btn-none">其他登录方式</a>
        </div>
        <div class="phone-password hide">
            <div class="input-box">
                <input type="text">
            </div>
            <div class="input-box">
                <input type="password">
            </div>
            <div class="unlogin">
                <a href="">忘记密码</a>
                <a href="">使用短信验证登录</a>
            </div>
            <button>登录</button>
            <a href="" class="btn btn-none">其他登录方式</a>
        </div>
        <div class="email-password hide">
            <div class="input-box">
                <input type="text">
            </div>
            <div class="input-box">
                <input type="password">
            </div>
            <div class="unlogin">
                <a href="">注册账号</a>
                <a href="">忘记密码</a>
            </div>
            <button>登录</button>
            <a href="" class="btn btn-none">其他登录方式</a>
        </div>
    </div>
    <div class="register-box hide">
        <div class="input-box">
            <input type="text">
        </div>
        <div class="code-input">
            <input type="text">
            <a href="">获取验证码</a>
        </div>
        <div class="input-box">
            <input type="password">
        </div>
        <button>注册</button>
        <div class="input-group">
            <div class="checkbox">
                <input type="checkbox" name="agree" value="1" id="checkboxInput"/>
                <label for="checkboxInput"></label>
            </div>
            同意本站协议
        </div>
    </div>

</div>