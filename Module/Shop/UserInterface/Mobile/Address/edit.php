<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑地址';

$header_btn = <<<HTML
<a class="right" href="javascript:$('form').submit();">
    <i class="fa fa-check"></i>
</a>
HTML;
$url = $this->url('./region/tree');
$js = <<<JS
$('.region-box').select({
    default: {$model->region_id} + 0,
    column: 3,
    textTag: 'name',
    data: '{$url}',
    ondone: function (prov, city, dist, t1, t2, t3) { 
        $('.region-box span').text(t1 + ' ' + t2 + ' ' + t3);
        $('.region-box input').val(dist);
    }
});
JS;
$this->extend('../layouts/header', compact('header_btn'))
    ->registerCssFile('@dialog-select.css')
    ->registerJsFile('@jquery.selectbox.min.js')
    ->registerJs($js);
?>

<div class="has-header">
    <form class="form-inline" data-type="ajax" action="<?=$this->url('./mobile/address/save')?>" method="post">
        <div class="input-group">
            <input type="text" name="name" placeholder="收货人" required  value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <input type="text" name="tel" placeholder="手机号" required value="<?=$model->tel?>">
        </div>
        <div class="input-group region-box">
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
