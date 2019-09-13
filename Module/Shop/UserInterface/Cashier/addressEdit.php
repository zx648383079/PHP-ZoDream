<?php
defined('APP_DIR') or exit();
/** @var $this View */
?>
<div class="address-edit">
    <div class="edit-body">
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
    <div class="edit-right">
        <div>
            <div class="checkbox">
                <input type="checkbox" name="is_default" value="1" id="checkboxInput" checked>
                <label for="checkboxInput"></label>
            </div>
            设为默认
        </div>
        <button type="button" class="btn">保存地址</button><button type="button" class="btn btn-cancel">取消</button>
    </div>
</div>