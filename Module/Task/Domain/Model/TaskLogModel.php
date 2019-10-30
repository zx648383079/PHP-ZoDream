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
 * @property integer $outage_time
 * @property integer $time
 * @property integer $created_at
 */
class TaskLogModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_PAUSE = 1; // 暂停
    const STATUS_FINISH = 2; // 完成
    const STATUS_FAILURE = 3; // 中断失败，未完成一个番茄时间

    public static function tableName() {
        return 'task_log';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'day_id' => 'int',
            'status' => 'int:0,9',
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
            'day_id' => 'Day Id',
            'status' => 'Status',
            'outage_time' => '中断时间',
            'end_at' => 'End At',
            'created_at' => 'Created At',
        ];
    }

    public function getTimeAttribute() {
        $end_at = $this->getAttributeSource('end_at');
        return ($end_at > 0 ? $end_at : time())
            - $this->getAttributeSource('created_at')
            - $this->getAttributeSource('outage_time');
    }

    public function getStartAtAttribute() {
        return $this->getAttributeSource('created_at') + $this->getAttributeSource('outage_time');
    }

    /**
     * @param $task_id
     * @return static
     */
    public static function findRunning($task_id) {
        return self::where('task_id', $task_id)
            ->whereIn('status', [self::STATUS_NONE, self::STATUS_PAUSE])
            ->first();
    }
}