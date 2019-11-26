<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="dialog dialog-box brand-dialog" data-type="dialog" data-url="<?=$this->url('./@admin/brand/dialog')?>">
    <div class="dialog-header">
        <div class="dialog-title">选择品牌</div><i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-search">
        <input type="text" name="keywords">
        <button>搜索</button>
    </div>
    <div class="dialog-body">
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>