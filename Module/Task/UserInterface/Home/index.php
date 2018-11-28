<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Task';
$url = $this->url('./');
$js = <<<JS
bindTask('{$url}');
JS;
$this->registerJs($js);
?>

<div class="panel">
    <div class="panel-header">
    任务管理平台
    </div>
    <div class="panel-body">
    <?php foreach($model_list as $item):?>
        <div class="task-item<?=$item->status > 0 ? ' active' : ''?>" data-id="<?=$item->id?>">
            <div class="name"><?=$item->name?></div>
            <div class="time" data-start="<?=$item->start_at?>"><?=$item->time_length?></div>
            <div class="actions">
                <a href="javascript:;" class="fa fa-pause-circle" alt="停止计时">
                </a>
                <a href="javascript:;" class="fa fa-play-circle" alt="开始计时">
                </a>
                <a href="javascript:;" class="fa fa-stop-circle" alt="终止任务">
                </a>
            </div>
            <div class="desc"><?=$item->description?></div>
        </div>
    <?php endforeach; ?>
    </div>
</div>