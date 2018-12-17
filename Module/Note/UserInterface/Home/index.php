<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '便签';
?>

<div class="search-box">
    <form action="">
        <div class="search-input">
            <button><i class="fa fa-search"></i></button>
            <input type="text" name="keywords" placeholder="搜索">
        </div>
    </form>
</div>

<div class="flex-box">
    <?php if(true || !auth()->guest()):?>
    <div class="item new-item">
        <div class="item-content">
            <textarea placeholder="请输入内容"></textarea>
        </div>
    </div>
    <?php endif;?>
    <?php foreach($model_list as $item):?>
    <div class="item">
        <div class="item-content">
            <?=$item->html?>
        </div>
    </div>
    <?php endforeach;?>
</div>