<?php
namespace Module\Task\Domain\Repositories;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Exception;

class TaskRepository {

    public static function getActiveTask() {
        return TaskModel::where('user_id', auth()->id())
            ->orderBy('status', 'desc')
            ->orderBy('id', 'asc')
            ->where('status', '>', TaskModel::STATUS_NONE)->get();
    }

    /**
     * 开始
     * @param $id
     * @return TaskDayModel
     * @throws Exception
     */
    public static function start($id) {
        $other = TaskDayModel::where('user_id', auth()->id())
            ->where('id', '<>', $id)->where('status', '>', TaskDayModel::STATUS_NONE)
            ->pluck('id');
        if (!empty($other)) {
            foreach ($other as $item) {
                self::stop($item);
            }
        }
        $day = TaskDayModel::findWithAuth($id);
        if (empty($day)) {
            throw new Exception('任务不存在');
        }
        if ($day->status == TaskDayModel::STATUS_RUNNING) {
            return $day;
        }
        if ($day->status == TaskDayModel::STATUS_PAUSE) {
            $log = TaskLogModel::findRunning($day->task_id);
            if (!empty($log)) {
                $log->outage_time += time() - $log->end_at;
                $log->end_at = 0;
                $log->status = TaskLogModel::STATUS_NONE;
                $log->save();
                $day->status = TaskDayModel::STATUS_RUNNING;
                $day->save();
                $day->task->status = TaskModel::STATUS_RUNNING;
                $day->task->save();
                return $day;
            }
        }
        if ($day->amount < 1) {
            throw new Exception('今日任务已完成');
        }
        $log = TaskLogModel::create([
            'user_id' => auth()->id(),
            'task_id' => $day->task_id,
            'created_at' => time(),
            'day_id' => $day->id
        ]);
        if (!$log) {
            throw new Exception('启动失败');
        }
        $day->status = TaskDayModel::STATUS_RUNNING;
        $day->save();
        $day->task->status = TaskModel::STATUS_RUNNING;
        $day->task->save();
        return $day;
    }

    /**
     * 停止
     * @param $id
     * @return TaskDayModel
     * @throws Exception
     */
    public static function stop($id) {
        $day = TaskDayModel::findWithAuth($id);
        if (empty($day)) {
            throw new Exception('任务不存在');
        }
        if ($day->status == TaskDayModel::STATUS_NONE) {
            return $day;
        }
        $log = TaskLogModel::findRunning($day->task_id);
        if (!$log) {
            throw new Exception('停止失败');
        }
        if ($log->status != TaskLogModel::STATUS_PAUSE) {
            $log->end_at = time();
        }
        $time = $log->getTimeAttribute();
        $log->status =
            $day->task->every_time <= 0 ||
            $time >= $day->task->every_time * 60
                ? TaskLogModel::STATUS_FINISH : TaskLogModel::STATUS_FAILURE;
        $log->save();
        $day->task->time_length += $time;
        $day->task->status = TaskModel::STATUS_NONE;
        $day->task->save();
        if ($log->status === TaskLogModel::STATUS_FINISH) {
            $day->success_amount ++;
        } else {
            $day->failure_amount ++;
        }
        $day->status = TaskDayModel::STATUS_NONE;
        $day->save();
        return $day;
    }

    /**
     * 暂停
     * @param $id
     * @return TaskDayModel
     * @throws Exception
     */
    public static function pause($id) {
        $day = TaskDayModel::findWithAuth($id);
        if (empty($day)) {
            throw new Exception('任务不存在');
        }
        if ($day->status == TaskDayModel::STATUS_PAUSE) {
            return $day;
        }
        $log = TaskLogModel::findRunning($day->task_id);
        if (!$log) {
            throw new Exception('暂停失败');
        }
        $log->end_at = time();
        $log->status = TaskLogModel::STATUS_PAUSE;
        $log->save();
        $day->pause_amount ++;
        $day->status = TaskDayModel::STATUS_PAUSE;
        $day->save();
        $day->task->status = TaskModel::STATUS_PAUSE;
        $day->task->save();
        return $day;
    }

    /**
     * 验证
     * @param $id
     * @return bool|TaskDayModel
     * @throws Exception
     */
    public static function check($id) {
        $day = TaskDayModel::findWithAuth($id);
        if (empty($day)) {
            throw new Exception('任务不存在');
        }
        if ($day->status != TaskDayModel::STATUS_RUNNING
            || $day->task->every_time <= 0) {
            return false;
        }
        $log = TaskLogModel::findRunning($day->task_id);
        if (!$log) {
            throw new Exception('任务记录有问题');
        }
        $log->end_at = time();
        $time = $log->getTimeAttribute();
        if ($time < $day->task->every_time * 60) {
            return false;
        }
        $log->status = TaskLogModel::STATUS_FINISH;
        $log->save();
        $day->task->time_length += $time;
        $day->task->status = TaskModel::STATUS_NONE;
        $day->task->save();
        $day->success_amount ++;
        $day->amount --;
        $day->status = TaskDayModel::STATUS_NONE;
        $day->save();
        return $day;
    }

    /**
     * 完成任务
     * @param $id
     * @return TaskModel
     * @throws Exception
     */
    public static function stopTask($id) {
        $task = TaskModel::findWithAuth($id);
        if (empty($task) || $task->status == TaskModel::STATUS_COMPLETE) {
            throw new Exception('任务不存在');
        }
        if ($task->status !== TaskModel::STATUS_NONE) {
            self::stopLog($task);
            self::cancelDay($task);
        }
        $task->status = TaskModel::STATUS_COMPLETE;
        $task->save();
        return $task;
    }

    private static function stopLog(TaskModel $task) {
        $log = TaskLogModel::findRunning($task->id);
        if (empty($log)) {
            return;
        }
        if ($log->status != TaskLogModel::STATUS_PAUSE) {
            $log->end_at = time();
        }
        $time = $log->getTimeAttribute();
        $log->status =
            $task->every_time <= 0 || $time >= $task->every_time * 60
                ? TaskLogModel::STATUS_FINISH : TaskLogModel::STATUS_FAILURE;
        $log->save();
        $task->time_length += $time;
        if ($log->day_id < 1) {
            return;
        }
        $day = TaskDayModel::find($log->day_id);
        if (empty($day)) {
            return;
        }
        if ($log->status === TaskLogModel::STATUS_FINISH) {
            $day->success_amount ++;
        } else {
            $day->failure_amount ++;
        }
        $day->amount = 0;
        $day->status = TaskDayModel::STATUS_NONE;
        $day->save();
    }

    private static function cancelDay(TaskModel $task) {
        TaskDayModel::where('task_id', $task->id)
            ->where('today', '>', date('Ymd'))->delete();
    }
}