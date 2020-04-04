<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '所有主题';
?>

<div class="card-box">
    <?php foreach($model_list as $item):?>
    <div class="card">
        <div class="card-logo">
            <a href="<?=$this->url('./@admin/theme', ['id' => $item->id])?>">
                <img src="<?=$this->url('./@admin/theme/cover', ['id' => $item->id])?>" alt="">
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