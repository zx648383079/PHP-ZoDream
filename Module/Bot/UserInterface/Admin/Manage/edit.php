<?php
defined('APP_DIR') or exit();
use Module\Bot\Domain\Model\BotModel;
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑微信公众号';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>添加微信公众号</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<?=Form::open($model, './@admin/manage/save')?>
    <?=Form::text('name', true)?>
    <?=Form::text('token', true)?>
    <?=Form::text('account', true)?>
    <?=Form::text('original', true)?>
    <?=Form::select('type', BotModel::$types)?>
    <?=Form::text('appid', true)?>
    <?=Form::text('secret', true)?>
    <?=Form::text('aes_key', true)?>
    <?=Form::file('avatar', true)?>
    <?=Form::file('qrcode', true)?>
    <?=Form::text('address', true)?>
    <?=Form::textarea('description', true)?>
    <?=Form::text('username', true)?>
    <?=Form::text('password', true)?>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>
