<?php
namespace Module\Task\Domain\Model;

use Domain\Model\Model;

/**
 * Class TaskLogModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $day_id
 * @property integer $status
 * @property integer $end_at
 * @property integer $time
 * @property integer $created_at
 */
class TaskLogModel extends Model {

    public static function tableName() {
        return 'task_log';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'day_id' => 'int',
            'status' => 'int:0,9',
            'end_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'task_id' => 'Task Id',
            'day_id' => 'Day Id',
            'status' => 'Status',
            'end_at' => 'End At',
            'created_at' => 'Created At',
        ];
    }

    public function getTimeAttribute() {
        $end_at = $this->getAttributeSource('end_at');
        return ($end_at > 0 ? $end_at : time()) - $this->getAttributeSource('created_at');
    }

    /**
     * @param $task_id
     * @return static
     */
    public static function findRunning($task_id) {
        return self::where('task_id', $task_id)
            ->where('end_at', 0)->first();
    }
}