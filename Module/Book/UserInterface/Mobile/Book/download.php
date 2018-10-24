<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Disk;
/** @var $this View */
$this->title = 'ZoDream';
$this->body_class = 'cover';
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
                <a href="<?=$this->url('./')?>" class="home"></a>
                <span class="divide"></span>
                <a href="<?=$cat->wap_url?>">
                    <?= $cat->real_name ?>小说</a>
                <span class="divide"></span>
                <a href="<?=$book->wap_url?>">
                    <?=$book->name?>txt下载</a>
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
                        <?=$book->author->name?>
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
                            <a class="read start" href="<?=$this->url('./book/txt', ['id' => $book->id])?>">
                            TXT下载(<?=Disk::size($book->size)?>)
                            </a>
                        </td>
                        <td width="5">&nbsp;</td>
                        <td width="50%">
                            <a class="collect" href="<?=$this->url('./book/zip', ['id' => $book->id])?>">
                                ZIP下载(<?=Disk::size($book->size / 3)?>)</a>
                        </td>
                    </tr>
                </table>
            </div>
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
<?php $this->extend('../layouts/footer');?>