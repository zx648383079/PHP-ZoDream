<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
<div class="container main-box">
    <div class="row">
        <div class="col-md-3">
            <div class="filter-box">
                <div class="filter-line">
                      <div class="filter-header">
                          分类
                      </div>
                      <div class="filter-body">
                            <a class="<?= $cat_id < 1 ? 'active' : '' ?>">全部</a>
                            <?php foreach($cat_list as $item):?>
                            <a href="<?= $this->url('./search', ['cat_id' => $item['id']]) ?>" class="<?= $item['id'] === $cat_id ? 'active' : '' ?>"><?=$item['name']?></a>
                            <?php endforeach;?>
                      </div>
                </div>
                <div class="filter-line">
                    <div class="filter-header">
                        排序
                    </div>
                    <div class="filter-body">
                        <a class="active">热度
                            <i class="fa fa-sort-alpha-down"></i>
                        </a>
                        <a class="active">热度
                            <i class="fa fa-sort-alpha-up"></i>
                        </a>
                        <a>热度
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <?php if($mode < 1):?>
            <div class="row">
            <?php foreach($book_list as $item):?>
            <div class="col-md-6">
                <div class="novel-item">
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
            <?php else:?>
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
            <?php endif;?>

            <?= $book_list->pageLink() ?>
        </div>
    </div>
</div>