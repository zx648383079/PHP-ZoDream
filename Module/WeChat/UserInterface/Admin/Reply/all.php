<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '群发消息';
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>群发消息，支持群发所有或但用户</li>
    </ul>
    <span class="toggle"></span>
</div>

<?=Form::open('./admin/reply/send_all')?>
    <?=Theme::select('user_id', [$user_list, [0 => '发送全部']], $user_id, '接收方')?>
    <?php $this->extend('../layouts/editor', [
        'tab_id' => [0, 1, 2, 3]
    ]); ?>
    <button type="submit" class="btn btn-success">确认发送</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消发送</a>
<?= Form::close() ?>