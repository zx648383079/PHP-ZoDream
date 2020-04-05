<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;

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
            <form class="form-horizontal" role="form">
                <div class="input-group">
                    <label for="keywords">姓名</label>
                    <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索姓名">
                </div>
                <div class="input-group">
                    <label>家族</label>
                    <select name="clan_id">
                        <option value="">请选择</option>
                        <?php foreach($clan_list as $item):?>
                        <option value="<?=$item->id?>"><?=$item->name?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <button type="submit" class="btn btn-default">搜索</button>
            </form>
            <div class="dialog-result"></div>
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