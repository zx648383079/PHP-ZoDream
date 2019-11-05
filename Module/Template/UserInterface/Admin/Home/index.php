<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '站点选择';
?>

<div class="container">
    <div class="card-box">
        <?php foreach($site_list as $item):?>
        <div class="card">
            <div class="card-logo">
                <a href="<?=$this->url('./admin/site', ['id' => $item->id])?>">
                    <img src="<?=$item->thumb?>" alt="">
                </a>
            </div>
            <div class="card-body">
                <h3><?=$item->title?></h3>
                <p><?=$item->description?></p>
            </div>
            <div class="card-action">
                <a href="<?=$this->url('./admin/site/edit', ['id' => $item->id])?>" class="fa fa-edit"></a>
                <a data-type="del" href="<?=$this->url('./admin/site/delete', ['id' => $item->id])?>" class="fa fa-trash"></a>
            </div>
        </div>
        <?php endforeach;?>
        

        <div class="card card-add">
            <div class="card-logo">
                <a href="<?=$this->url('./admin/site/create')?>">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
</div>