<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $book['name'];
?>
<div class="container main-box">
    <div class="panel-container novel-header-box">
        <div class="row">
            <div class="col-md-3">
                <div class="cover">
                    <img src="<?=$book['cover']?>" alt="">
                </div>
            </div>
            <div class="col-md-9">
                <div class="title"><?=$book['name']?></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="line-item">
                            作者：<?=$book['author']['name']?>
                        </div>
                        <div class="line-item">
                            最新章节：<a href="">dsdsd</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="line-item">
                            操作：
                            <a>加入书架</a>
                        </div>
                        <div class="line-item">
                            当前阅读：<a href="">dsdsd</a>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <?=$book['cover']?>
                </div>
                <div class="action-bar">
                    <div class="btn-group">
                        <div class="btn btn-primary">立即阅读</div>
                        <div class="btn-group dropdown">
                            <a class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">下载</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?=$this->url('./book/txt', ['id' => $book['id']])?>">TXT</a>
                                <a class="dropdown-item" href="<?=$this->url('./book/zip', ['id' => $book['id']])?>">ZIP</a>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->node('ad-sense', ['code' => 'book_detail']) ?>

    <div class="panel-container chapter-box">
        <div class="catalog-header">
            <a class="right">
                <i class="fa fa-sort-alpha-down"></i>
                正序
            </a>
        </div>
        <div class="catalog-box">
            <div class="row">
                
                <?php foreach($chapter_list as $item):?>
                <?php if($item['type'] == 9):?>
                <div class="col-12 group-header">
                    <div class="title"><?= $item['title'] ?></div>
                </div>
                <?php else:?>
                <a href="<?=$this->url('./book/read', ['id' => $item['id']])?>" class="col-lg-4 col-md-6 item">
                    <div class="title">
                        <?php if($item == 1):?>
                        <i class="fa <?=$item['is_bought'] ? 'fa-lock' : 'fa-lock-open' ?>" title="Fee chapter"></i>
                        <?php endif;?>
                        <?= $item['title'] ?></div>
                    <div class="time"><?= $item['created_at'] ?></div>
                </a>
                <?php endif;?>
                <?php endforeach;?>
            </div>
            
        </div>
    </div>
</div>