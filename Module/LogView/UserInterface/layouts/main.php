<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile([
    '@font-awesome.min.css',
    '@prism.css',
    '@zodream.css',
    '@zodream-admin.css',
    '@log.css'
])->registerJsFile([
    '@jquery.min.js',
    '@main.min.js',
    '@log.min.js'
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
        ZoDream Log Viewer
    </div>
</header>
<div class="container page-box">
    <div class="left-catelog navbar">
        <span class="left-catelog-toggle"></span>
        <ul>
            <li><a href="<?=$this->url('./')?>">
                    <i class="fa fa-home"></i><span>首页</span></a></li>
            <li class="expand"><a href="javascript:;">
                    <i class="fa fa-th-list"></i><span>文件列表</span></a>
                <ul>
                    <?php if (isset($file_list)):?>
                        <?php foreach($file_list as $item):?>
                            <li><a href="<?=$this->url('./log', ['id' => $item['id']])?>">
                                    <i class="fa fa-file"></i><span><?=$item['name']?></span></a></li>
                        <?php endforeach;?>
                    <?php endif;?>
                    <li><a href="<?=$this->url('./log/create')?>">
                            <i class="fa fa-plus"></i><span>上传文件</span></a></li>
                </ul>
            </li>
            <li class="expand"><a href="javascript:;">
                    <i class="fa fa-user-md"></i><span>智能分析</span></a>
                <ul>
                    <li><a href="<?=$this->url('./analysis')?>">
                            <i class="fa fa-line-chart"></i><span>次数曲线图</span></a></li>
                    <li><a href="<?=$this->url('./analysis/top')?>">
                            <i class="fa fa-list-ol"></i><span>统计排行</span></a></li>
                    <li><a href="<?=$this->url('./analysis/infer')?>">
                            <i class="fa fa-list-ol"></i><span>可疑推断</span></a></li>
                </ul>
            </li>
        </ul>
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