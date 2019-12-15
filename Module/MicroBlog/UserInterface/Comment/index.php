<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="comment-publish">
    <div class="avatar">
        <img src="<?=!auth()->guest() ? auth()->user()->avatar : '/assets/images/avatar/1.png'?>" alt="">
    </div>
    <div class="reply-input">
        <div class="input">
            <textarea name="content"></textarea>
        </div>
        <div class="input-actions">
            <div class="tools">
                <i class="fa fa-smile"></i>
                <i class="fa fa-image"></i>
            </div>
            <input type="checkbox" name="is_forward">同时转发到我的微博
            <a class="btn" href="<?=$this->url('./comment/save', ['micro_id' => $id])?>">评论</a>
        </div>
    </div>
</div>
<?php $this->extend('./more');?>