<?php
use Zodream\Template\View;
/** @var $this View */
$this->extend('layout/header');
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>第一级只能添加3个菜单，第二级只能添加五个</li>
    </ul>
    <span class="toggle"></span>
</div>
<div class="wx-page">

<div class="tree">
    <?php foreach($menu_list as $menu):?>
    <div class="tree-item">
        <span><?=$menu->name?></span>
        <div class="tree-action">
            <a href="<?=$this->url('./menu/edit', ['id' => $menu->id])?>">编辑</a>
            <a data-type="del" href="<?=$this->url('./menu/delete', ['id' => $menu->id])?>">删除</a>
        </div>
        <div class="tree-children">
                <?php foreach($menu->children as $item):?>
                <div class="tree-item">
                    <span><?=$item->name?></span>
                    <div class="tree-action">
                        <a href="<?=$this->url('./menu/edit', ['id' => $item->id])?>">编辑</a>
                        <a data-type="del" href="<?=$this->url('./menu/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </div>
                <?php endforeach;?>
                <div class="tree-add">
                    <a href="<?=$this->url('./menu/add')?>">添加</a>
                </div>
        </div>
    </div>
    <?php endforeach;?>
    <div class="tree-add">
        <a href="<?=$this->url('./menu/add')?>">添加</a>
    </div>
</div>
    
</div>

<?php
$this->extend('layout/footer');
?>