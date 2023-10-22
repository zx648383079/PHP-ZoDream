<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */

$this->title = '消息发送测试';
?>

<h1><?=$this->title?></h1>
<?=Form::open('./@admin/option/debug')?>
     <?=Theme::text('target', '', '收件人', '请输入手机号/邮箱地址', true)?>
     <?=Theme::text('title', '', '标题', '', true)?>
     <?=Theme::radio('type', ['TEXT', 'HTML'], 0, '内容类型')?>
     <?=Theme::textarea('content', '', '内容', '请输入文字内容', true)?>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">发送</button>
        <a class="btn btn-info" href="<?=$this->url('./@admin/option/mail')?>">邮箱配置</a>
        <a class="btn btn-primary" href="<?=$this->url('./@admin/option/sms')?>">SMS配置</a>
    </div>
<?= Form::close() ?>