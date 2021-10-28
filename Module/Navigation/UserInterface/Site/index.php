<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
<div class="container main-container on-search">
    <?php $this->extend('layouts/search');?>

    <div class="row">
        <div class="col-md-4">
            <div class="panel site-group-panel">
                <div class="panel-header" *ngIf="current">
                    <i class="iconfont icon-chevron-left" (click)="tapBack()"></i>
                    {{ current.name }}
                </div>
                <div class="panel-body">
                    <ng-container *ngFor="let item of kidItems; let i = index">
                        <div class="cat-item" (click)="tapItem(i)">
                            <div class="item-icon">
                                <img [src]="item.icon">
                            </div>
                            <div class="item-name">{{ item.name }}</div>
                        </div>
                    </ng-container>
                    
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <?php foreach($categories as $item):?>
                <div class="lazy-panel" appLazyLoad (lazyLoading)="loadItem(item)">
                    <div class="panel">
                        <div class="panel-header">
                            {{ header }}
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4" *ngFor="let item of items">
                                    <div class="site-item" [title]="item.name">
                                        <div class="item-name">
                                            <a [href]="formatLink(item)" target="_blank" rel="noopener"><img class="lazy" [src]="item.logo" [alt]="item.name"></a>
                                            <a [href]="formatLink(item)" target="_blank" rel="noopener">{{ item.name }}</a>
                                        </div>
                                        <div class="item-desc">
                                            {{ item.description }}
                                        </div>
                                        <a class="go-btn" [href]="formatLink(item)" target="_blank" rel="noopener" title="直接访问">
                                            <i class="iconfont icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>