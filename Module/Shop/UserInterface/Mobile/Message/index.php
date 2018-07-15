<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的消息';

$js = <<<JS
$(".swipe-row").swipeAction();
JS;

$this->extend('../layouts/header')
    ->registerJsFile('@jquery.swipeAction.min.js')
    ->registerJs($js);
?>

<div class="has-header">
    <div class="swipe-box address-list">
        <?php foreach($model_list as $item):?>
        <div class="swipe-row">
            <div class="swipe-content address-item">
                <div class="address-first">
                    <span>系</span>
                </div>
                <div class="address-info">
                    <h3>213213213123</h3>
                    <p>
                        <?=date('Y-m-d H:i:s')?>
                    </p>
                </div>
            </div>
            <div class="actions-right">
                <i class="fa fa-trash"></i>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>
