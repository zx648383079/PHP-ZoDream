<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Html\Bootstrap\FormWidget;
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
                    <?=Html::a('个人信息', 'info')?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('安全中心', ['security'])?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('隐私设置', ['setting'])?>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#phone" aria-controls="phone" role="tab" data-toggle="tab">更换手机号</a>
                </li>
                <li role="presentation">
                    <a href="#email" aria-controls="email" role="tab" data-toggle="tab">邮箱验证</a></li>
                <li role="presentation">
                    <a href="#password" aria-controls="password" role="tab" data-toggle="tab">修改密码</a></li>
                <li role="presentation">
                    <a href="#merge" aria-controls="merge" role="tab" data-toggle="tab">账号合并</a></li>
                <li role="presentation">
                    <a href="#cancel" aria-controls="cancel" role="tab" data-toggle="tab">账号注销</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="phone">
                    <?=FormWidget::begin()
                        ->hidden('type', ['value' => 1])
                        ->text('phone', ['value' => Auth::user()['phone']])
                        ->text('newphone', ['placeholder' => '新手机号码'])
                        ->html('<input type="text" class="form-control" name="code" placeholder="验证码">
                        <button class="btn btn-primary">发送验证码</button>')
                        ->button('确定')
                        ->end();?>
                </div>
                <div role="tabpanel" class="tab-pane" id="email">
                    <?=FormWidget::begin()
                        ->hidden('type', ['value' => 2])
                        ->text('email', ['value' => Auth::user()['email']])
                        ->button('确定')
                        ->end();?>
                </div>
                <div role="tabpanel" class="tab-pane" id="password">
                    <?=FormWidget::begin()
                        ->hidden('type', ['value' => 3])
                        ->password('oldpassword', ['placeholder' => '原密码'])
                        ->password('password', ['placeholder' => '新密码'])
                        ->password('repassword', ['placeholder' => '再次确认'])
                        ->button('确定')
                        ->end();?>
                </div>
                <div role="tabpanel" class="tab-pane" id="merge">
                    <p class="text-danger">合并其他账号将以本账号的资料为主</p>
                    <?=FormWidget::begin()
                        ->hidden('type', ['value' => 4])
                        ->email('email', ['placeholder' => '其他账号邮箱'])
                        ->password('password', ['placeholder' => '密码'])
                        ->button('确定')
                        ->end();?>
                </div>
                <div role="tabpanel" class="tab-pane" id="cancel">
                    <p class="text-danger">注销本账号将会删除本账号的一切信息</p>
                    <?=FormWidget::begin()
                        ->hidden('type', ['value' => 5])
                        ->email('email', ['placeholder' => '邮箱'])
                        ->password('password', ['placeholder' => '密码'])
                        ->button('注销本账号', 'submit', ['class' => 'btn btn-danger'])
                        ->end();?>
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
