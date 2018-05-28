<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@disk.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@vue.js',
        '@main.min.js',
        '@disk.min.js'
    ]);
?>
<!DOCTYPE html>
<html lang="<?=$this->get('language', 'zh-CN')?>">
   <head>
       <meta name="viewport" content="width=device-width, initial-scale=1"/>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="Description" content="<?=$this->description?>" />
       <meta name="keywords" content="<?=$this->keywords?>" />
       <title><?=$this->title?></title>
       <?=$this->header();?>
   </head>
   <body>
   <header>
        <div class="container">
            ZoDream Disk
        </div>
    </header>
    <div class="container page-box">
        <div class="left-catelog navbar">
            <span class="left-catelog-toggle"></span>
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
        </div>
        <div class="right-content">
            <?=$content?>
        </div>
    </div>
   <?=$this->footer()?>
   </body>
</html>