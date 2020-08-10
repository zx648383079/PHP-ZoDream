<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
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
            <?php if($item->is_closed):?>
                <i class="fa fa-lock" title="主题已关闭"></i>
            <?php else:?>
                <i class="fa fa-file"></i>
            <?php endif;?>
            <?php if($item->classify):?>
            <em class="tag-item">[
            <a href="<?=$this->url('./forum', ['id' => $item->forum_id, 'classify' => $item->classify_id])?>"><?=$item->classify->name?></a>
            ]</em>
            <?php endif;?>
            <a class="title" href="<?=$this->url('./thread', ['id' => $item->id])?>"><?=$this->text($item->title)?></a>
            <?php if($item->is_digest):?>
            <i class="fa fa-fire"></i>
            <?php endif;?>
            <?php if($item->is_new):?>
            <a href="<?=$this->url('./thread', ['id' => $item->id])?>#post-<?=$item->last_post->id?>" class="new-tag">New</a>
            <?php endif;?>
        </div>
        <div class="time">
            <em data-action="user" data-id="<?=$item->user_id?>"><?=$this->text($item->user->name)?></em>
            <em><?=$item->updated_at?></em>
        </div>
        <div class="count">
            <em><?=$item->post_count?></em>
            <em><?=$item->view_count?></em>
        </div>
        <div class="reply">
            <?php if($item->last_post):?>
            <em><?=$this->text($item->last_post->user->name)?></em>
            <em><?=$item->last_post->updated_at?></em>
            <?php endif;?>
        </div>
    </div>
<?php endforeach;?>
<div class="paging-box">
    <?=$thread_list->getLink()?>
</div>
