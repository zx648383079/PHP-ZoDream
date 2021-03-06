<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Task\Domain\Model\TaskDayModel;
/** @var $this View */
?>
<?php foreach($model_list as $item):?>
    <div class="task-item<?=$item->status == TaskDayModel::STATUS_RUNNING ? ' active' : ''?>" data-id="<?=$item->id?>" data-time="<?=$item->task->every_time?>">
        <div class="name">
            <?=$item->task->name?>
            <?php if($item->amount > 1):?>
            <span class="amount"><?=$item->amount?></span>
            <?php endif;?>
        </div>
        <div class="time" data-start="<?= $item->status == TaskDayModel::STATUS_RUNNING ? $item->log->start_at :  0?>"><?=$item->task->time_length?></div>
        <div class="actions">
            <a href="javascript:;" class="fa fa-pause-circle" title="停止计时">
            </a>
            <a href="javascript:;" class="fa fa-play-circle" title="开始计时">
            </a>
            <a href="javascript:;" class="fa fa-stop-circle" title="终止任务">
            </a>
        </div>
        <div class="desc"><?=$item->task->description?></div>
    </div>
<?php endforeach; ?>
<?php if($last_log):?>
    <div class="last-task-time">
        上次任务结束时间：
        <span class="time" data-time="<?=$this->time($last_log->end_at)?>"><?=$this->time($last_log->end_at)?></span>
    </div>
<?php endif;?>