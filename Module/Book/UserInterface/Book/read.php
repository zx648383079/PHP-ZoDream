<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $chapter->title;
$this->body_class = 'theme-0 width-800';
$this->extend('layouts/header2');
?>
<div class="clear"></div>
<div class="Layout local">当前位置：<a href="<?=$this->url('./')?>" title=""><?=$site_name?></a> >
    <?php if($cat):?>
    <a href="<?=$cat->url?>"><?=$cat->real_name?>小说</a> >
    <?php endif;?>
    <a href="<?=$book->url?>"><?=$book->name?> 最新章节</a></div>
<div class="clear"></div>
<!--body开始-->
<div class="chapte-box">
  <div class="read-main">
    <div class="read-title">
        <h2><?=$chapter->title?></h2>
        <div class="info"><a href="<?=$book->url?>" title="<?=$book->name?>"><?=$book->name?></a>
            &#160;|
            <?php if($book->author):?>
            &#160;作者:<?=$book->author->name?>&#160;|
            <?php endif;?>
            
            &#160;更新时间：<?=$chapter->created_at?>
        </div>
    </div>
    <div class="read-content">
        <?=$chapter->body->html?>
    </div>
  </div>

    <div class="chapter-control">
      <div class="u"> 
        <span class="pre">
        <?php if($chapter->previous):?>
        <b>（快捷键：←）&#160;</b>
            <a href="<?=$chapter->previous->url?>" id="keyleft" title="上一章">上一章</a>
        <?php endif;?>
        </span>
        <span class="bookhome">
              <a id="keyenter" href="<?=$book->url?>" title="回目录">回目录</a></span> 
        <span class="next">
        <?php if($chapter->next):?>
        
              <a href="<?=$chapter->next->url?>" id="keyright" title="下一章">下一章</a><b>&#160;（快捷键：→）</b>
        <?php endif;?>
        </span> 
    </div>
</div>
<!--body结束-->
<div class="clear"></div>
</div>

<?php $this->extend('layouts/footer2');?>