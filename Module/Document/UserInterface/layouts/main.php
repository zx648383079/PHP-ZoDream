<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@prism.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@doc.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@prism.js',
        '@main.min.js',
        '@doc.min.js'
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
            ZoDream Document
        </div>
    </header>
    <div class="container page-box">
        <div class="left-catelog navbar">
            <span class="left-catelog-toggle"></span>
            <?php if(isset($project_list)):?>
            <ul>
                <li><a href="<?=$this->url('./')?>">
                        <i class="fa fa-home"></i><span>首页</span></a></li>
                <li class="expand"><a href="javascript:;">
                        <i class="fa fa-money"></i><span>项目列表</span></a>
                    <ul>
                        <?php foreach($project_list as $item):?>
                        <li><a href="<?=$this->url('./project', ['id' => $item['id']])?>">
                                <i class="fa fa-book"></i><span><?=$item['name']?></span></a></li>
                        <?php endforeach;?>
                    </ul>
                </li>
            </ul>
            <?php else:?>
            <ul>
                <li><a href="<?=$this->url('./')?>">
                        <i class="fa fa-arrow-left"></i><span>返回首页</span></a></li>
                <li><a href="<?=$this->url('./project', ['id' => $project->id])?>">
                        <i class="fa fa-home"></i><span>项目主页</span></a></li>
                <?php foreach($tree_list as $item):?>
                <li class="active"><a href="javascript:;">
                        <i class="fa fa-folder-open"></i><span><?=$item['name']?></span></a>
                    <ul>
                        <?php foreach($item['children'] as $child):?>
                        <li><a href="<?=$this->url('./api', ['id' => $child['id']])?>">
                                <i class="fa fa-file"></i><span><?=$child['name']?></span></a></li>
                        <?php endforeach;?>
                    </ul>
                </li>
                <?php endforeach;?>
            
            </ul>
            <?php endif;?>
        </div>
        <div class="right-content">
            <?=$content?>
        </div>
    </div>
    <footer class="page-footer">
        <a href="http://www.miitbeian.gov.cn/" target="_blank">湘ICP备16003508号</a>
    </footer>
   <?=$this->footer()?>
   </body>
</html>