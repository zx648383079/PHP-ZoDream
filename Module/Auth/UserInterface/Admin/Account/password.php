<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */

$this->title = '更改密码';

?>

<h1><?=$this->title?></h1>
<?= Form::open('./admin/account/update_password') ?>
    <?= Theme::password('old_password', '原密码', '输入原密码', true) ?>
    <?= Theme::password('password', '新密码', '输入新密码', true) ?>
    <?= Theme::password('confirm_password', '确认密码', '确认密码', true) ?>

    <button type="submit" class="btn btn-success">确认更改</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close() ?>