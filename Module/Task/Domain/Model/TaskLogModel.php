<?php
namespace Module\Task\Domain\Model;

use Module\Task\Domain\Entities\TaskLogEntity;

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
class TaskLogModel extends TaskLogEntity {

    protected array $append = ['time'];

    public function task() {
        return $this->hasOne(TaskModel::class, 'id', 'task_id');
    }

    public function day() {
        return $this->hasOne(TaskDayModel::class, 'id', 'day_id');
    }

    public function getTimeAttribute() {
        $end_at = $this->getAttributeSource('end_at');
        return ($end_at > 0 ? $end_at : time())
            - $this->getAttributeSource('created_at')
            - $this->getAttributeSource('outage_time');
    }

    /**
     * @param $task_id
     * @return static
     */
    public static function findRunning($task_id) {
        return self::where(function ($query) use ($task_id) {
                $query->where('task_id', $task_id)
                    ->orWhere('child_id', $task_id);
            })
            ->whereIn('status', [self::STATUS_NONE, self::STATUS_PAUSE])
            ->orderBy('id', 'desc')
            ->first();
    }
}