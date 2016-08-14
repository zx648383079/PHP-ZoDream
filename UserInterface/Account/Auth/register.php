<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
$this->registerCssFile('zodream/login.css');
$this->registerJs('require(["admin/login"]);');
$this->extend('layout/head');
?>

<div class="login">
    <h2 class="text-center">用户注册</h2>
    <form method="POST">
        <input type="text" name="name" class="form-control" value="" required placeholder="用户名">
        <input type="email" id="email" class="form-control" name="email" value="" required placeholder="邮箱">
        <input type="password" name="password" class="form-control" value="" required placeholder="密码">
        <input type="password" name="repassword" class="form-control" value="" required placeholder="重复密码">
        <input type="checkbox" name="agree" value="1" checked>同意"
        <a href="javascript:0;" data-toggle="modal" data-target="#service">服务条款</a>"和"<a href="javascript:0;" data-toggle="modal" data-target="#rule">隐私相关政策</a>"
        <button class="btn btn-primary" type="submit">注册</button>
        <p>返回 <?=Html::a('登录', 'auth')?></p>
    </form>
</div>

    <!-- Modal -->
    <div class="modal fade" id="service" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">服务条款</h4>
                </div>
                <div class="modal-body">
                    本网站的服务条款
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rule" tabindex="-1" role="dialog" aria-labelledby="ruleLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="ruleLabel">隐私相关政策</h4>
                </div>
                <div class="modal-body">
                    本网站的隐私
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
                </div>
            </div>
        </div>
    </div>

<?php $this->extend('layout/foot')?>