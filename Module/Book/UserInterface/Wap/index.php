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
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=1"/>
	<meta name="format-detection" content="telephone=no" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<?= $this->header() ?>
</head>

<body class="index">
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
		<div class="top-alert">
		</div>
		<div class="slide-ad">
			<!--首页广告1-->
		</div>
		<div class="mod block column-list">
			<div class="hd" style="padding: 7px;"></div>
			<div class="bd">
				<ul class="list">
                    <?php foreach ($recommend_book as $item):?>
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
		<div class="mod block recommend">
			<div class="hd">
				<h4>热门推荐</h4>
			</div>
			<div class="bd">
				<ul class="list">
                    <?php foreach ($hot_book as $key => $item):?>
                    <?php if ($key < 1):?>
						<li class="column-2">
							<div class="left">
								<a href="<?=$item->wap_url?>">
									<img src="<?=$item->cover?>" alt="<?=$item->name?>"  width="85"
										height="110">
								</a>
							</div>
							<div class="right">
								<h4>
									<a href="<?=$item->wap_url?>"><?=$item->name?></a>
									<span style="float: right;">[<?=$item->author?>]</span>
								</h4>
								<div class="summary">
                                    <?=$item->description?>
								</div>
							</div>
						</li>
                        <?php else:?>
						<li><a href="<?=$item->wap_url?>"><span>[<?=$item->category->name?>]</span><?=$item->name?></a><span style="float: right;">[<?=$item->author?>]</span></li>
                        <?php endif;?>
                    <?php endforeach;?>
                    <li><span style="float: right;">[<a href="<?=$this->url('book/wap/list', ['sort' => 'click_count'])?>">更多热门小说···</a>]</span></li>
				</ul>
			</div>
		</div>
		<div class="slide-ad">
			<!--首页广告1-->
		</div>
		<div class="mod block">
			<div class="hd" style="height: 16px;">
				<h4 style="float: left;">新书推荐</h4>
				<span style="float: right;font-size: 12px;">[
					<a href='<?=$this->url('book/wap/list', ['sort' => 'created_at'])?>'>更多···</a>]</span>
			</div>
			<div class="bd">
				<div class="column-list">
					<ul class="list">
                        <?php foreach ($new_book as $key => $item):?>
                        <?php if ($key < 4):?>
						<li>
							<a href="<?=$item->wap_url?>">
								<img src="<?=$item->cover?>" alt="<?=$item->name?>">
							</a>
							<div class="name">
								<a href="<?=$item->wap_url?>"><?=$item->name?></a>
							</div>
						</li>
                            <?php endif;?>
                        <?php endforeach;?>
					</ul>
				</div>
				<div class="recommend">
					<ul class="list">
                        <?php foreach ($hot_book as $key => $item):?>
                        <?php if ($key >= 4):?>
						<li><a href="<?=$item->wap_url?>"><span>[<?=$item->category->name?>]</span><?=$item->name?></a><span style="float: right;">[<?=$item->author?>]</span></li>
                            <?php endif;?>
                        <?php endforeach;?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mod block">
			<div class="hd" style="height: 16px;">
				<h4 style="float: left;">完本推荐</h4>
				<span style="float: right;font-size: 12px;">[
					<a href='<?=$this->url('book/wap/list', ['status' => 2])?>'>更多···</a>]</span>
			</div>
			<div class="bd">
				<div class="column-list">
					<ul class="list">
                        <?php foreach ($over_book as $key => $item):?>
                            <?php if ($key < 4):?>
                                <li>
                                    <a href="<?=$item->wap_url?>">
                                        <img src="<?=$item->cover?>" alt="<?=$item->name?>">
                                    </a>
                                    <div class="name">
                                        <a href="<?=$item->wap_url?>"><?=$item->name?></a>
                                    </div>
                                </li>
                            <?php endif;?>
                        <?php endforeach;?>
					</ul>
				</div>
				<div class="recommend">
					<ul class="list">
                        <?php foreach ($over_book as $key => $item):?>
                            <?php if ($key >= 4):?>
                                <li><a href="<?=$item->wap_url?>"><span>[<?=$item->category->name?>]</span><?=$item->name?></a><span style="float: right;">[<?=$item->author?>]</span></li>
                            <?php endif;?>
                        <?php endforeach;?>
					</ul>
				</div>
			</div>
		</div>
		<div class="mod block rank-switch">
			<div class="hd tab-switch">
				<span index="0" class="item active">点击榜</span>
				<span index="1" class="item ">推荐榜</span>
				<span index="2" class="item ">字数榜</span>
			</div>
			<div class="bd">
				<ul class="list">
                    <?php foreach ($click_bang as $key => $item):?>
				    <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                        <dt style="float: right;">[<?=$item->author?>]</dt></li>
                    <?php endforeach;?>
                </ul>
				<ul class="list" style="display:none">
                    <?php foreach ($recommend_bang as $key => $item):?>
                        <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                            <dt style="float: right;">[<?=$item->author?>]</dt></li>
                    <?php endforeach;?>
				</ul>
				<ul class="list" style="display:none">
                    <?php foreach ($size_bang as $key => $item):?>
                        <li <?= $key < 3 ? 'class="t"' : '' ?>><span class="count" style="float: left;"><?=$key + 1?></span><a href="<?=$item->wap_url?>" style="float: left;"><?=$item->name?></a>
                            <dt style="float: right;">[<?=$item->author?>]</dt></li>
                    <?php endforeach;?>
				</ul>
			</div>
		</div>
		<div class="mod block book-all-list">
			<div class="hd" style="height: 16px;padding: 10px;">
				<h4>最近更新小说列表</h4>
			</div>
			<div class="bd">
				<ul>
                    <?php foreach ($update_book as $item):?>
					<li class="column-2 ">
						<a class="name" href="<?=$item->wap_url?>"><?=$item->name?></a>
						<span style="float:right;font-size:0.8125em;color: #999;"><?=$item->status?></span>
                        <?php if ($item->last_chapter):?>
                        <p class="update">最新章节：
                            <a href="<?=$item->last_chapter->wap_url?>"><?=$item->last_chapter->title?></a>
                        </p>
                        <?php endif;?>
						<p class="info">作者：
							<a href="<?=$item->wap_url?>" class="author"><?=$item->author?></a>
							<span class="words">字数：<?=$item->size?></span>
						</p>
					</li>
                    <?php endforeach;?>
                    <li class="column-2 "><span style="float: right;font-size:12px;">[<a href="<?=$this->url('book/wap/list')?>">更多小说更新列表···</a>]</span></li>
				</ul>
			</div>
		</div>
		<div class="slide-ad">
			<!--首页广告-->
		</div>
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