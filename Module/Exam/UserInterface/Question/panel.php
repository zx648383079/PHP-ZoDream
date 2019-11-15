
<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php $this->extend('./item');?>
<div class="tool-bar">
    <div class="btn-bar">
        <?php if($previous_url):?>
        <a class="left btn" href="<?=$previous_url?>">上一题</a>
        <?php endif;?>
        <?php if($next_url):?>
        <a class="left btn" href="<?=$next_url?>">下一题</a>
        <?php endif;?>
        <button class="right" data-target=".sheet-panel">显示答题卡</button>
        <button class="right" data-target=".analysis-panel">显示详解</button>
    </div>
    <div class="msg-bar">
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