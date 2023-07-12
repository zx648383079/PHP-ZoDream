<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '作者：'.$author['name'];
?>
<div class="container main-box">

    <div class="panel-container novel-header-box">
        <div class="row">
            <div class="col-md-3">
                <div class="cover">
                    <img src="<?= $author['avatar'] ?>" alt="">
                </div>
            </div>
            <div class="col-md-9">
                <div class="title"><?= $author['name'] ?></div>
                <div class="row">
                    <div class="col-12">
                        <div class="line-item">
                            作品总数：<?= 0 ?>
                        </div>
                        <div class="line-item">
                            累计字数：<?= 0 ?>
                        </div>
                        <div class="line-item">
                            累计天数：<?= 0 ?>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <?= $author['description'] ?>
                </div>
                <div class="action-bar">
                    <div class="btn-group">
                        <div class="btn btn-primary">关注</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-30">
    <?php foreach($book_list as $item):?>
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

</div>