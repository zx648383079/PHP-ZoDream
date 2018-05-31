<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $chapter->title;
$this->registerJsFile('@book_mobile.min.js')
    ->extend('../layouts/header2');
?>
<header class="page-title">
        <a class="back" href="<?=$book->wap_url?>">
            &lt;
        </a>
        <span class="title"><?=$chapter->title; ?></span>
        <a href="<?=$this->url('./mobile')?>" class="home">
            首页
        </a>
    </header>
    <div class="toolbar">
        <a class="button lightoff" href="javascript:;"><i></i></a>
        <a class="button huyanon"  href="javascript:;">护眼</a>&nbsp;&nbsp;&nbsp;&nbsp;
        字体：<a class="fontsize" data-role="inc" href="javascript:;">+A</a> <a class="fontsize" data-role="des" href="javascript:;">-A</a>
    </div>
    <div class="page-control">
        <div class="bd">
            <?php if($chapter->previous):?>
            <a href="<?=$chapter->previous->wap_url?>" class="prev"><span>&lt;</span>上一章</a>
            <?php endif;?>
            <a href="<?=$book->wap_url?>" class="catalog">目录</a>
            <?php if($chapter->next):?>
            <a href="<?=$chapter->next->wap_url?>" class="next">下一章<span>&gt;</span></a>
            <?php endif;?>
            
        </div>
    </div>
	<div class="container">
		<div class="mod mod-page" id="ChapterView" data-already-grab="" data-hongbao="-1">
			<div class="bd">
				<div class="page-content font-l">
					<p>
						<?=$chapter->body->html?>
					</p>
				</div>
			</div>
		</div>
		<div class="tuijian">
			<span>推荐阅读：</span>
            <?php foreach ($like_book as $key => $item):?>
                <?= $key > 0 ? '、' : ''?>
                <a href="<?=$item->wap_url?>"><?=$item->name?></a>
            <?php endforeach;?>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>

	</div>
    <div class="page-control">
        <div class="bd">
            <?php if($chapter->previous):?>
            <a href="<?=$chapter->previous->wap_url?>" class="prev"><span>&lt;</span>上一章</a>
            <?php endif;?>
            <a href="<?=$book->wap_url?>" class="catalog">目录</a>
            <?php if($chapter->next):?>
            <a href="<?=$chapter->next->wap_url?>" class="next">下一章<span>&gt;</span></a>
            <?php endif;?>
        </div>
    </div>
<?php $this->extend('../layouts/footer2');?>