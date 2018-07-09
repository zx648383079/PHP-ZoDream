<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';

$js = <<<JS
$(".swipe-row").swipeAction();
<<<;

$this->extend('../layouts/header')
    ->registerJsFile('@jquery.swipeAction.js')
    ->registerJs($js);
?>

<div class="has-header">
    <div class="cart-box">
        <div class="swipe-box box">
            <div class="swipe-row">
                <div class="swipe-content">
                    这是一个人问题
                </div>
                <div class="actions-right">
                    <button>删除111111</button>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-footer">
        

    </div>
</div>

<?php $this->extend('../layouts/navbar');?>