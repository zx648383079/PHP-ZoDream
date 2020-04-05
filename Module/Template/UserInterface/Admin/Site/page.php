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
<div id="page-dialog" class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">新增页面</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="page-select">
            <?php foreach($template_list as $item):?>
            <div class="page-item" data-id="<?=$item->id?>">
                <div class="thumb">
                    <img src="<?=$this->url('./@admin/theme/asset',
                        ['file' => $item['thumb']], false)?>" alt="">
                </div>
                <div class="name"><?=$item['name']?></div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button"
            class="dialog-close">取消</button>
    </div>
</div>