<?php
defined('APP_DIR') or exit();

use Zodream\Template\View;
/** @var $this View */

$this->title = '插件管理';
?>

<div class="scroll-panel">
    <div class="panel-body">
        <div class="panel-swiper-item" *ngFor="let item of items">
            <div class="swiper-header">
                <i class="checkbox checked"></i>
            </div>
            <div class="swiper-body">
                <div class="item-header">
                    <span class="item-name">标题</span>
                    <span class="item-author">作者</span>
                    <div class="item-version">v5.1
                        <i class="iconfont icon-arrow-up" title="有更新"></i>
                    </div>
                </div>
                <div class="item-body">
                    说明
                </div>
            </div>
            <div class="swiper-action">
                <a class="btn-primary">运行</a>
                <a class="btn-danger">卸载</a>
            </div>
        </div>

        <app-loading-tip [loading]="isLoading" [visible]="isLoading || items.length == 0"></app-loading-tip>
    </div>
    <div class="panel-footer">
        <div class="panel-action">
            <i class="checkbox"></i>
            <div class="btn-group">
                <button class="btn btn-danger">删除选中项</button>
            </div>
        </div>
        <?=$items->getLink()?>
    </div>
</div>