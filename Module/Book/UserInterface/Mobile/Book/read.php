<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $chapter->title;
$js = <<<JS
$(".toolbar .lightoff").click(function () { 
    $(".chapter").removeClass('best-eye').toggleClass('night');
});
$(".toolbar .huyanon").click(function () { 
    $(".chapter").removeClass('night').toggleClass('best-eye');
});
var sizes = [
    'font-s',
    'font-l',
    'font-xl',
    'font-xxl',
    'font-xxxl'
];
var pagebox = $(".page-content")
$('.toolbar .fontsize').click(function (e) {
    if ($(this).attr('data-role') == 'inc') {
        var selected = false;
        for (var i = 0; i < sizes.length; i++) {
            const size = sizes[i];
            if (selected) {
                pagebox.removeClass(sizes[i - 1]).addClass(size);
                return;
            }
            if (pagebox.hasClass(size)) {
                selected = true;
            }
        }
        return;
    }
    var selected = false;
    for (var i = sizes.length - 1; i >= 0; i--) {
        const size = sizes[i];
        if (selected) {
            pagebox.removeClass(sizes[i + 1]).addClass(size);
            return;
        }
        if (pagebox.hasClass(size)) {
            selected = true;
        }
    }
    return;
});
$(document).click(function (e) { 
    console.log(e.pageX, e.pageY, $(window).scollTop());

});
JS;
$this->registerJs($js, View::JQUERY_READY);
$this->extend('../layouts/header2');
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
            <a href="<?=$chapter->previous->wap_url?>" class="prev"><span>&lt;</span>上一章</a>
            <a href="<?=$book->wap_url?>" class="catalog">目录</a>
            <a href="<?=$chapter->next->wap_url?>" class="next">下一章<span>&gt;</span></a>
        </div>
    </div>
	<div class="container">
		<div class="mod mod-page" id="ChapterView" data-already-grab="" data-hongbao="-1">
			<div class="bd">
				<div class="page-content font-l">
					<p>
						<?=$chapter->body->content; ?>
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
            <a href="<?=$chapter->prev->wap_url?>" class="prev"><span>&lt;</span>上一章</a>
            <a href="<?=$book->wap_url?>" class="catalog">目录</a>
            <a href="<?=$chapter->next->wap_url?>" class="next">下一章<span>&gt;</span></a>
        </div>
    </div>
<?php $this->extend('../layouts/footer2');?>