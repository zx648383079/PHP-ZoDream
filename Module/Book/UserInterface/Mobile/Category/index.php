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
            <a class="<?= $key % 3 == 1 ? 'xuanyi' : ''  ?> <?= $item->id == $cat->id ? 'active' : ''  ?>" href="<?=$this->url('./mobile/category', ['id' => $item->id])?>">
                <?=$item->name?></a>
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
				<a href="<?=$cat->wap_url ?>">
                    <?=$cat->real_name ?>小说</a>
			</div>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>
		<div class="mod block recommend">
			<div class="hd">
				<h4>热门
                    <?=$cat->real_name ?>小说推荐</h4>
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
                                        <span style="float: right;">[<?=$item->author->name?>]</span>
                                    </h4>
                                    <div class="summary">
                                        <?=$item->description?>
                                    </div>
                                </div>
                            </li>
                        <?php else:?>
                            <li><a href="<?=$item->wap_url?>"><span>[<?=$item->category->name?>]</span><?=$item->name?></a><span style="float: right;">[<?=$item->author->name?>]</span></li>
                        <?php endif;?>
                    <?php endforeach;?>
                    <li><span style="float: right;font-size:12px;">[<a href="<?=$this->url('./mobile/search/list', ['cat_id' => $cat->id, 'sort' => 'click_count'])?>">更多热门玄幻奇幻小说···</a>]</span></li>
				</ul>
			</div>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>
		<div class="mod block column-list">
			<div class="hd" style="height: 16px;">
				<h4 style="float: left;">最新
                    <?=$cat->real_name ?>小说推荐</h4>
				<span style="float: right;font-size: 12px;">[
					<a href='<?=$this->url('./mobile/search/list', ['cat_id' => $cat->id, 'sort' => 'created_at'])?>'>更多···</a>]</span>
			</div>
			<div class="bd">
				<ul class="list">
                    <?php foreach ($new_book as $item):?>
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
		<div class="mod block column-list">
			<div class="hd" style="height: 16px;">
				<h4 style="float: left;">完本
                    <?=$cat->real_name ?>小说推荐</h4>
				<span style="float: right;font-size: 12px;">[
					<a href='<?=$this->url('./mobile/search/list', ['cat_id' => $cat->id, 'status' => 2])?>'>更多···</a>]</span>
			</div>
			<div class="bd">
				<ul class="list">
                    <?php foreach ($over_book as $item):?>
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
		<div class="slide-ad">
			<!--广告-->
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
		<div class="mod block book-all-list">
			<div class="hd" style="height: 16px;padding: 10px;">
				<h4>
                    <?=$cat->real_name ?>小说最近更新列表</h4>
			</div>
			<div class="bd">
                <ul>
                <?php foreach ($book_list as $item):?>
					<li class="column-2 ">
						<a class="name" href="<?=$item->wap_url?>"><?=$item->name?></a>
						<span style="float:right;font-size:0.8125em;color: #999;"><?=$item->status?></span>
                        <?php if ($item->last_chapter):?>
                        <p class="update">最新章节：
                            <a href="<?=$item->last_chapter->wap_url?>"><?=$item->last_chapter->title?></a>
                        </p>
                        <?php endif;?>
						<p class="info">作者：
							<a href="<?=$item->wap_url?>" class="author"><?=$item->author->name?></a>
							<span class="words">字数：<?=$item->size?></span>
						</p>
					</li>
                    <?php endforeach;?>
                    <li class="column-2 "><span style="float: right;font-size:12px;">[<a href="<?=$this->url('./mobile/search/list')?>">更多小说更新列表···</a>]</span></li>
                </ul>
			</div>
		</div>
<?php $this->extend('../layouts/footer');?>