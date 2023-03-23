<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '所有主题页面';
?>
<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">页面</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="页面" value="<?=$this->text($keywords)?>">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <input type="hidden" name="site_id" value="<?=$site_id?>">
        <input type="hidden" name="type" value="<?=$type?>">
    </form>
</div>
<div class="card-box">
    <?php foreach($model_list as $item):?>
    <div class="card">
        <div class="card-logo">
            <a href="<?=$this->url('./@admin/page/create', ['page_id' => $item->component_id, 'site_id' => $site_id, 'type' => $type])?>">
                <img src="<?=$this->url('./@admin/theme/asset', ['folder' => $item->path, 'file' => $item->thumb], false)?>" alt="">
            </a>
        </div>
        <div class="card-body">
            <h3><?=$item->name?></h3>
            <p><?=$item->description?></p>
        </div>
    </div>
    <?php endforeach;?>
</div>

<?= $model_list->getLink()?>