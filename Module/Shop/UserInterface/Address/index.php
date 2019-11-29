<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '收货地址';
$js = <<<JS
bindAddress();
JS;
$this->registerJs($js)
    ->registerJsFile('@jquery.multi-select.min.js');
?>

<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div class="address-box">
            <div class="header">
                <span>收货地址</span>
                <a class="add-btn" href="javascript:;">
                    <i class="fa fa-plus"></i>
                    新建地址
                </a>
            </div>
            <div class="address-row row-header">
                <div>收货人</div>
                <div>地址</div>
                <div>联系方式</div>
                <div>操作</div>
            </div>
            <div class="address-page-box">
                <?php $this->extend('./page');?>
            </div>
        </div>
    </div>
</div>


<div class="dialog dialog-box address-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">新建地址</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="address-body">
            <div>*所在地区:</div>
            <div class="region-input">
                <select name="" id=""></select>
                <select name="" id=""></select>
                <select name="" id=""></select>
            </div>
            <div>*详细地址:</div>
            <div>
                <textarea name="address" id="" cols="30" rows="10"></textarea>
            </div>
            <div>*收货人:</div>
            <div>
                <input type="text" name="name">
            </div>
            <div>*手机号码:</div>
            <div>
                <input type="text" name="tel">
                <input type="hidden" name="id">
            </div>
        </div>
    </div>
    <div class="dialog-footer">
        <div>
            <div class="checkbox">
                <input type="checkbox" name="is_default" value="1" id="checkboxInput" checked>
                <label for="checkboxInput"></label>
            </div>
            设为默认
        </div>
        <button type="button" class="dialog-yes">确认</button><button type="button" class="dialog-close">取消</button>
    </div>
</div>