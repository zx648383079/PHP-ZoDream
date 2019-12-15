<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="micro-item" data-id="<?=$blog->id?>">
    <div class="body">
        <div class="avatar">
            <img src="<?=$blog->user->avatar?>" alt="">
        </div>
        <div>
            <div class="name"><?=$blog->user->name?></div>
            <p><span class="time"><?=$blog->created_at?></span> <span class="from">来自<?=$blog->source ?: 'web'?></span></p>
            <div class="content">
                <?=$blog->content?>
            </div>
        </div>
        <?php if($blog->forward_id > 0 && $blog->forward):?>
        <div class="forward-source">
            <div class="name">@<?=$blog->forward->user->name?></div>
            <div class="content">
                <?=$blog->forward->content?>
            </div>
            <p><span class="time"><?=$blog->forward->created_at?></span> <span class="from">来自<?=$blog->forward->source ?: 'web'?></span></p>
        </div>
        <?php endif;?>
    </div>
    
    <div class="ations">
        <a href="javascript:;">收藏</a>
        <a data-action="dialog" href="<?=$this->url('./forward_mini', ['id' => $blog->id])?>">转发<?=$blog->forward_count > 0 ? $blog->forward_count : null?></a>
        <a data-action="comment" href="javascript:;">评论<?=$blog->comment_count > 0 ? $blog->comment_count : null?></a>
        <a class="<?=$blog->is_recommended ? 'active' : ''?>" data-action="recommend" href="javascript:;">赞<?=$blog->recommend_count > 0 ? $blog->recommend_count : null?></a>
    </div>
    <div class="comment-box">
        
    </div>
</div>