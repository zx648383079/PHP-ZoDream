<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <?php if($player->house_id > 0):?>
        <li>您已有 <?=$player->house->name?></li>
        <?php else:?>
        <li>您还没有住宅，请先购买</li>
        <?php endif;?>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<a href="<?=$this->url('./')?>">返回</a>
<div class="house-box">
    <?php foreach($house_list as $item):?>
    <div class="item">
        <div class="name">
            <?=$item->name?>
            (价格：<?=$item->price?>)
        </div>
        <div class="action">
            <?php if($item->id === $player->house_id):?>
            <span>已拥有</span>
            <?php else:?>
            <a data-type="ajax" href="<?=$this->url('./house/buy', ['id' => $item->id])?>">购买</a>
            <?php endif;?>
        </div>
    </div>
    <?php endforeach;?>
</div>


