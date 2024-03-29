<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>您还没有矿工，请先雇佣</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<a href="<?=$this->url('./')?>">返回</a>
<div class="house-box">
    <?php foreach($miner_list as $item):?>
    <div class="item">
        <div class="name">
            <?=$item->name?>
            (价格：<?=$item->price?>)
        </div>
        <div class="action">
            <a data-type="ajax" href="<?=$this->url('./miner/hire', ['id' => $item->id])?>">雇佣</a>
        </div>
    </div>
    <?php endforeach;?>
</div>


