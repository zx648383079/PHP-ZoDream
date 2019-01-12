<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $thread->title;
$this->registerCssFile([
        '@dialog.css',
        '@forum.css',
    ])
    ->registerJsFile([
        '@main.min.js',
        '@jquery.dialog.min.js',
        '@forum.min.js'
    ]);
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
                <?=$thread->title?>
            </div>
        </div>
        <?php $this->extend('./page');?>

        <?php if(!auth()->guest()):?>
        <div class="post-item post-new">
            <div class="post-user">
                <div class="name"><?=auth()->user()->name?></div>
                <div class="avatar">
                    <img src="<?=$this->asset('images/zd.jpg')?>" alt="">
                </div>
            </div>
            <div class="post-content">
                <form data-type="ajax" action="<?=$this->url('./thread/reply')?>" method="post">
                    <textarea name="content"></textarea>
                    <div class="footer">
                        <button class="btn">发表回复</button>
                        <input type="hidden" name="thread_id" value="<?=$thread->id?>">
                    </div>
                </form>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>
