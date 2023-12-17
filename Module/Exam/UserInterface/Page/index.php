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
    ->registerCssFile('@dialog.min.css')
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
            <span class="time-counter" data-start="<?=$evaluate->created_at?>" data-limit="<?=$page->limit_time?>"></span>
        </p>
    </div>
    <div class="question-panel">
        <?php foreach($items as $item):?>
            <?php $this->extend('Pager/item'
                , ['question' => $item]);?>
        <?php endforeach;?>
    </div>
    <div class="tool-bar">
        <a data-type="ajax" href="<?=$this->url('./page/check', ['id' => $evaluate->id])?>">交卷</a>
    </div>
</div>
