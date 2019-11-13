<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $question['title'];

$js = <<<JS
bindDo();
JS;
$this->extend('layouts/main')->registerJs($js);
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
        <li class="active">
            顺序练习
        </li>
    </ul>
</div>
<div class="container">
    <div class="question-panel">
        <?php $this->extend('./panel');?>
    </div>
</div>