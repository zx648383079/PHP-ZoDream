<?php
use Zodream\Template\View;
use Zodream\Domain\Access\Auth;
/** @var $this View */
?>

<ul>
    <li><a href="<?=$this->url('./')?>">
            <i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand disk-menu"><a href="<?=$this->url('./disk')?>">
            <i class="fa fa-files-o"></i><span>全部文件</span></a>
        <ul>
                <li><a href="<?=$this->url('./disk')?>#type/1">
                    <i class="fa fa-image"></i><span>图片</span></a></li>
                <li><a href="<?=$this->url('./disk')?>#type/2">
                    <i class="fa fa-file-word-o"></i><span>文档</span></a></li>
                <li><a href="<?=$this->url('./disk')?>#type/3">
                    <i class="fa fa-file-video-o"></i><span>视频</span></a></li>
                <li><a href="<?=$this->url('./disk')?>#type/4">
                    <i class="fa fa-gift"></i><span>种子</span></a></li>
                <li><a href="<?=$this->url('./disk')?>#type/5">
                    <i class="fa fa-file-sound-o"></i><span>音乐</span></a></li>
                <li><a href="<?=$this->url('./disk')?>#type/6">
                    <i class="fa fa-file-zip-o"></i><span>其他</span></a></li>
        </ul>
    </li>
    <li><a href="<?=$this->url('./share/my')?>">
            <i class="fa fa-share-alt"></i><span>我的分享</span></a></li>
    <li><a href="<?=$this->url('./trash')?>">
            <i class="fa fa-trash"></i><span>回收站</span></a></li>
</ul>
