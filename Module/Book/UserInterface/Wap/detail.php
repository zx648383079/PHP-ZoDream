<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@wap.min.css')->registerJsFile('@jquery.min.js');
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?=$this->title?>
    </title>
    <meta name="keywords" content="<?=$this->keywords?>">
    <meta name="description" content="<?=$this->description?>">
	<meta name="format-detection" content="telephone=no" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <?= $this->header() ?>
</head>

<body class="chapter" ondragstart="return false" oncopy="return false;" oncut="return false;" oncontextmenu="return false">
    <header class="page-title">
        <a class="back" href="<?=$book->wap_url?>">
            &lt;
        </a>
        <span class="title"><?=$chapter->title; ?></span>
        <a href="<?=$this->url('./wap')?>" class="home">
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
            <a href="<?=$chapter->prev->url?>" class="prev"><span>&lt;</span>上一章</a>
            <a href="<?=$book->wap_url?>" class="catalog">目录</a>
            <a href="<?=$chapter->next->url?>" class="next">下一章<span>&gt;</span></a>
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
            <a href="<?=$chapter->prev->url?>" class="prev"><span>&lt;</span>上一章</a>
            <a href="<?=$book->wap_url?>" class="catalog">目录</a>
            <a href="<?=$chapter->next->url?>" class="next">下一章<span>&gt;</span></a>
        </div>
    </div>
    <?php $this->extend('./footer')?>
    <?=$this->footer()?>
    <script>
        $(document).ready(function () {
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
        });
    </script>
</body>

</html>