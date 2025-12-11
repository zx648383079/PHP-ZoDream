<?php
namespace Module\Task\Domain\Model;

use Module\Task\Domain\Entities\TaskEntity;
use Zodream\Helpers\Time;

/**
 * Class TaskModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $parent_id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $every_time
 * @property integer $space_time
 * @property integer $per_time
 * @property integer $start_at
 * @property integer $time_length
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_at
 */
class TaskModel extends TaskEntity {

    protected array $append = ['last_at'];

    public function children() {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function getStartAtAttribute() {
        $time = $this->getAttributeSource('start_at');
        return $time < 1 ? '' : Time::format($time, 'Y-m-d');
    }

    public function setStartAtAttribute($value) {
        $this->__attributes['start_at'] = empty($value) || is_numeric($value) ? intval($value)
            : strtotime($value);
    }

    public function getTimeAttribute() {
        if ($this->status != self::STATUS_RUNNING) {
            return $this->time_length;
        }
        $log = TaskLogModel::findRunning($this->id);
        return $this->time_length + $log->time;
    }

    public function getLastAtAttribute() {
        return $this->getAttributeSource('updated_at');
    }

    public function makeEnd(TaskDayModel|null $day = null) {
        $log = TaskLogModel::findRunning($this->id);
        $time = 0;
        if (!empty($log)) {
            $log->end_at = time();
            $time = $log->getTimeAttribute();
            $log->status =
                $this->every_time <= 0 || $time >= $this->every_time * 60
                    ? TaskLogModel::STATUS_FINISH : TaskLogModel::STATUS_FAILURE;
            $log->save();
        }
        $this->time_length += $time;
        $this->status = self::STATUS_NONE;
        if ($day) {
            $day->makeEnd($this, $time);
        } else {
            TaskDayModel::makeEndTask($this, $time);
        }
        return $this->save();
    }

    public function makeNewRun(TaskDayModel|null $day = null) {
        $this->status = self::STATUS_RUNNING;
        $this->updated_at = time();
        $log = TaskLogModel::create([
           'user_id' => auth()->id(),
           'task_id' => $this->id,
           'created_at' => $this->start_at,
            'day_id' => $day ? $day->id : 0
        ]);
        if ($log && $this->save()) {
            return $log;
        }
        return false;
    }

}