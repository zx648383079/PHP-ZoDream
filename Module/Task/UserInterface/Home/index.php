<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Task';
$url = $this->url('./', false);
$js = <<<JS
bindTask('{$url}');
JS;
$this->registerJs($js);
?>

<div class="panel">
    <div class="panel-header">
    今日待办事项
    <a href="javascript:;" class="pull-right" data-action="add">
        <i class="fa fa-plus"></i>
    </a>
    </div>
    <div class="panel-body">
        
    </div>
</div>

<div class="dialog-panel">
    <div class="dialog-header">
        待办事项
    </div>
    <div class="task-list">
    <?php foreach($task_list as $item):?>
    <div class="task-item" data-id="<?=$item->id?>">
        <?=$item->name?>
    </div>
    <?php endforeach;?>
    </div>
    <a href="javascript:;" class="btn">确定</a>
</div>

<div class="dialog-timer">
    <div class="dialog-body">
        <div class="timer-box">
            12:00
        </div>
        <div class="timer-tip">画画</div>
    </div>

    <div class="dialog-footer">
        <a href="javascript:;" class="timer-pause" title="暂停计时">
            <i class="fa fa-pause-circle"></i>
        </a>
        <a href="javascript:;" class="timer-close" title="停止计时">
            <i class="fa fa-times-circle"></i>
        </a>
    </div>
</div>