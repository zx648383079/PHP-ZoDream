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
		<?php echo "{$typename}"; ?>最新章节_
		<?php echo "{$zuozhe}"; ?>新书作品_
		<?php echo $cfg_webname; ?>
	</title>
    <meta name="keywords" content="<?=$this->keywords?>">
    <meta name="description" content="<?=$this->description?>">
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <?= $this->header() ?>
</head>

<body class="cover">
    <?php $this->extend('./head') ?>
    <div class="channel">
        <?php foreach ($cat_list as $key => $item):?>
            <a class="<?= $key % 3 == 1 ? 'xuanyi' : ''  ?> active" href="<?=$this->url('book/wap/category', ['id' => $item->id])?>"><?=$item->name?></a>
        <?php endforeach;?>
    </div>
    <form name="From" action="<?=$this->url('book/wap/search')?>" class="search-form">
        <table>
            <tr>
                <td>
                    <input type="text" name="keywords" class="text-border vm" value="" placeholder="搜索"/>
                </td>
                <td width="8"></td>
                <td width="70">
                    <input type="submit" class="btn btn-auto btn-blue vm" value="搜索" />
                </td>
            </tr>
        </table>
    </form>
	<div class="container">
		<div class="mod mod-back breadcrumb">
			<div class="bd">
				<a href="/" class="home"></a>
				<span class="divide"></span>
				<a href="<?=$cat->wap_url?>">
					<?= $cat->real_name ?>小说</a>
				<span class="divide"></span>
				<a href="<?=$book->wap_url?>">
					<?=$book->name?>章节目录</a>
			</div>
		</div>
		<div class="mod detail">
			<div class="bd column-2">
				<div class="left">
					<img src="<?=$book->cover?>" width="90" alt="<?=$book->name?>" />
				</div>
				<div class="right">
					<h1>
                        <?=$book->name?>
					</h1>
					<p class="info">
						作者：
                        <?=$book->author?>
						<br /> 类型：
                        <?= $cat->real_name ?>
						<br /> 字数：
                        <?=$book->size?>
						<br /> 人气：
                        <?=$book->click_count?>
					</p>
					<p></p>
					<span class="status is-serialize">
						<?= $book->over_at == 0 ? '连载中••' : '已完结'?>
					</span>
				</div>
			</div>
			<div class="ft">
				<table>
					<tr>
						<td width="50%">
							<a class="read start" href="/wap.php?action=article&id=0&tid=<?php echo $id.$wxuid; ?>">从头开始阅读</a>
						</td>
						<td width="5">&nbsp;</td>
						<td width="50%">
							<a class="collect" href="/download/download.php?filetype=txt&filename=<?php echo $id.$wxuid; ?>">
                                <?=$book->name?>txt下载</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php if (isset($new_chapter)): ?>
            <div class="mod book-intro">
                <div class="bd">
                    <?=$book->description?>
                </div>
            </div>
            <div class="slide-ad"><!--广告--></div>
            <div class="mod block update chapter-list">
                <div class="hd">
                    <h4><?=$book->name?>最新章节</h4>
                </div>
                <div class="bd">
                    <ul class="list">
                        <?php foreach ($new_chapter as $item) : ?>
                            <li><a href="<?=$item->wap_url?>"><?=$item->title?></a></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <?php endif;?>
		<div class="slide-ad">
			<!--广告-->
		</div>
		<div class="mod block update chapter-list">
			<div class="hd">
				<h4>
                    <?=$book->name?>章节列表</h4>
			</div>
			<div class="bd">
				<ul class="list">
                    <?php foreach ($chapter_list as $item) : ?>
                        <li><a href="<?=$item->wap_url?>"><?=$item->title?></a></li>
                    <?php endforeach;?>
				</ul>
			</div>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>
		<div class="mod page">
			<?=$chapter_list->getLink()?>
		</div>
		<div class="mod block column-list">
			<div class="hd" boxid="heiyanMobileChapterJingpin">
				<h4>类似
                    <?=$book->name?>的小说推荐</h4>
			</div>
			<div class="bd">
                <ul class="list">
                    <?php foreach ($like_book as $item):?>
                        <li>
                            <a href="<?=$item->wap_url?>">
                                <img src="<?=$item->cover?>" alt="<?=$item->name?>">
                            </a>
                            <div class="name">
                                <a href="<?=$item->wap_url?>"><?=$item->name?></a>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
			</div>
		</div>
		<div class="mod mod-back">
			<div class="bd">
				<a href="/" class="home"></a>
				<span class="divide"></span>
				<a href="<?= $cat->wap_url ?>">
                    <?= $cat->real_name ?>小说</a>
				<span class="divide"></span>
				<a href="<?=$book->wap_url?>">
                    <?=$book->name?>最新章节</a>
			</div>
		</div>
	</div>
    <?php $this->extend('./footer')?>
    <?=$this->footer()?>
</body>

</html>