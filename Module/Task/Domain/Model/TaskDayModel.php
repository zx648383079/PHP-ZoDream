<?php
namespace Module\Task\Domain\Model;

use Module\Task\Domain\Entities\TaskDayEntity;
use Zodream\Helpers\Time;

/**
 * Class TaskDayModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property string $today
 * @property integer $amount
 * @property integer $success_amount
 * @property integer $pause_amount
 * @property integer $failure_amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property TaskModel $task
 */
class TaskDayModel extends TaskDayEntity {

    protected $append = ['task', 'log'];

    public function scopeMonth($query, $time) {
        return $this->scopeTime($query, date('Y-m-01', $time),
            date('Y-m-t', $time));
    }

    public function scopeWeek($query, $now) {
        $time = ('1' == date('w', $now)) ? strtotime('Monday', $now) : strtotime('last Monday', $now);
        return $this->scopeTime($query, date('Y-m-d', $time), date('Y-m-d', strtotime('Sunday', $now)));
    }

    public function scopeTime($query, $start_at, $end_at) {
        return $query->where('today', '>=', $start_at)->where('today', '<=', $end_at);
    }

    public function task() {
        return $this->hasOne(TaskModel::class, 'id', 'task_id');
    }

    public function logs() {
        return $this->hasMany(TaskLogModel::class, 'day_id', 'id');
    }

    public function getLogAttribute() {
        return TaskLogModel::findRunning($this->task_id);
    }

    public function getWeekAttribute() {
        return Time::weekFormat($this->today);
    }

    public function start() {
        if ($this->amount < 1) {
            $this->setError('amount', '次数已用完，请重新添加');
            return false;
        }
        if ($this->task->status == TaskModel::STATUS_COMPLETE) {
            $this->setError('task_id', '此任务已完成');
            return false;
        }
        if (!$this->task->makeNewRun($this)) {
            $this->setError('task_id', '启动失败');
            return false;
        }
        $this->status = self::STATUS_RUNNING;
        return $this->save();
    }

    public function stop() {
        return $this->task->makeEnd($this);
    }

    public function check() {
        if ($this->status !== TaskModel::STATUS_RUNNING) {
            $this->setError('status', '此任务不在运行');
            return false;
        }
        if ($this->task->every_time <= 0) {
            return true;
        }
        $log = TaskLogModel::findRunning($this->task_id);
        if (empty($log)) {
            $this->makeEnd($this->task, 0);
            $this->setError('status', '此任务不在运行');
            return false;
        }
        $log->end_at = time();
        if ($log->getTimeAttribute() >= $this->task->every_time * 60) {
            $this->stop();
        }
        return true;
    }

    public function makeEnd(TaskModel $task, $time) {
        if ($time > 0 &&
            ($task->every_time <= 0 || $task->every_time * 60 <= $time)) {
            $this->amount --;
        }
        $this->status = self::STATUS_NONE;
        $this->save();
    }

    public static function add(TaskModel $task, $amount = 1) {
        $day = date('Y-m-d');
        $model = static::where('task_id', $task->id)
            ->where('today', $day)->first();
        if (!empty($model)) {
            $model->amount += $amount;
            return $model->save();
        }
        return static::create([
           'user_id' => $task->user_id,
           'task_id' => $task->id,
           'today' => $day,
           'amount' => $amount,
           'status' => self::STATUS_NONE
        ]);
    }

    public static function makeEndTask(TaskModel $task, $time) {
        $model = self::where('task_id', $task->id)
            ->where('amount', '>', 0)
            ->where('status', self::STATUS_RUNNING)
            ->first();
        if (empty($model)) {
            return;
        }
        $model->makeEnd($task, $time);
    }

}