<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<ul>
    <li><a href="<?=$this->url('./')?>">
            <i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand disk-menu"><a href="javascript:;">
            <i class="fa fa-files-o"></i><span>模块管理</span></a>
        <ul>
            <li><a href="<?=$this->url('./admin/category')?>">
                <i class="fa fa-image"></i><span>栏目</span></a></li>
            <li><a href="<?=$this->url('./admin/model')?>">
                 <i class="fa fa-image"></i><span>模型</span></a></li>
            <li><a href="<?=$this->url('./admin/linkage')?>">
                 <i class="fa fa-image"></i><span>联动</span></a></li>
        </ul>
    </li>
    <li class="expand disk-menu"><a href="javascript:;">
            <i class="fa fa-files-o"></i><span>内容管理</span></a>
        <ul>
            <?php foreach($cat_list as $item):?>
            <li><a href="<?=$this->url('./admin/content', ['cat_id' => $item->id])?>">
                        <i class="fa fa-image"></i><span><?=$item->name?></span></a></li>
            <?php endforeach;?>
        </ul>
    </li>
</ul>