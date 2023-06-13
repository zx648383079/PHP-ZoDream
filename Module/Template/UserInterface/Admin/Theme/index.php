<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '所有主题';
?>
<div class="page-search-bar">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">主题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="主题" value="<?=$this->text($keywords)?>">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/theme/install')?>" data-type="ajax">刷新</a>
</div>
<div class="card-box">
    <?php foreach($model_list as $item):?>
    <div class="card">
        <div class="card-logo">
            <a href="<?=$this->url('./@admin/site/create', ['theme_id' => $item->id])?>">
                <img src="<?=$this->url('./@admin/theme/asset', ['folder' => $item->path, 'file' => $item->thumb], false)?>" alt="">
            </a>
        </div>
        <div class="card-body">
            <h3><?=$item->name?></h3>
            <p><?=$item->description?></p>
        </div>
        <div class="card-action">
            <a data-type="del" href="<?=$this->url('./@admin/theme/delete', ['id' => $item->id])?>" class="fa fa-trash"></a>
        </div>
    </div>
    <?php endforeach;?>
</div>

<?= $model_list->getLink()?>