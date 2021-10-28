<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = sprintf('搜索“%s”', $this->text($keywords));
?>
<div class="container main-container on-search">
    <?php $this->extend('layouts/search');?>

    <?php if($items->isEmpty()):?>
        <div class="empty-tip">
        搜索结果为空
        </div>
    <?php else:?>
        <?php foreach($items as $item):?>
        <div class="page-container<?= $item['thumb'] ? '' : ' page-not-thumb' ?>">
            <div class="page-header">
                <a href="<?= $this->url($item['link']) ?>" target="_blank" rel="noopener"><?= $this->text($item['title']) ?></a>
                <?php if($item['score'] >= 80):?>
                    <i class="fa fa-shield-alt" title="此网页安全"></i>
                <?php elseif ($item['score'] < 60):?>
                    <i class="fa fa-exclamation-triangle" title="<?= $item['score'] < 10 ? '此网页含有危险内容' : '此网页不推荐访问' ?>"></i>
                <?php endif;?>
            </div>
            <div class="page-flex-body">
                <?php if($item['thumb']):?>
                <div class="page-thumb">
                    <img src="<?= $this->url($item['thumb']) ?>" alt="<?= $this->text($item['title']) ?>">
                </div>
                <?php endif;?>
                <div class="page-body">
                    <span class="page-time"><?= $this->ago($item['updated_at']) ?></span> <span class="page-content"><?= $this->text($item['description']) ?></span>
                    <div class="page-footer">
                        <?php if($item['site']):?>
                            <a class="page-site" href="<?= $item['site']['schema'] ?>://<?= $item['site']['domain'] ?>" target="_blank" rel="noopener">
                                <?php if($item['site']['logo']):?>
                                <div class="site-logo">
                                    <img src="<?=$item['site']['logo']?>" alt="<?=$item['site']['name']?>">
                                </div>
                                <?php endif;?>
                                <span class="site-name"><?=$item['site']['name']?></span>
                            </a>
                        <?php else:?>
                            <a class="page-site" href="<?= $this->url($item['link']) ?>" target="_blank" rel="noopener">
                            <span class="site-name"><?= parse_url($item['link'], PHP_URL_HOST) ?></span>
                            </a>
                        <?php endif;?>
                        
                        
                        <div class="page-action">
                            <div class="drop-icon">
                                <i class="iconfont icon-ellipsis-h"></i>
                            </div>
                            <div class="drop-body">
                                <div class="menu-body">
                                    <div class="menu-item">收藏</div>
                                    <div class="menu-item">分享</div>
                                    <div class="menu-item">举报</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>

        <?=$items->getLink()?>
    <?php endif;?>

    
</div>