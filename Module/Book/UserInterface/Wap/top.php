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
		<?php echo $seotitle; ?>_
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

<body class="index">
    <?php $this->extend('./head') ?>
    <div class="channel">
        <?php foreach ($cat_list as $key => $item):?>
            <a class="<?= $key % 3 == 1 ? 'xuanyi' : ''  ?> active" href="<?=$this->url('./wap/category', ['id' => $item->id])?>"><?=$item->name?></a>
        <?php endforeach;?>
    </div>
    <form name="From" action="<?=$this->url('./wap/search')?>" class="search-form">
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
				<a href="<?=$this->url('./wap/top')?>">热门小说排行榜</a>
			</div>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>
		<div class="mod block rank-switch p1">
			<div class="hd tab-switch">
				<span index="0" class="item active">小说点击榜</span>
				<span index="1" class="item ">小说推荐榜</span>
				<span index="2" class="item ">小说字数榜</span>
			</div>
			<div class="bd">
				<ul class="list">
                    <?php foreach ($click_bang as $key => $item):?>
                        <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                            <dt style="float: right;">[<?=$item->author->name?>]</dt></li>
                    <?php endforeach;?>
				</ul>
				<ul class="list" style="display:none">
                    <?php foreach ($recommend_bang as $key => $item):?>
                        <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                            <dt style="float: right;">[<?=$item->author->name?>]</dt></li>
                    <?php endforeach;?>
				</ul>
				<ul class="list" style="display:none">
                    <?php foreach ($size_bang as $key => $item):?>
                        <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                            <dt style="float: right;">[<?=$item->author->name?>]</dt></li>
                    <?php endforeach;?>
				</ul>
			</div>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>

        <?php foreach ($cat_list as $index => $cat): ?>
            <div class="mod block rank-switch top<?=$index?>">
                <div class="hd tab-switch">
                    <span index="0" class="item active" style="width:50%"><?=$icat->real_name?>小说排行榜</span>
                    <span index="1" class="item " style="width:50%"><?=$cat->real_name?>小说月榜</span>
                </div>
                <div class="bd">
                    <ul class="list">
                        <?php foreach ($cat->book_list as $key => $item):?>
                            <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                                <dt style="float: right;">[<?=$item->author->name?>]</dt></li>
                        <?php endforeach;?>
                    </ul>
                    <ul class="list" style="display:none">
                        <?php foreach ($cat->month_book as $key => $item):?>
                            <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                                <dt style="float: right;">[<?=$item->author->name?>]</dt></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>

        <?php endforeach;?>


        <?php $this->extend('./footer')?>
        <?=$this->footer()?>
        <script>
            $(document).ready(function () {
                $(".tab-switch .item").click(function () {
                    $(this).addClass('active').siblings().removeClass('active');
                    $('.rank-switch .bd .list').eq($(this).index()).show().siblings().hide();
                });
            });
        </script>
</body>

</html>