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
                            <div class="input-group">
                                <input type="email" name="email" placeholder="请输入账号" required autocomplete="off">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>
                            <div class="input-hr"></div>
                            <div class="input-group">
                                <input type="password" name="password" placeholder="请输入密码" required autocomplete="off">
                                <i class="fa fa-lock" aria-hidden="true" ></i>
                            </div>
                        </div>
                        <div class="tab-item">
                            
                        </div>
                        <div class="tab-item">
                            
                        </div>
                    </div>
                    <button class="btn">登录</button>
                </div>
                <div class="box-footer">
                    <div class="login-oauth-box">
                        <a href="<?=$this->url('./oauth', ['type' => 'qq'])?>"><i class="fa fa-qq"></i></a>
                        <a href="<?=$this->url('./oauth', ['type' => 'wechat'])?>"><i class="fa fa-wechat"></i></a>
                        <a href="<?=$this->url('./oauth', ['type' => 'weibo'])?>"><i class="fa fa-weibo"></i></a>
                        <a href="<?=$this->url('./oauth', ['type' => 'github'])?>"><i class="fa fa-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="register-box">

            </div>
        </div>
    </div>
</div>