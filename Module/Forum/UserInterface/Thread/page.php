<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Forum\Domain\Parsers\Parser;
/** @var $this View */
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js');
?>
<div class="post-list">
    <?php foreach($post_list as $item):?>
    <div class="post-item" data-id="<?=$item->id?>">
        <div class="post-user">
            <div class="name"><?=$item->user->name?></div>
            <div class="avatar">
                <img src="<?=$item->user->avatar?>" alt="">
            </div>
        </div>
        <?php if($item->grade < 1):?>
        <div class="post-content">
            <div class="header">
                <i class="fa fa-user"></i>
                发表于 <?=$item->created_at?>
                <div class="action">
                  
                </div>
            </div>
            <div class="last-time">最后编辑于 <?=$item->updated_at?></div>
            <div class="content">
                <?=Parser::converter($item)?>
            </div>
            <div class="action">
                
            </div>
            <div class="footer">
                <a href="javascript:;" data-action="reply">回复</a>
            </div>
        </div>
        <?php else:?>
        <div class="post-content">
            <div class="header">
                <i class="fa fa-user"></i>
                <?php if($thread->user_id == $item->user_id):?>
                <span>楼主</span>
                <?php endif;?>
                发表于 <?=$item->created_at?>
                <div class="action">
                    <?=$item->grade?>楼
                </div>
            </div>
            <div class="content">
                <?=Parser::converter($item)?>
            </div>
            <div class="footer">
                <a href="javascript:;" data-action="reply">回复</a>
            </div>
        </div>
        <?php endif;?>
    </div>
    <?php endforeach;?>
</div>
<div class="paging-box">
    <?=$post_list->getLink()?>
</div>