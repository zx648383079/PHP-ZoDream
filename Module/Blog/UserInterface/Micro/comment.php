<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="comment-publish">
    <div class="avatar">
        <img src="<?=!auth()->guest() ? auth()->user()->avatar : '/assets/images/avatar/1.png'?>" alt="">
    </div>
    <div>
        <div class="input">
            <textarea name=""></textarea>
        </div>
        <div class="actions">
            <div class="tools">
                <i class="fa fa-smile"></i>
                <i class="fa fa-image"></i>
            </div>
            <button>发布</button>
        </div>
    </div>
</div>
<div class="comment-item">
    <div class="avatar">
        <img src="/assets/images/avatar/2.png" alt="">
    </div>
    <div>
        <div class="text">
            <a href="">123123</a>：
            12312312
        </div>
        <div class="actions">
            <span class="time">2分钟前</span>
            <div class="tools">
                <a href="">回复</a>
                <a href="">赞</a>
            </div>
        </div>
    </div>
</div>