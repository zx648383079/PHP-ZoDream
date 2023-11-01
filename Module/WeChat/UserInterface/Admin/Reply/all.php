<?php
defined('APP_DIR') or exit();

use Module\WeChat\Domain\Model\ReplyModel;
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '群发消息';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>群发消息，支持群发所有或但用户</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<?=Form::open('./@admin/reply/send_all')?>
    <?=Theme::select('user_id', [$user_list, [0 => '发送全部']], $user_id, '接收方')?>
    <?php $this->extend('../layouts/editor', [
            'model' => new ReplyModel(),
    ]); ?>
    <button type="submit" class="btn btn-success">确认发送</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消发送</a>
<?= Form::close() ?>