<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use ZoDream\Helpers\Str;
/** @var $this View */
$this->title = $cat->name;
?>
<div class="container main-box">
    <div class="row mt-30">
    <?php foreach($hot_book as $item):?>
        <div class="col-md-4">
            <div class="novel-item width-not-border">
                <div class="item-cover">
                    <a href="<?=$item['url']?>">
                        <img src="<?=$item['cover']?>" alt="<?=$item['name']?>">
                        <div class="mask">
            
                        </div>
                    </a>
                </div>
                <div class="item-body">
                    <div class="item-name">
                        <a href="<?=$item['url']?>"><?=$item['name']?></a>
                    </div>
                    <div class="item-tags">
                        <a>
                            <i class="fa fa-user"></i>
                            <?=$item['author']['name']?>
                        </a>
                        <a href="<?=$item['cat']['url']?>"><?=$item['cat']['real_name']?></a>
                        <a href="javascription:;"><?=$item['status_label']?></a>
                    </div>
                    <div class="item-meta">
                        <?=$item['description']?>
                    </div>
                    <div class="item-status">
                        <span><?=$item['format_size']?></span>
                        <?php if (!$item['over_at'] && $item['last_chapter']):?>
                            <a href="<?=$item['last_chapter']['url']?>"><?=$item['last_chapter']['name']?></a>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="panel">
                <div class="panel-header">
                    最近更新
                </div>
                <table class="table table-hover novel-table">
                    <thead>
                        <tr>
                            <th>类别</th>
                            <th>小说书名</th>
                            <th>最新章节</th>
                            <th>字数</th>
                            <th>小说作者</th>
                            <th>更新时间</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($book_list as $item):?>
                        <tr>
                            <td>「<a href="<?=$item['cat']['url']?>"><?=$item['cat']['real_name']?></a>」</td>
                            <td>
                                <a href="<?=$item['url']?>"><?=$item['name']?></a>
                            </td>
                            <td>
                                <?php if (!$item['over_at'] && $item['last_chapter']):?>
                                    <a href="<?=$item['last_chapter']['url']?>"><?=$item['last_chapter']['name']?></a>
                                <?php endif;?>
                            </td>
                            <td><?=$item['format_size']?></td>
                            <td><?=$item['author']['name']?></td>
                            <td><?=$item['updated_at']?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bang-panel mb-30">
                <div class="panel-header">
                    <span>点击榜单</span>
                    <a href="<?=$this->url('./search/top')?>" class="more-link">更多&gt;&gt;</a>
                </div>
                <div class="panel-body">
                    <?php foreach($click_bang as $key => $item):?>
                    <div class="novel-bang-item">
                        <div class="item-no"><?= $key + 1 ?></div>
                        <div class="item-body">
                            <a class="item-name" href="<?=$item['url']?>"><?=$item['name']?></a>
                            <div class="item-cover">
                                <img src="<?=$item['cover']?>" alt="<?=$item['name']?>">
                            </div>
                            <div class="item-author">
                                <?=$item['author']['name']?>
                            </div>
                            <div class="item-count"><?=$item['format_size']?></div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>