<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '菜单管理';
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>第一级只能添加3个菜单，第二级只能添加五个</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-primary pull-right" data-type="ajax" href="<?=$this->url('./@admin/menu/apply')?>">应用</a>
    </div>
    <div class="wx-page">
        <div class="tree-box">
            <?php foreach($menu_list as $menu):?>
            <div class="tree-item">
                <span><?=$menu['name']?></span>
                <div class="tree-action">
                    <a href="<?=$this->url('./@admin/menu/edit', ['id' => $menu['id']])?>">编辑</a>
                    <a data-type="del" href="<?=$this->url('./@admin/menu/delete', ['id' => $menu['id']])?>">删除</a>
                </div>
                <div class="tree-children">
                        <?php foreach($menu['children'] as $item):?>
                        <div class="tree-item">
                            <span><?=$item['name']?></span>
                            <div class="tree-action">
                                <a href="<?=$this->url('./@admin/menu/edit', ['id' => $item['id']])?>">编辑</a>
                                <a data-type="del" href="<?=$this->url('./@admin/menu/delete', ['id' => $item['id']])?>">删除</a>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="tree-add">
                            <a href="<?=$this->url('./@admin/menu/add', ['parent_id' => $menu['id']])?>">添加</a>
                        </div>
                </div>
            </div>
            <?php endforeach;?>
            <div class="tree-add">
                <a href="<?=$this->url('./@admin/menu/add')?>">添加</a>
            </div>
        </div>
        
    </div>
</div>
