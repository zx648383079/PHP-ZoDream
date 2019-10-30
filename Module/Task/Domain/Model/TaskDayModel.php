<?php
namespace Module\Task\Domain\Model;

use Domain\Model\Model;

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
class TaskDayModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_RUNNING = 1;
    const STATUS_PAUSE = 2;

    protected $append = ['task', 'log'];

    public static function tableName() {
        return 'task_day';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'today' => 'required|string:0,8',
            'amount' => 'int:0,9',
            'success_amount' => 'int:0,127',
            'pause_amount' => 'int:0,127',
            'failure_amount' => 'int:0,127',
            'status' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'task_id' => '任务',
            'today' => 'Today',
            'amount' => '执行次数',
            'success_amount' => '成功次数',
            'pause_amount' => '暂停次数',
            'failure_amount' => '中断次数',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function task() {
        return $this->hasOne(TaskModel::class, 'id', 'task_id');
    }

    public function getLogAttribute() {
        return TaskLogModel::findRunning($this->task_id);
    }

    public function start() {
        if ($this->amount < 1) {
            $this->setError('amount', '次数已用完，请重新添加');
            return false;
        }
        if ($this->task->status == TaskModel::STATUS_COMPETE) {
            $this->setError('task_id', '此任务已完成');
            return false;
        }
        if (!$this->task->makeNewRun($this)) {
            $this->setError('task_id', '启动失败');
            return false;
        }
        $this->status = TaskModel::STATUS_RUNNING;
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
        $this->status = TaskModel::STATUS_NONE;
        $this->save();
    }

    public static function add(TaskModel $task, $amount = 1) {
        $day = date('Ymd');
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
           'status' => 0
        ]);
    }

    public static function makeEndTask(TaskModel $task, $time) {
        $model = self::where('task_id', $task->id)
            ->where('amount', '>', 0)
            ->where('status', TaskModel::STATUS_RUNNING)
            ->first();
        if (empty($model)) {
            return;
        }
        $model->makeEnd($task, $time);
    }

}