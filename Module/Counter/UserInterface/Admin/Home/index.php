<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '网站概况';
$js = <<<JS
bindHome();
JS;
$this->registerJs($js);
?>

<div class="template-lazy" data-url="<?=$this->url('./admin/home/today')?>">
    
</div>

<div class="tab-header">
    <a href="" class="active" data-type="today">今天</a>
    <a href="" data-type="yesterday">昨天</a>
    <a href="" data-type="week">最近7天</a>
    <a href="" data-type="month">最近30天</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="template-lazy" data-url="<?=$this->url('./admin/home/trend')?>"></div>
    </div>

    <div class="col-md-6">
        <div class="template-lazy" data-url="<?=$this->url('./admin/home/search_word')?>"></div>
        
    </div>
    <div class="col-md-6">
        <div class="template-lazy" data-url="<?=$this->url('./admin/home/source')?>"></div>
    </div>

    <div class="col-md-6">
        <div class="template-lazy" data-url="<?=$this->url('./admin/home/enter')?>"></div>
        
    </div>
    <div class="col-md-6">
        <div class="template-lazy" data-url="<?=$this->url('./admin/home/url')?>"></div>
        
    </div>

    <div class="col-md-6">
        <div class="template-lazy" data-url="<?=$this->url('./admin/home/district')?>"></div>
    </div>
</div>