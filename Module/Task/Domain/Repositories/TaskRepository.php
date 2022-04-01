<?php
declare(strict_types=1);
namespace Module\Task\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Exception;

class TaskRepository {

    public static function getList(string $keywords = '', int $status = 0, int $parent_id = 0) {
        return TaskModel::where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })
            ->when($status > 0, function ($query) use ($status) {
                if ($status > 1) {
                    return $query->where('status', TaskModel::STATUS_COMPLETE);
                }
                return $query->where('status', '>=', 5);
            })
            ->where('parent_id', $parent_id)
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')->page();
    }

    public static function detail($id) {
        $model = TaskModel::findWithAuth($id);
        if (empty($model)) {
            throw new Exception('任务不存在');
        }
        $model->children;
        return $model;
    }

    public static function save(array $data, int $id = 0, int $status = -1) {
        $model = $id > 0 ? TaskModel::findWithAuth($id) : new TaskModel();
        if (empty($model)) {
            throw new Exception('任务不存在');
        }
        $model->set($data)->user_id = auth()->id();
        if ($status !== -1 && $model->status === TaskModel::STATUS_COMPLETE
            && $status != TaskModel::STATUS_COMPLETE) {
            $model->status = 0;
        }
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id, bool $stop = false) {
        $model = TaskModel::findWithAuth($id);
        if (empty($model)) {
            throw new Exception('任务不存在');
        }
        try {
            static::stopTask($id);
        } catch (\Exception $ex) {}
        if (!$stop) {
            $model->delete();
        }
    }

    public static function getActiveTask() {
        return TaskModel::where('user_id', auth()->id())
            ->orderBy('status', 'desc')
            ->orderBy('id', 'asc')
            ->where('status', '>=', TaskModel::STATUS_NONE)->get();
    }

    /**
     * 开始
     * @param $id
     * @param int $child_id
     * @return TaskDayModel
     * @throws Exception
     */
    public static function start(int $id, int $child_id = 0) {
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
            $log_list = TaskLogModel::where('task_id', $day->task_id)
                ->whereIn('status', [TaskLogModel::STATUS_NONE, TaskLogModel::STATUS_PAUSE])
                ->orderBy('id', 'desc')
                ->get();
            $log = null;
            foreach ($log_list as $item) {
                if ($item->day_id === $day->id) {
                    $log = $item;
                    continue;
                }
                self::stopDayLog($item);
            }
            if (!empty($log)) {
                $log->outage_time += time() - $log->end_at;
                $log->end_at = 0;
                $log->status = TaskLogModel::STATUS_NONE;
                $log->save();
                $day->status = TaskDayModel::STATUS_RUNNING;
                $day->save();
                $day->task->status = TaskModel::STATUS_RUNNING;
                $day->task->save();
                if ($log->child_id > 0) {
                    TaskModel::where('id', $log->child_id)
                        ->update([
                            'status' => TaskModel::STATUS_RUNNING
                        ]);
                }
                return $day;
            }
        }
        if ($day->amount < 1) {
            throw new Exception('今日任务已完成');
        }
        $log = TaskLogModel::create([
            'user_id' => auth()->id(),
            'task_id' => $day->task_id,
            'child_id' => $child_id,
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
        if ($log->child_id > 0) {
            TaskModel::where('id', $log->child_id)
                ->update([
                    'status' => TaskModel::STATUS_RUNNING
                ]);
        }
        return $day;
    }

    private static function stopDayLog(TaskLogModel $log) {
        $day = TaskDayModel::findWithAuth($log->day_id);
        if ($log->status != TaskLogModel::STATUS_PAUSE) {
            $log->end_at = time();
        }
        $time = $log->getTimeAttribute();
        if (empty($day)) {
            $log->status = TaskLogModel::STATUS_FAILURE;
            $log->save();
            return;
        }
        $log->status =
            $day->task->every_time <= 0 ||
            $time >= $day->task->every_time * 60
                ? TaskLogModel::STATUS_FINISH : TaskLogModel::STATUS_FAILURE;
        $log->save();
        if ($day->status == TaskDayModel::STATUS_NONE) {
            return;
        }
        self::addDayTime($day, $log, $time);
    }

    private static function addDayTime(TaskDayModel $day, TaskLogModel $log, int $time) {
        $day->task->time_length += $time;
        $day->task->status = TaskModel::STATUS_NONE;
        $day->task->save();
        if ($log->child_id > 0) {
            TaskModel::query()->where('id', $log->child_id)
                ->update([
                    'time_length=time_length+'.$time,
                    'status' => TaskModel::STATUS_NONE
                ]);
        }
        if ($log->status === TaskLogModel::STATUS_FINISH) {
            $day->success_amount ++;
            if ($day->amount > 0) {
                $day->amount --;
            }
        } else {
            $day->failure_amount ++;
        }
        $day->status = TaskDayModel::STATUS_NONE;
        return $day->save();
    }

    /**
     * 停止
     * @param $id
     * @return TaskDayModel
     * @throws Exception
     */
    public static function stop(int $id) {
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
        self::addDayTime($day, $log, $time);
        return $day;
    }

    /**
     * 暂停
     * @param $id
     * @return TaskDayModel
     * @throws Exception
     */
    public static function pause(int $id) {
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
        if ($log->child_id > 0) {
            TaskModel::where('id', $log->child_id)
                ->update([
                    'status' => TaskModel::STATUS_PAUSE
                ]);
        }
        return $day;
    }

    /**
     * 验证
     * @param $id
     * @return bool|TaskDayModel
     * @throws Exception
     */
    public static function check(int $id) {
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
        if ($log->child_id > 0) {
            TaskModel::where('id', $log->child_id)
                ->update([
                    'time_length=time_length+'.$time,
                    'status' => TaskModel::STATUS_NONE
                ]);
        }
        return $day;
    }

    /**
     * 完成任务
     * @param $id
     * @return TaskModel
     * @throws Exception
     */
    public static function stopTask(int $id) {
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
        $task->status = TaskModel::STATUS_NONE;
        $task->save();
        if ($task->parent_id > 0) {
            TaskModel::query()->where('id', $task->parent_id)
                ->update([
                    'time_length=time_length+'.$time,
                    'status' => TaskModel::STATUS_NONE
                ]);
        }
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

    public static function restTip(TaskDayModel $model) {
        // 记录今天完成的任务次数，每4轮多休息
        $count = TaskLogModel::where('created_at', '>',
            strtotime(date('Y-m-d 00:00:00')))
            ->where('status', TaskLogModel::STATUS_FINISH)
            ->where('user_id', auth()->id())
            ->count();
        $tip = '本轮任务完成，请休息3-5分钟';
        if ($count % 4 === 0) {
            return '本轮任务完成，请休息20-25分钟';
        }
        return $tip;
    }
}