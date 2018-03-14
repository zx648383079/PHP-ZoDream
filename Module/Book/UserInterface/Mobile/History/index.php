<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
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
		<h1 class="page-title">您的阅读足迹</h1>
		<div class="mod block book-all-list">
			<div class="bd">
				<ul>
					<?php if (!$chapter_list->isEmpty()): ?>
                    <?php foreach ($chapter_list as $item):?>
                            <li class="column-2 ">
                                <div class="right">
                                    <a class="name" href="<?=$item->book->wap_url?>"><?=$item->book->name?></a>
                                    <span style="float:right;font-size:0.8125em;color: #999;"><?=$item->book->status?></span>
                                    <p class="update">上次看到：<a href="<?=$item->wap_url?>"><?=$item->title?></a></p>
                                    <p class="info">作者：<?=$item->book->author->name?>  <span class="words">字数：<?=$item->book->size?></span></p>
                                </div>
                            </li>
                    <?php endforeach;?>
                    <?php else:?>
                        很抱歉！没有找到您的阅读记录哦:)
                    <?php endif;?>
				</ul>
			</div>
		</div>
	</div>
<?php $this->extend('../layouts/footer');?>