<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '帐号信息';
?>
<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div class="panel history-panel">
            <div class="panel-header">
                <span>帐号信息</span>
            </div>
            <div class="panel-body">
            <?=Form::open('./member/save_profile')?>
                <div class="input-group">
                    <label for="avatar">用户头像</label>
                    <div class="avatar-input">
                        <img src="<?=auth()->user()->avatar?>" alt="">
                        <i class="fa fa-camera"></i>
                    </div>
                </div>
                <?=Form::text('帐　　号', true)?>
                <?=Form::text('昵　　称', true)?>
                <?=Form::radio('性　　别', ['男', '女'])?>
                <?=Form::text('手机号码', true)?>
                <?=Form::text('出生日期', true)?>
                
                <div class="offset-md-3">
                    <button class="btn">保存</button>
                </div>
                
            <?=Form::close('id')?>
            </div>
        </div>
    </div>
</div>
