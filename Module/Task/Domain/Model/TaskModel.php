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
 * @property integer $time_length
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $start_at
 */
class TaskModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_RUNNING = 1;
    const STATUS_COMPETE = 2;

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
            'status' => 'int:0,9',
            'every_time' => 'int',
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
            'status' => 'Status',
            'every_time' => '单次时长（/分钟）',
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

    public function getStartAtAttribute() {
        return $this->getAttributeSource('updated_at');
    }

    public function makeEnd() {
        $log = TaskLogModel::findRunning($this->id);
        $log->end_at = time();
        $this->time_length += $log->getTimeAttribute();
        $this->status = self::STATUS_NONE;
        return $log->save() && $this->save();
    }

    public function makeNewRun() {
        $this->status = self::STATUS_RUNNING;
        $this->updated_at = time();
        $log = TaskLogModel::create([
           'user_id' => auth()->id(),
           'task_id' => $this->id,
           'created_at' => $this->start_at
        ]);
        if ($log && $this->save()) {
            return $log;
        }
        return false;
    }

}