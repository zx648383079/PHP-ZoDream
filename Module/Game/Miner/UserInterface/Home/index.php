<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>先买房</li>
        <li>再雇工</li>
        <li>再出工</li>
        <li>最后收钱</li>
    </ul>
    <span class="toggle"></span>
</div>
<a href="<?=$this->url('./miner')?>">雇佣矿工</a>
<h4>[<?=$player->name?>](<?=$miner_list->getTotal()?>/<?=$player->house->amount?>)</h4>
<div class="miner-box">
    <?php foreach($miner_list as $item):?>
    <div class="item">
        <div class="name">
            <?=$item->miner->name?>
            (体力：<?=$item->ps?>)
        </div>
        <div class="action">
            <?php if($item->status < 1):?>
            <a data-type="work" href="<?=$this->url('./miner/work', ['id' => $item->id])?>">上工</a>
            <a data-type="del" href="<?=$this->url('./miner/fire', ['id' => $item->id])?>">解雇</a>
            <?php else:?>
            <a data-type="ajax" href="<?=$this->url('./miner/balance', ['id' => $item->id])?>">召回(钱<?=$item->income?>)</a>
            <?php endif;?>
        </div>
    </div>
    <?php endforeach;?>
</div>

<div align="center">
    <?=$miner_list->getLink()?>
</div>

<div class="dialog dialog-box area-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择矿场</div><i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="area-box">
            <?php foreach($area_list as $item):?>
            <div class="item" data-id="<?=$item->id?>">
                <div class="name">
                    <?=$item->name?>
                    (价格：<?=$item->price?>)
                </div>
                <div class="action">
                    <a href="javascript:;">选择</a>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
