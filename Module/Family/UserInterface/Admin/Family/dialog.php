<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;

/** @var $this View */
?>
<div class="dialog dialog-box family-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择族人
        </div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="dialog-list">
            <form class="form-horizontal" role="form" action="<?=$this->url('./@admin/family')?>">
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
                <a href="" data-action="add">新增</a>
            </form>
            <div class="dialog-result"></div>
        </div>
        <div class="dialog-new">
            <a href="" data-action="back">&lt;&lt;返回</a>
            <?=Form::open('./@admin/family/save')?>
                <?=Theme::text('name', '', '姓名', '', true)?>
                <?=Theme::text('secondary_name', '', '表字')?>
                <?=Theme::select('sex', ['其他', '女', '男'], 1, '性别')?>
                <?=Theme::textarea('lifetime', '', '生平')?>
            <?= Form::close() ?>
        </div>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>