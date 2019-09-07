<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="product-box">
    <?php foreach($model_list as $item):?>
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
    <?=$model_list->getLink()?>
</div>

<div id="invest-dialog" class="dialog dialog-content" data-type="dialog">
    <div class="dialog-body">
        <p class="tip">请输入投资金额</p>
        <input type="text" name="money" placeholder="整数金额">
    </div>
    <div class="dialog-footer">
        <button class="dialog-yes">投资</button>
        <button class="dialog-close">取消</button>
    </div>
</div>
