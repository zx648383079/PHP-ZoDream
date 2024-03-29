<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach ($comment_list as $item) :?>
<div class="comment-item"  data-id="<?=$item->id?>">
    <div class="item-header">
        <?php if($item->url):?>
        <a href="<?=$this->url($item->url)?>" class="user" target="_blank" rel="noopener noreferrer"><?=$this->text($item['name'])?></a>
        <?php else:?>
        <span class="user"><?=$this->text($item['name'])?></span>
        <?php endif;?>
        <span class="time"><?=$item['created_at']?></span>
        <span class="floor"><?=$item->position?><?=__('floor')?></span>
    </div>
    <div class="item-body">
        <p><?=$this->text($item['content'])?></p>
        <span>&nbsp;</span>
        <span class="comment" data-type="reply"><i class="fa fa-comment"></i></span>
        <span class="report"><?=__('Report')?></span>
        <div class="actions">
            <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$item['agree_count']?></b></span>
            <span class="disagree"><i class="fas fa-thumbs-down"></i><b><?=$item['disagree_count']?></b></span>
        </div>
    </div>

    <div class="comments <?=$item->replies ? '' : 'reply-hide' ?>">
        <?php if ($item->replies):?>
            <?php foreach ($item->replies as $reply) :?>
                <div class="comment-item" data-id="<?=$item->id?>">
                    <div class="item-header">
                        <span class="user"><?=$this->text($reply['name'])?></span>
                        <span class="time"><?=$reply['created_at']?></span>
                        <span class="floor"><?=$reply->position?>#</span>
                    </div>
                    <div class="item-body">
                        <p><?=$this->text($reply['content'])?></p>
                        <span>&nbsp;</span>
                        <span class="comment" data-type="reply"><i class="fa fa-comment"></i></span>
                        <span class="report"><?=__('Report')?></span>
                        <div class="actions">
                            <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$reply['agree_count']?></b></span>
                            <span class="disagree"><i class="fas fa-thumbs-down"></i><b><?=$reply['disagree_count']?></b></span>
                        </div>
                    </div>
                   
                </div>
            <?php endforeach;?>
        <?php endif;?>
    </div>
</div>
<?php endforeach;?>
<?php if($comment_list->hasMore()):?>
   <div class="load-more" data-page="<?=$comment_list->getIndex() + 1?>"><?=__('Load More')?></div>
<?php endif;?>