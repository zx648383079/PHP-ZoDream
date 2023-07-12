<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '历史记录';
?>

<div class="container main-box">
    <div class="row">
        <div class="col-12">
            <div class="panel mt-30">
                <div class="panel-header">
                    历史记录
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php foreach($book_list as $item):?>
                        <div class="col-md-6">
                            <div class="novel-history-item">
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
                                        <a href="<?=$this->url('./search', ['author_id' => $item['author_id']])?>">
                                            <i class="fa fa-user"></i>
                                            <?=$item['author']['name']?>
                                        </a>
                                        <a href="<?=$item['cat']['url']?>"><?=$item['cat']['real_name']?></a>
                                        <a href="javascription:;"><?=$item['status_label']?></a>
                                    </div>
                                    <div class="item-icon-line">
                                        <i class="fa fa-history"></i>
                                        <a href="<?=$this->url('./book/read')?>">最近阅读到</a>
                                    </div>
                                    <?php if (!$item['over_at'] && $item['last_chapter']):?>
                                    <div class="item-icon-line">
                                        <i class="fa fa-compass"></i>
                                        <a href="<?=$item['last_chapter']['url']?>"><?=$item['last_chapter']['name']?></a>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <div class="item-action">
                                    <a href="" class="btn btn-primary">删除</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>