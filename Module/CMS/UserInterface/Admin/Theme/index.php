<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '本地主题';
?>
<div class="panel">
    <div class="panel-header">当前主题</div>
    <div class="panel-body">
        <div class="theme-item">
            <div class="thumb">
                <img src="<?=$this->url('./admin/theme/cover',
                    ['theme' => $current['name']], false)?>" alt="">
            </div>
            <div class="name"><?=$current['name']?></div>
            <div class="desc"><?=$current['description']?></div>
            <a data-type="del" data-tip="是否确定备份此主题？" href="<?=$this->url('./admin/theme/back', false)?>" class="btn">备份</a>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">本地主题</div>
    <div class="panel-body">
        <?php foreach($themes as $item):?>
        <div class="theme-item">
            <div class="thumb">
                <img src="<?=$this->url('./admin/theme/cover',
                    ['theme' => $item['name']], false)?>" alt="">
            </div>
            <div class="name"><?=$item['name']?></div>
            <div class="desc"><?=$item['description']?></div>
            <a data-type="del" data-tip="是否确定清空数据并使用此主题？" href="<?=$this->url('./admin/theme/apply',
                ['theme' => $current['name']], false)?>" class="btn">使用</a>
        </div>
        <?php endforeach;?>
    </div>
</div>
