<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Disk;
/** @var $this View */
$this->title = $post['title'];
$this->registerCssFile([
    '@blog.css',
    '@demo.css',
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
            <a href="<?=$this->url('./')?>">资源商店首页</a>
        </li>
        <li class="active">
            <?=$this->text($post['title'])?>
        </li>
    </ul>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="detail-header">
                <div class="title"><?=$this->text($post['title'])?></div>
                <div class="tags">
                    <?php foreach($tags as $i => $item):?>
                        <?php if ($i > 0):?>
                            ，
                        <?php endif;?>
                        <a class="link-btn" href="<?=$this->url('./', ['tag' => $item['name']])?>">
                            <?=__($item['name'])?>
                        </a>
                    <?php endforeach;?>
                </div>
                <div class="thumb">
                    <img src="<?=$post['thumb']?>" alt="">
                    <a class="preview-btn" href="<?=$this->url('./preview', ['id' => $post['id']])?>">
                        <i class="fa fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-info">
                <div class="top">
                    <a class="link-btn" href="<?=$this->url('./download', ['id' => $post['id']])?>" target="_blank">下载</a>
                    <a class="link-btn" href="<?=$this->url('./preview', ['id' => $post['id']])?>" target="_blank">在线预览</a>
                </div>
                <?php if($post['category']):?>
                <div class="line-item">
                    <i class="fa fa-th"></i>
                    分类：
                    <a href="<?=$this->url('./', ['category' => $post['cat_id']])?>"><?=$post['category']['name']?></a>
                </div>
                <?php endif;?>
                <div class="line-item">
                    <i class="fa fa-user"></i>
                    看看谁在用
                </div>
                <a class="line-item" href="#catalog">
                    <i class="fa fa-folder"></i>
                    查看文件结构
                </a>
                <div class="line-item">
                    <i class="fa fa-calendar-check"></i>
                    更新时间：<?=$post['created_at']?>
                </div>
                <div class="line-item">
                    <i class="fa fa-file"></i>
                    文件大小：<?=Disk::size($post['size'])?>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-body">
        <article id="content" class="style-type-1">
            <?=$post['content']?>
        </article>

        <div id="catalog" class="catalog-box template-lazy" data-url="<?=$this->url('./home/catalog', ['id' => $post['id']])?>">
                
        </div>
    </div>
</div>