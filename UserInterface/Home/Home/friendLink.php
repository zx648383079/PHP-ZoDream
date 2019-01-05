<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '友情链接';

$js = <<<JS
var dialog = $('.apply-dialog').dialog();
dialog.on('done', function() {
    this.find('form').submit();
    //this.hide();
});
$(".friend-apply .btn").click(function () {
    dialog.show();
});
JS;
$this->registerCssFile('@dialog.css')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJs($js, View::JQUERY_READY)
    ->extend('layouts/header');
?>

<div class="container">
    <div class="friend-box">
        <?=$this->node('friend-link')?>
    </div>
    
    <div class="friend-apply">
        <p>申请前请收录本站，接受新站，拒绝一切非法网站。</p>
        <a href="javascript:;" class="btn btn-show">申请互换友链</a>
    </div>
</div>

<div class="dialog dialog-box apply-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">申请互换友链</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <form action="" method="post">
            <div>*站点名:</div>
            <div>
                <input type="text" name="name" required>
            </div>
            <div>*网址:</div>
            <div>
                <input type="text" name="url" required>
            </div>
            <div>简介:</div>
            <div>
                <input type="text" name="brief">
            </div>
        </form>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">申请</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
    
</div>

<?php $this->extend('layouts/footer')?>