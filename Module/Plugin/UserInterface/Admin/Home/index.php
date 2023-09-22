<?php
defined('APP_DIR') or exit();

use Zodream\Template\View;
/** @var $this View */

$this->title = '插件管理';
?>

<div class="scroll-panel page-multiple-table page-multiple-enable">
    <div class="panel-body">
        <?php foreach($items as $item):?>
        <div class="panel-swiper-item">
            <div class="swiper-header page-multiple-td" data-id="<?=$item['id']?>">
                <i class="checkbox"></i>
            </div>
            <div class="swiper-body">
                <div class="item-header">
                    <span class="item-name"><?= $item['name'] ?></span>
                    <span class="item-author"><?= $item['author'] ?></span>
                    <div class="item-version"><?= $item['version'] ?>
                        <i class="iconfont icon-arrow-up" title="有更新"></i>
                    </div>
                </div>
                <div class="item-body">
                    <?= $item['description'] ?>
                </div>
            </div>
            <div class="swiper-action">
                <a class="btn-primary">运行</a>
                <a class="btn-danger">卸载</a>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <div class="panel-footer">
        <div class="panel-action page-multiple-th">
            <i class="checkbox"></i>
            <div class="btn-group">
                <a class="btn btn-danger" href="<?=$this->url('./@admin/home/delete', ['id' => 0])?>" data-type="del">删除选中项(
                    <span class="page-multiple-count">0</span>
                    )</a>
                <a href="<?=$this->url('./@admin/home/sync')?>" data-type="ajax" class="btn btn-info">更新</a>
            </div>
        </div>
        <?=$items->getLink()?>
    </div>
</div>