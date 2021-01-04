<?php
namespace Module\Task\Domain\Entities;

use Domain\Entities\Entity;
/**
 * Class TaskDayModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $child_id
 * @property string $today
 * @property integer $amount
 * @property integer $success_amount
 * @property integer $pause_amount
 * @property integer $failure_amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskLogEntity extends Entity {

    const STATUS_NONE = 0;
    const STATUS_PAUSE = 1; // 暂停
    const STATUS_FINISH = 2; // 完成
    const STATUS_FAILURE = 3; // 中断失败，未完成一个番茄时间

    public static function tableName() {
        return 'task_log';
    }

    public function rules() {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'day_id' => 'int',
            'child_id' => 'int',
            'status' => 'int:0,127',
            'outage_time' => 'int',
            'end_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'task_id' => 'Task Id',
            'child_id' => 'Day Id',
            'day_id' => 'Day Id',
            'status' => 'Status',
            'outage_time' => '中断时间',
            'end_at' => 'End At',
            'created_at' => 'Created At',
        ];
    }
}