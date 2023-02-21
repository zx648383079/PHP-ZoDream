<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = $site->title;
?>

<div class="container">
    <?=Form::open($site, './@admin/site/save')?>
        <div class="tab-box">
            <div class="tab-header">
                <div class="tab-item">
                    基本
                </div>
                <div class="tab-item">
                    高级
                </div>
            </div>
            <div class="tab-body">
                <div class="tab-item">
                    <?=Form::text('name', true)?>
                    <?=Form::text('title', true)?>
                    <?=Form::text('keywords')?>
                    <?=Form::text('domain')?>
                    <?=Form::file('thumb')?>
                    <?=Form::textarea('description')?>
                    <button type="submit" class="btn btn-success">确认保存</button>
                </div>
                <div class="tab-item">
                    
                </div>
            </div>
        </div>
        
    <?= Form::close('id') ?>
    <div class="card-box">
        <?php foreach($page_list as $item):?>
        <div class="card">
            <div class="card-logo">
                <a href="<?=$this->url('./@admin/page', ['id' => $item->id])?>">
                    <img src="<?=$item->thumb?>" alt="">
                </a>
            </div>
            <div class="card-body">
                <h3><?=$item->title?></h3>
                <p><?=$item->descriptio ?></p>
            </div>
        </div>
        <?php endforeach;?>
        

        <div class="card card-add">
            <div class="card-logo">
                <a href="<?=$this->url('./@admin/page/create', ['site_id' => $site->id])?>">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            <div class="card-body">
                <h3>新增普通网页</h3>
                <p></p>
            </div>
        </div>

        <div class="card card-add">
            <div class="card-logo">
                <a href="<?=$this->url('./@admin/page/create', ['site_id' => $site->id, 'type' => 1])?>">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            <div class="card-body">
                <h3>新增WAP网页</h3>
                <p></p>
            </div>
        </div>
    </div>
</div>