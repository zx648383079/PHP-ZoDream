<?php
namespace Module\Task\Domain\Model;

use Domain\Model\Model;

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
 * @property integer $duration
 * @property integer $start_at
 * @property integer $time_length
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_at
 */
class TaskModel extends Model {

    const STATUS_NONE = 5;
    const STATUS_RUNNING = 9;
    const STATUS_PAUSE = 8;
    const STATUS_COMPLETE = 1;

    protected $append = ['start_at'];

    public static function tableName() {
        return 'task';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'parent_id' => 'int',
            'name' => 'required|string:0,100',
            'description' => 'string:0,255',
            'status' => 'int:0,127',
            'every_time' => 'int:0,9999',
            'space_time' => 'int:0,127',
            'duration' => 'int:0,127',
            'start_at' => 'int',
            'time_length' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'parent_id' => 'Parent Id',
            'name' => '名称',
            'description' => '说明',
            'status' => '状态',
            'every_time' => '单次时长（/分钟）',
            'space_time' => '单次间隔（/分钟）',
            'duration' => '每日次数',
            'start_at' => '开始时间',
            'time_length' => 'Time Length',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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

    public function makeEnd(TaskDayModel $day = null) {
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

    public function makeNewRun(TaskDayModel $day = null) {
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