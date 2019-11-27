<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="comment-box">
    <div class="comment-header">
        <div class="text-center">
        好评率
        </div>
        <div>
        大家都在说：
        </div>
        <div class="text-center">
            <div class="rate"><?=$comment_count['favorable_rate']?>%</div>
            <div class="score">
                <?php for($i = 0; $i < 10; $i += 2):?>
                <i class="fa fa-star <?=$i < $comment_count['avg'] ? 'light' : ''?>"></i>
                <?php endfor;?>
            </div>
        </div>
        <div class="tag-box">
            <span class="active">全部（<?=$comment_count['total']?>）</span>
            <?php foreach($comment_count['tags'] as $item):?>
            <span><?=$item['label']?>（<?=$item['count']?>）</span>
            <?php endforeach;?>
        </div>
    </div>
    <div class="comment-filter">
        <span>排序</span>
        <a href="" class="active"> 默认</a>
        <a href="">评价时间</a>
    </div>
    <div class="comment-page-box">
        <?php $this->extend('./commentPage');?>
    </div>
</div>