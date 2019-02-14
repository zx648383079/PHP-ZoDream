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
            <p><span class="time"><?=$blog->created_at?></span> <span class="from">来自web</span></p>
            <div class="content">
                <?=$blog->content?>
            </div>
        </div>
    </div>
    <div class="ations">
        <a href="javascript:;">收藏</a>
        <a href="javascript:;">转发</a>
        <a data-action="comment" href="javascript:;">评论</a>
        <a data-action="recommend" href="javascript:;">赞<?=$blog->recommend > 0 ? $blog->recommend : null?></a>
    </div>
    <div class="comment-box">
        
    </div>
</div>