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
        <div class="address-box">
            <div class="header">
                <span>bbb</span>
                <a href="">
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
            <div class="address-row">
                <div>1312</div>
                <div>123123</div>
                <div>23123</div>
                <div>
                    <a href="">编辑</a>
                    <a href="">删除</a>
                </div>
                <div>
                    <a href="">设为默认地址</a>
                </div>
            </div>
            <div class="address-row">
                <div>1312</div>
                <div>123123</div>
                <div>23123</div>
                <div>
                    <a href="">编辑</a>
                    <a href="">删除</a>
                </div>
                <div>
                    <span class="btn">默认地址</span>
                </div>
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