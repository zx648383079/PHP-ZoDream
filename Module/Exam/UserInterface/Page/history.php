<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $page->name;
$url = $this->url('./', false);
$js = <<<JS
bindDoPage('{$url}');
JS;
$this->extend('layouts/main')
    ->registerCssFile('@dialog.css')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@main.min.js')
    ->registerJs($js);
?>

<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>" >题库首页</a>
        </li>
        <li class="active">
            <?=$page->name?>
        </li>
    </ul>
</div>

<div class="container page-box">
    <div class="page-info">
        <h2 class="page-name"><?=$page->name?></h1>
        <p>
            <span>耗时：<?=$evaluate->spent_time?> 分</span>
            <span>正确：<?=$evaluate->right?> 个</span>
            <span>错误：<?=$evaluate->wrong?> 个</span>
            <span>得分：<?=$evaluate->score?></span>
        </p>
        <div class="page-score"><?=$evaluate->score?></div>
    </div>
    <div class="question-panel">
        <?php foreach($items as $item):?>
            <?php $this->extend('Pager/view'
                , ['question' => $item]);?>
        <?php endforeach;?>
    </div>
    <div class="page-remark">
        <h3>评语</h3>
        <?=$evaluate->remark?>
    </div>
</div>
