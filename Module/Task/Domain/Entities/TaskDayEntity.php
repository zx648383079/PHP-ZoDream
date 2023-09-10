<?php
namespace Module\Task\Domain\Entities;


use Domain\Entities\Entity;
/**
 * Class TaskDayModel
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
 */
class TaskDayEntity extends Entity {
    const STATUS_NONE = 5;
    const STATUS_RUNNING = 9;
    const STATUS_PAUSE = 8;

    public static function tableName(): string {
        return 'task_day';
    }

    public function rules(): array {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'today' => 'required',
            'amount' => 'int:0,9',
            'success_amount' => 'int:0,127',
            'pause_amount' => 'int:0,127',
            'failure_amount' => 'int:0,127',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
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
}