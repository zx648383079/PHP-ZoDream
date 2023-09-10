<?php
namespace Module\Task\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $plan_type
 * @property integer $plan_time
 * @property integer $amount
 * @property integer $priority
 * @property integer $updated_at
 * @property integer $created_at
 */
class TaskPlanModel extends Model {

    public static function tableName(): string {
        return 'task_plan';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'plan_type' => 'int:0,127',
            'plan_time' => 'required|int',
            'amount' => 'int:0,127',
            'priority' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'task_id' => 'Task Id',
            'plan_type' => 'Plan Type',
            'plan_time' => 'Plan Time',
            'amount' => 'Amount',
            'priority' => 'Priority',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function task() {
        return $this->hasOne(TaskModel::class, 'id', 'task_id');
    }

}