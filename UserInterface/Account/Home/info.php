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
            <?=
            FormWidget::begin(Auth::user()->get())
                ->html('avatar', function ($avatar) {
                    return <<<HTML
<div class="col-md-offset-2">
<img src="{$avatar}" alt="头像" class="img-rounded">
<input type="hidden" name="avatar" value="{$avatar}">                    
</div>

HTML;

                })
                ->text('name', ['label' => '昵称'])
                ->email('email', ['label' => '邮箱'])
                ->radio('sex', ['label' => '性别', 'value' => '男'])
                ->radio('sex', ['label' => '性别', 'value' => '女'])
                ->button()
                ->end()
            ?>
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
