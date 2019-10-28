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
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskDayModel extends Model {

    public static function tableName() {
        return 'task_day';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'today' => 'required|string:0,8',
            'amount' => 'int:0,9',
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
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function task() {
        return $this->hasOne(TaskModel::class, 'id', 'task_id');
    }

    public function start() {

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

}