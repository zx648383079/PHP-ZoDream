<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div>
            
        </div>
    </div>
</div>


<div class="dialog dialog-box address-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">新建地址</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div>*所在地区:</div>
        <div>
            <select name="" id=""></select>
            <select name="" id=""></select>
            <select name="" id=""></select>
        </div>
        <div>*详细地址:</div>
        <div>
            <textarea name="" id="" cols="30" rows="10"></textarea>
        </div>
        <div>*收货人:</div>
        <div>
            <input type="text">
        </div>
        <div>*手机号码:</div>
        <div>
            <input type="text">
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