
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
        <button class="right" data-target=".analysis-panel">显示详解</button>
    </div>
</div>

<div class="panel analysis-panel">
    <div class="panel-header">
        题目解析
    </div>
    <div class="panel-body">
        <?=empty($question['analysis']) ? '暂无解析' : $question['analysis']?>
    </div>
</div>