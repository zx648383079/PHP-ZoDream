<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Forum\Domain\Parsers\Parser;
/** @var $this View */
?>
<div class="post-list">
    <?php foreach($post_list as $item):?>
    <div id="post-<?=$item->id?>" class="post-item" data-id="<?=$item->id?>">
        <div class="post-user">
            <div class="name"><?=$this->text($item->user->name)?></div>
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

                <form class="vote-box" action="" method="POST">
                    这是说明
                    <div class="vote-item">
                        <input type="checkbox" name="" id="">
                        <label for="">选项一</label>
                    </div>
                    <button>提交</button>
                </form>
            </div>
            <div class="action">
                
            </div>
            <div class="footer">
                <a href="javascript:;" data-action="reply">回复</a>
                <?php if($thread->canDigest()):?>
                <a href="<?=$this->url('./thread/digest', ['id' => $thread->id])?>" data-action="toggle"><?= !$thread->is_digest ? '设为' : '取消'?>精华</a>
                <?php endif;?>
                <?php if($thread->canHighlight()):?>
                <a href="<?=$this->url('./thread/highlight', ['id' => $thread->id])?>" data-action="toggle"><?= !$thread->is_highlight ? '设为' : '取消'?>高亮</a>
                <?php endif;?>
                <?php if($thread->canClose()):?>
                <a href="<?=$this->url('./thread/close', ['id' => $thread->id])?>" data-action="toggle"><?= !$thread->is_closed ? '设为' : '取消'?>关闭</a>
                <?php endif;?>
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
                <?php if($thread->canRemovePost($item)):?>
                <a href="<?=$this->url('./thread/remove_post', ['id' => $item->id])?>" data-action="toggle">删除</a>
                <?php endif;?>
            </div>
        </div>
        <?php endif;?>
    </div>
    <?php endforeach;?>
</div>
<div class="paging-box">
    <?=$post_list->getLink()?>
</div>