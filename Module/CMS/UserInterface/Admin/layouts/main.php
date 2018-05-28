<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@zodream.css',
        '@dialog.css',
        '@cms_admin.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@cms_admin.min.js'
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
            ZoDream CMS Admin
        </div>
    </header>
    <div class="container page-box">
        <div class="left-catelog navbar">
            <span class="left-catelog-toggle"></span>
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
        </div>
        <div class="right-content">
            <?=$content?>
        </div>
    </div>
   <?=$this->footer()?>
   </body>
</html>