<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑地址';

$header_btn = <<<HTML
<a class="btn" href="javascript:$('form').submit();">
    保存
</a>
HTML;
$this->extend('../layouts/header', compact('header_btn'));
?>

<div class="has-header">
    <form class="form-inline" data-type="ajax" action="<?=$this->url('./mobile/address/save')?>" method="post">
        <div class="input-group">
            <input type="text" name="name" placeholder="收货人" required  value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <input type="text" name="tel" placeholder="手机号" required value="<?=$model->tel?>">
        </div>
        <div class="input-group">
            <span>地址</span>
            <input type="hidden" name="region_id" value="<?=$model->region_id ?: 1?>">
        </div>
        <div class="input-group">
            <textarea name="address" placeholder="详细地址" required><?=$model->address?></textarea>
        </div>

        <div class="input-radio">
            <span>设为默认地址</span>
            <i class="fa toggle-box"></i>
        </div>

        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>

<div class="fixed-footer">
    <button class="btn" type="button">删除地址</button> 
</div>
