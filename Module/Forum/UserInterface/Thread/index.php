<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js');
?>

<div class="container">
    <div class="thread-box">
        <div class="thread-title">
            <div class="count">
                <span>查看：0</span>
                <span>回复：0</span>
            </div>
            <div class="title">
                [
                    <a href="">求助</a>
                ]
                213132132131231
            </div>
        </div>
        <?php $this->extend('./page');?>

        <div class="post-item post-new">
            <div class="post-user">
                <div class="name">1231</div>
                <div class="avatar">
                    <img src="<?=$this->asset('images/zd.jpg')?>" alt="">
                </div>
            </div>
            <div class="post-content">
                <textarea name=""></textarea>
                <div class="footer">
                    <button class="btn">发表回复</button>
                </div>
            </div>
        </div>
    </div>
</div>
