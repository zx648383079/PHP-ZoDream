<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream 登录';
?>
<div class="login-page" style="background-image: url(https://yanxuan.nosdn.127.net/6c20940c7b36612b0ed4d7b0a8a4aafe.jpg);">
    <div class="container">
        <div class="page-box">
            <div class="login-box">
                <div class="box-main">
                    <div class="box-header">
                        <a href="" class="active">手机号登录</a>
                        <a href="">邮箱登录</a>
                    </div>
                    <div class="box-tab">
                        <div class="tab-item active">
                            <form data-type="ajax" action="<?= $this->url('/auth/login') ?>" method="POST">
                                <div class="input-group">
                                    <input type="email" name="email" placeholder="请输入账号" required autocomplete="off">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </div>
                                <div class="input-hr"></div>
                                <div class="input-group">
                                    <input type="password" name="password" placeholder="请输入密码" required autocomplete="off">
                                    <i class="fa fa-lock" aria-hidden="true" ></i>
                                </div>
                                <button class="btn">登录</button>
                                <input type="hidden" name="redirect_uri" value="<?= $redirect_uri ?>">
                            </form>
                        </div>
                        <div class="tab-item">
                            
                        </div>
                        <div class="tab-item">
                            
                        </div>
                    </div>
                    
                </div>
                <div class="box-footer">
                    <div class="login-oauth-box">
                        <a href="<?=$this->url('./oauth', ['type' => 'qq', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-qq"></i></a>
                        <a href="<?=$this->url('./oauth', ['type' => 'wechat', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-weixin"></i></a>
                        <a href="<?=$this->url('./oauth', ['type' => 'weibo', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-weibo"></i></a>
                        <a href="<?=$this->url('./oauth', ['type' => 'github', 'redirect_uri' => $redirect_uri])?>"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="register-box">

            </div>
        </div>
    </div>
</div>