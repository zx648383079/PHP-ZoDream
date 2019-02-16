<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach ($comment_list as $item) :?>
<div class="comment-item"  data-id="<?=$item->id?>">
    <div class="avatar">
        <img src="/assets/images/avatar/2.png" alt="">
    </div>
    <div>
        <div class="text">
            <a href="">123123</a>：
            <?=$this->text($item['content'])?>
        </div>
        <div class="actions">
            <span class="time"><?=$item['created_at']?></span>
            <div class="tools">
                <a href="">回复</a>
                <a href="">赞</a>
            </div>
        </div>
        <div class="comments <?=$item->replies ? '' : 'reply-hide' ?>">
            <?php if ($item->replies):?>
                <?php foreach ($item->replies as $reply) :?>
                    <div class="comment-item" data-id="<?=$item->id?>">
                        <div class="text">
                            <a href="">123123</a>：
                            <?=$this->text($reply['content'])?>
                        </div>
                        <div class="actions">
                            <span class="time"><?=$reply['created_at']?></span>
                            <div class="tools">
                                <a href="">回复</a>
                                <a href="">赞</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
</div>
<?php endforeach;?>
<?php if($comment_list->hasMore()):?>
   <div class="load-more" data-page="<?=$comment_list->getIndex() + 1?>">加载更多</div>
<?php endif;?>