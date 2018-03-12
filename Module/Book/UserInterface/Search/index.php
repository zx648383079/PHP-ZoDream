<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->body_class = 'bodyph';
$this->extend('layouts/header');
?>
<div class="clear"></div>
<!--body开始-->
<div class="Layout local">当前位置：
    <a href="<?=$this->url('./')?>" title=""><?=$site_name?></a>&nbsp;>&nbsp;
    <a href="<?=$this->url('./home/list')?>">搜索</a></div>
<div class="clear"></div>
<div class="Layout m_list list">
    <div class="Con">
            <div class="m_head"> <span class="c">类型</span> <span class="t">书名/章节</span> <span class="w">字数</span> <span class="a">作者</span><span class="z">状态</span></div>
            <ul class="ul_m_list">
                <?php foreach ($book_list as $key => $item):?>
                    <li <?=$key % 2 == 1 ? 'class="odd"' : '' ?>>
                        <div class="c">[<a href="<?=$item->category->url?>" title="<?=$item->category->name?>" target="_blank"><?=$item->category->name?></a>]</div>
                        <div class="title">
                            <div class="t"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a></div>
                            <div class="n">[<a href="<?=$item->download_url?>" title="<?=$item->name?>txt下载" target="_blank">下载</a>] <a href="#" target="_blank"></a> </div>
                        </div>
                        <div class="words">0</div>
                        <div class="author"><a href="<?=$item->author->url?>" title="<?=$item->author->name?>作品" target="_blank"><?=$item->author->name?></a></div>
                        <div class="abover"><span><?=$item->status?></span></div>
                    </li>
                <?php endforeach;?>
            </ul>
            <div class="bot_more">
                <div class="page_info">每页显示<b>&nbsp;50&nbsp;</b>部，共<b><?=$book_list->getTotal()?></b>部</div>
                <div class="page_num">
                    <div><a class="info">第<b><?=$book_list->getIndex()?></b>页/共<?=$book_list->getTotal()?>页</a>
                        <a href="<? echo $pagestart; ?>" title="第一页">第一页</a><a href="<? echo $pagepre; ?>" title="上一页">上一页</a></div>
                    <div><a href="<? echo $pagenext; ?>" title="下一页">下一页</a>
                        <a href="<? echo $pageend; ?>" title="最后一页">最后一页</a></div>
                </div>
            </div>
</div>
<!--body结束-->
<div class="clear"></div>
<?php $this->extend('layouts/footer');?>