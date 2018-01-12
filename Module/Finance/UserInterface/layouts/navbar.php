<?php
use Zodream\Template\View;
/** @var $this View */
?>

<ul>
    <li><a href="<?=$this->url('finance')?>">
            <i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-briefcase"></i><span>资金</span></a>
        <ul>
            <li><a href="<?=$this->url('finance/money')?>">
                    <i class="fa fa-list"></i><span>总资本</span></a></li>
            <li><a href="<?=$this->url('gzo/home/model')?>">
                    <i class="fa fa-list"></i><span>资本配置</span></a></li>
            <li><a href="<?=$this->url('gzo/home/crud')?>">
                    <i class="fa fa-edit"></i><span>资金形态</span></a></li>
        </ul>
    </li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-briefcase"></i><span>收支管理</span></a>
        <ul>
            <li><a href="<?=$this->url('gzo/home/sql')?>">
                    <i class="fa fa-list"></i><span>月收支</span></a></li>
            <li><a href="<?=$this->url('gzo/home/export')?>">
                    <i class="fa fa-list"></i><span>月流水</span></a></li>
         </ul>
    </li>
</ul>