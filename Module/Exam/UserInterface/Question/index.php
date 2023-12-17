<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $question['title'];
$url = $this->url('./', false);
$js = <<<JS
bindDoQuestion('{$url}');
JS;
$this->extend('layouts/main')
    ->registerCssFile('@dialog.min.css')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@main.min.js')->registerJs($js);
?>
<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>" >题库首页</a>
        </li><li class="active">
            <a href="<?=$this->url('./course', ['id' => $course->id])?>" ><?=$course->name?></a>
        </li>
    </ul>
</div>
<div class="container">
    <div class="question-panel">
        <?php $this->extend('./panel');?>
    </div>
</div>