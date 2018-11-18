<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Disk';
?>

<div class="list-group">
    <?php foreach ($model_list as $item):?>
    <div class="list-group-item">
        <div class="avatar">
            <img src="<?=$item->user->avatar?>">
        </div>
        <div>
            <h4 class="list-group-item-heading"><?=$item->user->name?> 分享了
            <a href="<?=$this->url('./share', ['id' => $item['id']])?>"><?=$item['name']?></a>
            </h4>
            <p class="list-group-item-text">
                分享时间:<?=$item['created_at']?>
                <?php if (auth()->id() == $item['user_id']):?>
                <a data-type="del" data-tip="确定取消这条分享？" href="<?=$this->url('./share/cancel', ['id' => $item['id']])?>">取消分享</a>
                <?php endif;?>
            </p>
        </div>
    </div>
    <?php endforeach;?>
</div>
