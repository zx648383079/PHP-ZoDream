<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Disk;
/** @var $this View */
$this->title = $model['name'];
$this->registerCssFile([
    '@blog.min.css',
    '@demo.min.css',
])
    ->registerJsFile([
        '@jquery.lazyload.min.js',
        '@demo.min.js'
    ])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>

<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>">应用商店首页</a>
        </li>
        <li class="active">
            <?=$this->text($model['name'])?>
        </li>
    </ul>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="app-detail-header">
                <div class="app-title-bar">
                    <div class="thumb">
                        <img src="<?=$model['icon']?>" alt="">
                    </div>
                    <div class="app-title-body">
                        <div class="title"><?=$this->text($model['name'])?></div>
                    </div>
                </div>
                
                
                <div class="package-box">
                    <?php foreach($model['packages'] as $item):?>
                    <div class="file-item">
                        <div class="item-tag">
                            <a><?= $item['os'] ?></a>
                            <a><?= $item['framework'] ?></a>
                            <a><?= Disk::size($item['size']) ?></a>
                        </div>
                        <div class="item-body">
                        <?= $item['name'] ?>
                        </div>
                        <div class="item-action">
                            <?php if($item['url_type'] < 1):?>
                            <a class="btn btn-info" href="<?=$this->url('./download', ['id' => $item['id']])?>" target="_blank">下载</a>
                            <?php elseif ($item['url_type'] > 0 && $item['url_type'] < 3):?>
                            <a class="btn btn-info" href="<?=$this->url($item['url'])?>" target="_blank">Open</a>
                            <?php else:?>
                            <a class="btn btn-info">Copy</a>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-info">
                <?php if($model['category']):?>
                <div class="line-item">
                    <i class="fa fa-th"></i>
                    分类：
                    <a href="<?=$this->url('./', ['category' => $model['cat_id']])?>"><?=$model['category']['name']?></a>
                </div>
                <?php endif;?>
                <div class="line-item">
                    <i class="fa fa-cloud"></i>
                    最新版本：<?=$model['version']['name']?>
                </div>
                <div class="line-item">
                    <i class="fa fa-calendar-check"></i>
                    更新时间：<?=$model['created_at']?>
                </div>
                <div class="line-item">
                    <i class="fa fa-file"></i>
                    文件大小：<?=Disk::size($model['size'])?>
                </div>
                <?php if($model['official_website']):?>
                <div class="line-item">
                    <i class="fa fa-globe"></i>
                    官网：<?=$model['official_website']?>
                </div>
                <?php endif;?>
                <?php if($model['is_open_source']):?>
                <div class="line-item">
                    <i class="fa fa-link"></i>
                    源码：<?=$model['git_url']?>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?= $this->node('ad-sense', ['code' => 'app_detail']) ?>
    <div class="detail-body">
        <article id="content" class="style-type-1">
            <?=$model['content']?>
        </article>
    </div>
    
</div>