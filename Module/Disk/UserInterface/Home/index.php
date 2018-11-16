<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Disk';
?>

<div class="list-group">
    <?php foreach ($model_list as $item):?>
    <div class="list-group-item">
        <img src="<?=$item->user->avatar?>">
        <h4 class="list-group-item-heading"><?=$item->user->name?> 分享了
        <a href="<?=$this->url('/share', ['id' => $item['id']])?>"><?=$item['title']?></a>
        </h4>
        <p class="list-group-item-text">
            分享时间:<?=$item['created_at']?>
            <?php if (auth()->id() == $item['user_id']):?>
            <a href="#">取消分享</a>
            <?php endif;?>
        </p>
    </div>
    <?php endforeach;?>
</div>
