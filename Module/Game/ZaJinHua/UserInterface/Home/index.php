<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Game\ZaJinHua\Domain\Player;
/** @var $this View */
?>
<?php if($player && $player->room):?>
<p>当前奖池：<?=$player->room->pool?></p>
<p>当前注：<?=$player->room->min?></p>
<?php endif;?>
<?php if(!$player || $player->status === Player::STATUS_NONE):?>
<p>欢迎来到扎金花游戏</p>
<p>请输入底注：</p>
<div class="input-box" data-url="<?=$this->url('./do', ['action' => 'init'], false)?>">
    <?php foreach([10, 100, 200, 500, 1000, 2000] as $item):?>
    <a href="<?=$this->url('./do', ['action' => 'init', 'money' => $item], false)?>"><?=$item?></a>
    <?php endforeach;?>
    <input type="text" placeholder="自定义">
</div>
<?php elseif ($player->status === Player::STATUS_WAITE):?>
<p>请等待其他玩家</p>
<a href="">刷新</a>
<?php elseif ($player->status === Player::STATUS_DO):?>
<?php if($player->isWatch):?>
<p>您的牌为：
    <?php foreach($player->pokers as $item):?>
       <span><?=$item?></span>、
    <?php endforeach;?>
</p>
<?php endif;?>
<p>请选择你的操作</p>
<p>
    <a href="<?=$this->url('./do', ['action' => 'call'], false)?>">跟注</a>
    <?php if(!$player->isWatch):?>
    <a href="<?=$this->url('./do', ['action' => 'check'], false)?>">看牌</a>
    <?php endif;?>
    <a href="<?=$this->url('./do', ['action' => 'fold'], false)?>">弃牌</a>
</p>
<p>
    <span>加注</span>
    <div class="input-box">
        <?php foreach([10, 100, 200, 500, 1000, 2000] as $item):?>
        <?php if($item > $player->room->min):?>
        <a href="<?=$this->url('./do', ['action' => 'fill', 'money' => $item], false)?>"><?=$item?></a>
        <?php endif;?>
        <?php endforeach;?>
        <input type="text" placeholder="自定义">
    </div>
</p>
<p>
    <span>比牌</span>
    <div class="input-box">
        <?php foreach($player->room as $item):?>
        <?php if(!$item->eq($player)):?>
        <a href="<?=$this->url('./do', ['action' => 'compare', 'money' => $item->getName()], false)?>"><?=$item->getName()?></a>
        <?php endif;?>
        <?php endforeach;?>
    </div>
</p>
<?php elseif ($player->status === Player::STATUS_FAILURE):?>
<p>请再接再厉！</p>
<a href="<?=$this->url('./')?>">重新开始</a>
<?php elseif ($player->status === Player::STATUS_WINNER):?>
<p>恭喜您赢得了 <?=$player->room->pool?> ！</p>
<a href="<?=$this->url('./')?>">重新开始</a>
<?php else:?>

<?php endif;?>

<?php if($player && $player->room):?>
<div class="record-box">
    <?php foreach($player->room->records as $item):?>
    <p>[<?=$item['player']?>]<?=$item['remark']?></p>
    <?php endforeach;?>
</div>
<?php endif;?>