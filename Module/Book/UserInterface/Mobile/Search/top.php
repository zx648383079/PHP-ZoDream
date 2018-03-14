<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->body_class = 'index';
$this->extend('../layouts/header');
?>
<div class="channel">
        <?php foreach ($cat_list as $key => $item):?>
            <a class="<?= $key % 3 == 1 ? 'xuanyi' : ''  ?> active" href="<?=$this->url('./mobile/category', ['id' => $item->id])?>"><?=$item->name?></a>
        <?php endforeach;?>
    </div>
    <form name="From" action="<?=$this->url('./mobile/search')?>" class="search-form">
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
				<a href="<?=$this->url('./mobile/search/top')?>">热门小说排行榜</a>
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
                    <span index="0" class="item active" style="width:50%"><?=$cat->real_name?>小说排行榜</span>
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

<?php $this->extend('../layouts/footer');?>