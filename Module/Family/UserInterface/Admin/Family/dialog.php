<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="dialog dialog-box family-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择族人
            <i class="fa fa-plus"></i>
        </div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="dialog-list">

        </div>
        <div class="dialog-new">
            <?=Form::open('./@admin/clan/save')?>
                <?=Form::text('name', true)?>
                <?=Form::text('secondary_name')?>
                <?=Form::select('sex', ['其他', '女', '男'], true)?>
            <?= Form::close() ?>
        </div>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>