<?php
namespace Module\Task\Domain\Cron;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;

class FinishTask {

    public function __construct() {
        $this->finish();
    }

    public function finish() {
        $min = TaskModel::where('every_time', '>', 0)->min('every_time');
        $items = TaskLogModel::with('task', 'day')
            ->where('status', TaskLogModel::STATUS_NONE)
            ->where('created_at', '<', time() - $min * 60)->get();
        if (empty($items)) {
            return;
        }
        foreach ($items as $item) {
            if (!$item->task || !$item->day) {
                $item->delete();
                continue;
            }
            $time = $item->getTimeAttribute();
            if ($item->task->every_time <= 0 ||
                $time < $item->task->every_time * 60) {
                continue;
            }
            $item->status = TaskLogModel::STATUS_FINISH;
            $item->save();

            $item->task->time_length += $time;
            $item->task->status = TaskModel::STATUS_NONE;
            $item->task->save();
            $item->day->success_amount ++;
            $item->day->status = TaskDayModel::STATUS_NONE;
            $item->day->save();
        }
    }
}