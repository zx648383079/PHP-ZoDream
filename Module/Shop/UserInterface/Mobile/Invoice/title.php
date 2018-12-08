<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '发票抬头';
$create_url = $this->url('./mobile/invoice/create');

$js = <<<JS
$(".swipe-row").swipeAction();
JS;
$header_btn = <<<HTML
<a class="right" href="{$create_url}">
    <i class="fa fa-plus"></i>
</a>
HTML;

$this->extend('../layouts/header', compact('header_btn'))
    ->registerJsFile('@jquery.swipeAction.min.js')
    ->registerJs($js);
?>
<div class="has-header">
    <div class="swipe-box title-list">
        <?php foreach(range(1, 10) as $item):?>
        <div class="swipe-row">
            <div class="swipe-content title-item">
                <div class="name">公司</div>
                <p>发票类型：增值税普通发票</p>
                <p>税务登记号：11123545656</p>
            </div>
            <div class="actions-right">
                <a href="<?=$this->url('./mobile/invoice/edit', ['id' => $item])?>">
                    <i class="fa fa-edit"></i>
                </a>
                <i class="fa fa-trash"></i>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>