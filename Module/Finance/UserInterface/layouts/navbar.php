<?php
use Zodream\Template\View;
/** @var $this View */
?>

<ul>
    <li><a href="<?=$this->url('./')?>">
            <i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-briefcase"></i><span>资金</span></a>
        <ul>
            <li><a href="<?=$this->url('./money')?>">
                    <i class="fa fa-list"></i><span>总资本</span></a></li>
            <li><a href="<?=$this->url('./money/account')?>">
                    <i class="fa fa-list"></i><span>资金账户</span></a></li>
            <li><a href="<?=$this->url('./money/project')?>">
                    <i class="fa fa-edit"></i><span>理财项目</span></a></li>
            <li><a href="<?=$this->url('./money/product')?>">
                    <i class="fa fa-edit"></i><span>理财产品</span></a></li>
        </ul>
    </li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-briefcase"></i><span>收支管理</span></a>
        <ul>
            <li><a href="<?=$this->url('./income')?>">
                    <i class="fa fa-list"></i><span>月收支</span></a></li>
            <li><a href="<?=$this->url('./income/log')?>">
                    <i class="fa fa-list"></i><span>月流水</span></a></li>
            <li><a href="<?=$this->url('./income/channel')?>">
                    <i class="fa fa-list"></i><span>消费渠道</span></a></li>
         </ul>
    </li>
    <li><a href="<?=$this->url('./budget')?>">
            <i class="fa fa-home"></i><span>生活预算</span></a></li>
</ul>