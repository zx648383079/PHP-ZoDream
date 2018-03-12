<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('../layouts/header');
?>
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
		<h1 class="page-title">
			<?=$author->name?>作品集</h1>
		<div class="mod block book-all-list">
			<div class="bd">
				<ul>
                    <?php foreach ($book_list as $item):?>
						<li class="column-2 ">
							<div class="right">
								<a class="name" href="<?=$item->wap_url?>">
                                    <?=$item->name?>
								</a>
								<span style="float:right;font-size:0.8125em;color: #999;">
									<?=$item->over_at?>
								</span>
								<p class="update">
									最新章节：
                                    <?=$item->last_chapter->name?>
								</p>
								<p class="info">
									作者：
                                    <?=$author->name?>
									<span class="words">字数：
                                        <?=$item->size?>
									</span>
								</p>
							</div>
						</li>
                    <?php endforeach;?>
				</ul>
			</div>
		</div>
	</div>
<?php $this->extend('../layouts/footer');?>