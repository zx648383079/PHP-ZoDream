<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="product-box">
    <?php foreach($miner_list as $item):?>
    <a class="item" data-min="<?=$item->min_amount?>" href="<?=$this->url('./invest', ['id' => $item->id])?>">
        <div class="name"><?=$item->name?></div>
        <div class="column">
            <em><?=$item->earnings / 100?>%</em>
            <p>收益率</p>
        </div>
        <div class="column">
            <em><?=$item->cycle?>天</em>
            <p>周期</p>
        </div>
    </a>
    <?php endforeach;?>
</div>

<div align="center">
    <?=$miner_list->getLink()?>
</div>
