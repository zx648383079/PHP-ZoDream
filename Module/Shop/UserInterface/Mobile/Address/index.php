<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的地址';
$js = <<<JS
$(".swipe-row").swipeAction();
JS;

$this->extend('../layouts/header')
    ->registerJsFile('@jquery.swipeAction.js')
    ->registerJs($js);
?>

<div class="has-header">
    <div class="swipe-box address-list">
        <?php foreach($model_list as $item):?>
        <div class="swipe-row">
            <div class="swipe-content address-item">
                <div class="address-first">
                    <span><?=$item->name?></span>
                </div>
                <div class="address-info">
                    <p>
                        <h4><?=$item->name?></h4>
                        <span><?=$item->tel?></span>
                    </p>
                    <p>
                        <span>默认</span>
                        <span><?=$item->region->full_name?> <?=$item->address?></span>    
                    </p>
                </div>
            </div>
            <div class="actions-right">
                <i class="fa fa-edit"></i>
                <i class="fa fa-trash"></i>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>

<?php $this->extend('../layouts/navbar');?>