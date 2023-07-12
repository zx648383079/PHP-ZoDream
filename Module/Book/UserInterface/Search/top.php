<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';

$items = ['click_bang' => '总点击榜', 
'month_click_bang' => '月点击榜', 
'week_click_bang' => '周点击榜', 
'recommend_bang' => '总推荐榜', 
'month_recommend_bang' => '月推荐榜', 
'week_recommend_bang' => '周推荐榜', 
'size_bang' => '字数榜', 
'new_book' => '新书榜', 
'over_book' => '完本榜'];

?>
<div class="container main-box">
    <div class="row">
        <div class="col-md-9 order-md-end">
            <div class="row">
                <?php foreach($items as $k => $label):?>
                <div class="col-md-6">
                    <div id="<?=$k?>" class="bang-panel mb-30">
                        <div class="panel-header">
                            <span><?= $label ?></span>
                            <a href="<?= $this->url('./search') ?>" class="more-link">更多&gt;&gt;</a>
                        </div>
                        <div class="panel-body">
                        <?php 
                        $book_list = $this->get($k);
                        if (empty($book_list) || !is_array($book_list)) {
                            $book_list = [];
                        }
                        foreach($book_list as $key => $item):?>
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
                <?php endforeach;?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="nav-menu-bar">
                <?php foreach($items as $k => $item):?>
                <a href="#<?=$k?>"><?= $item ?></a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>