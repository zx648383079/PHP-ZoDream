<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="micro-item" data-id="<?=$code->id?>">
    <div class="body">
        <div class="avatar">
            <img src="<?=$code->user->avatar?>" alt="">
        </div>
        <div>
            <div class="name"><?=$code->user->name?></div>
            <p><span class="time"><?=$code->created_at?></span>
                <span class="lang"><?=$this->text($code->language)?></span>
                <?php if($code->user_id == auth()->id()):?>
                <a class="remove" href="javascript:;" data-action="remove">[删除]</a>
                <?php endif;?>
            </p>
            <div class="content">
                <pre>
                    <code language="language-<?=strtolower($this->text($code->language))?>"><?=$this->text($code->content)?></code>
                </pre>
                
            </div>
            <?php if($code->tags):?>
            <div class="tags">
                <ul>
                    <?php foreach($code->tags as $tag):?>
                    <li>
                        <a href="<?=$this->url('./', ['keywords' => $tag->content])?>"><?=$tag->content?></a>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <?php endif;?>
        </div>
    </div>
    
    <div class="ations">
        <a class="<?=$code->is_collected ? 'active' : ''?>"  data-action="collect" href="javascript:;">收藏</a>
        <a data-action="comment" href="javascript:;">评论<?=$code->comment_count > 0 ? $code->comment_count : null?></a>
        <a class="<?=$code->is_recommended ? 'active' : ''?>" data-action="recommend" href="javascript:;">赞<?=$code->recommend_count > 0 ? $code->recommend_count : null?></a>
    </div>
    <div class="comment-box">
        
    </div>
</div>