<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js');
?>
<div class="thread-item-header">
    <div class="name">
        标题
    </div>
    <div class="time">
        作者
    </div>
    <div class="count">
        回复/查看
    </div>
    <div class="reply">
        最后发表
    </div>
</div>
<?php foreach($thread_list as $item):?>
    <div class="thread-item<?=$item->is_highlight ? ' thread-highlight' : ''?>">
        <div class="name">
            <i class="fa fa-file"></i>
            <?php if($item->classify):?>
            [
            <a href="<?=$this->url('./forum', ['id' => $item->forum_id, 'classify' => $item->classify_id])?>"><?=$item->classify->name?></a>
            ]
            <?php endif;?>
            <a class="title" href="<?=$this->url('./thread', ['id' => $item->id])?>"><?=$this->text($item->title)?></a>
            <?php if($item->is_digest):?>
            <i class="fa fa-fire"></i>
            <?php endif;?>
        </div>
        <div class="time">
            <em><?=$item->user->name?></em>
            <em><?=$item->updated_at?></em>
        </div>
        <div class="count">
            <em><?=$item->post_count?></em>
            <em><?=$item->view_count?></em>
        </div>
        <div class="reply">
            <?php if($item->last_post):?>
            <em><?=$item->last_post->user->name?></em>
            <em><?=$item->last_post->updated_at?></em>
            <?php endif;?>
        </div>
    </div>
<?php endforeach;?>
<div class="paging-box">
    <?=$thread_list->getLink()?>
</div>
