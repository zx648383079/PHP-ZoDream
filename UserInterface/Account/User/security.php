<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Authentication\Auth;
/** @var $this \Zodream\Domain\Response\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'zodream/blog.css'
    )
);
?>

<div class="container">

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <?=Html::a('个人信息', 'user/info')?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('安全中心', ['user/security'])?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('隐私设置', ['user/setting'])?>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#phone" aria-controls="home" role="tab" data-toggle="tab">更换手机号</a>
                </li>
                <li role="presentation">
                    <a href="#email" aria-controls="profile" role="tab" data-toggle="tab">邮箱验证</a></li>
                <li role="presentation">
                    <a href="#password" aria-controls="messages" role="tab" data-toggle="tab">修改密码</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="phone">
                    <form method="post">
                        <input type="hidden" name="type" value="1">
                        <input type="text" class="form-control" name="phone" value="<?=Auth::user()['phone']?>">
                        <input type="text" class="form-control" name="phone" placeholder="新手机号码">
                        <input type="text" class="form-control" name="code" placeholder="验证码">
                        <button class="btn btn-primary">发送验证码</button>
                        <button type="submit" class="btn btn-primary">确定</button>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="email">
                    <form method="post">
                        <input type="hidden" name="type" value="2">
                        <input type="email" class="form-control" name="email" value="<?=Auth::user()['email']?>">
                        <button type="submit" class="btn btn-primary">确定</button>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="password">
                    <form method="post">
                        <input type="hidden" name="type" value="3">
                        <input type="password" class="form-control" name="oldpassword" placeholder="原密码">
                        <input type="password" class="form-control" name="password" placeholder="新密码">
                        <input type="password" class="form-control" name="repassword" placeholder="再次确认">
                        <button type="submit" class="btn btn-primary">确定</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
