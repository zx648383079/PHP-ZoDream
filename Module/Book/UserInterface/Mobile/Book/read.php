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
    <div class="dialog dialog-box" id="setting-box" data-type="dialog">
        <div class="dialog-header">
            <i class="fa fa-close dialog-close"></i>
        </div>
        <div class="dialog-body">
            <ul>
                <li>
                    <span>阅读主题</span>
                    <div class="theme-box">
                        <span class="theme-0 active"></span>
                        <span class="theme-1"></span>
                        <span class="theme-2"></span>
                        <span class="theme-3"></span>
                        <span class="theme-4"></span>
                        <span class="theme-5"></span>
                    </div>
                </li>
                <li>
                    <span>正文字体</span>
                    <div class="font-box">
                        <span>雅黑</span>
                        <span>宋体</span>
                        <span>楷书</span>
                        <span>启体</span>
                    </div>
                </li>
                <li>
                    <span>字体大小</span>
                    <div class="size-box">
                        <i class="fa fa-minus"></i>
                        <span class="lang">18</span>
                        <i class="fa fa-plus"></i>
                    </div>
                </li>
                <li>
                    <span>页面宽度</span>
                    <div class="width-box">
                        <i class="fa fa-minus"></i>
                        <span class="lang">800</span>
                        <i class="fa fa-plus"></i>
                    </div>
                </li>
            </ul>
        </div>
        <div class="dialog-footer">
            <button type="button" class="dialog-yes">保存</button>
            <button type="button" class="dialog-close">取消</button>
        </div>
    </div>
<?php $this->extend('../layouts/footer2');?>