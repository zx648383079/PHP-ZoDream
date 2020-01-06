<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */

$this->title = __('friend link');
$this->set([
    'keywords' => '友情链接,友链',
    'description' => '申请前请收录本站，接受新站，拒绝一切非法网站。'
]);

$js = <<<JS
var dialog = $('.apply-dialog').dialog();
dialog.on('done', function() {
    this.find('form').trigger('submit');
    this.hide();
});
$(".friend-apply .btn").click(function () {
    dialog.show();
});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="container">
    <div class="friend-box">
        <?=$this->node('friend-link')?>
    </div>
    
    <div class="friend-apply">
        <p><?=__('friend link tip')?></p>
        <a href="javascript:;" class="btn btn-show"><?=__('Apply Excharge Link')?></a>
    </div>
</div>

<div class="dialog dialog-box apply-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title"><?=__('Apply Excharge Link')?></div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <?= Form::open('/contact/home/friend_link', 'POST', ['data-type' => 'ajax',]) ?>
            <div>*<?=__('Site Name')?>:</div>
            <div>
                <input type="text" name="name" placeholder="显示的网站名称" required>
            </div>
            <div>*<?=__('URL')?>:</div>
            <div>
                <input type="text" name="url" placeholder="跳转的网站链接" required>
            </div>
            <div><?=__('Site Description')?>:</div>
            <div>
                <input type="text" name="brief" placeholder="网站简介">
            </div>
            <div><?=__('Email')?>:</div>
            <div>
                <input type="email" name="email" placeholder="将发送结果到你的邮箱">
            </div>
        <?= Form::close() ?>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes"><?=__('Apply')?></button>
        <button type="button" class="dialog-close"><?=__('Cancel')?></button>
    </div>
    
</div>