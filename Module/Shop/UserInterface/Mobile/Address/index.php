<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的地址';

$create_url = $this->url('./mobile/address/create');

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
    <div class="swipe-box address-list">
        <?php foreach($model_list as $item):?>
        <div class="swipe-row">
            <div class="swipe-content address-item<?=$selected == $item->id ? ' selected' : ''?>">
                <div class="address-first">
                    <span><?=$item->name?></span>
                </div>
                <div class="address-info">
                    <p>
                        <span class="name"><?=$item->name?></span>
                        <span class="tel"><?=$item->tel?></span>
                    </p>
                    <p>
                        <span class="default">默认</span>
                        <span><?=$item->region->full_name?> <?=$item->address?></span>    
                    </p>
                </div>
            </div>
            <div class="actions-right">
                <a href="<?=$this->url('./mobile/address/edit', ['id' => $item->id])?>">
                    <i class="fa fa-edit"></i>
                </a>
                <i class="fa fa-trash"></i>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
