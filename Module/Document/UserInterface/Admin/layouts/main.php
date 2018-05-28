<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
        '@font-awesome.min.css',
        '@prism.css',
        '@zodream.css',
        '@zodream-admin.css',
        '@dialog.css',
        '@doc.css'
    ])->registerJsFile([
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
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
            ZoDream Document Admin
        </div>
    </header>
    <div class="container page-box">
        <div class="left-catelog navbar">
            <span class="left-catelog-toggle"></span>
            <?php if(isset($project_list)):?>
            <ul>
                <li><a href="<?=$this->url('./admin')?>">
                        <i class="fa fa-home"></i><span>首页</span></a></li>
                <li class="expand"><a href="javascript:;">
                        <i class="fa fa-money"></i><span>项目列表</span></a>
                    <ul>
                        <?php foreach($project_list as $item):?>
                        <li><a href="<?=$this->url('./admin/project', ['id' => $item['id']])?>">
                                <i class="fa fa-book"></i><span><?=$item['name']?></span></a></li>
                        <?php endforeach;?>
                        <li><a href="<?=$this->url('./admin/project/create')?>">
                                <i class="fa fa-plus"></i><span>新建项目</span></a></li>
                    </ul>
                </li>
            </ul>
            <?php else:?>
            <ul>
                <li><a href="<?=$this->url('./admin')?>">
                        <i class="fa fa-arrow-left"></i><span>返回首页</span></a></li>
                <li><a href="<?=$this->url('./admin/project', ['id' => $project->id])?>">
                        <i class="fa fa-home"></i><span>项目主页</span></a></li>
                <?php foreach($tree_list as $item):?>
                <li class="active"><a href="javascript:;">
                        <i class="fa fa-folder-open"></i><span><?=$item['name']?></span></a>
                    <ul>
                        <?php if(isset($item['children'])):?>
                        <?php foreach($item['children'] as $child):?>
                        <li><a href="<?=$this->url('./admin/api', ['id' => $child['id']])?>">
                                <i class="fa fa-file"></i><span><?=$child['name']?></span></a></li>
                        <?php endforeach;?>
                        <?php endif;?>
                        <li><a href="<?=$this->url('./admin/api/create', ['project_id' => $project->id, 'parent_id' => $item['id']])?>">
                                <i class="fa fa-plus"></i><span>新建接口</span></a></li>
                    </ul>
                </li>
                <?php endforeach;?>
                <li><a href="<?=$this->url('./admin/api/create', ['project_id' => $project->id])?>">
                                <i class="fa fa-plus"></i><span>新建模块</span></a></li>
            </ul>
            <?php endif;?>
        </div>
        <div class="right-content">
            <?=$content?>
        </div>
    </div>
   <?=$this->footer()?>
   </body>
</html>