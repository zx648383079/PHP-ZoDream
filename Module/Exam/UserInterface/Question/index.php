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
    <?php $this->extend('./item');?>
    <div class="tool-bar">
        <div class="btn-bar">
            <button class="left">上一题</button>
            <button class="left">下一题</button>
            <button class="right" data-target=".sheet-panel">显示答题卡</button>
            <button class="right" data-target=".analysis-panel">显示详解</button>
        </div>
        <div class="msg-bar">
            <label class="">
                <input type="checkbox" checked=""
                    class="checkbox-next"><span>&nbsp;答对自动下一题</span>
            </label>
            <span class="left">
                <span
                    class="gray">答对：</span><span class="count-right">1&nbsp;题</span>
            </span>
            <span
                class="left">
                <span class="gray">答错：</span>
                <span class="count-wrong">2&nbsp;题</span>
                </span>
            <span class="left">
                <span
                    class="gray">正确率：</span>33%</span>
        </div>
    </div>

    <div class="panel analysis-panel">
        <div class="panel-header">
            题目解析
        </div>
        <div class="panel-body">
            <?=empty($model->analysis) ? '暂无解析' : $model->analysis?>
        </div>
    </div>
    <div class="panel sheet-panel">
        <div class="panel-header">
            答题卡
        </div>
        <div class="panel-body">
            <ul>
                <?php foreach($question_list as $i => $item):?>
                <li data-id="<?=$item?>"><?=$i + 1?></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>

</div>