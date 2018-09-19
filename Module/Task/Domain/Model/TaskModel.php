<?php
namespace Module\Task\Domain\Model;

use Domain\Model\Model;

/**
 * Class TaskModel
 * @package Module\Task\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $time_length
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_RUNNING = 1;
    const STATUS_COMPETE = 2;

    public static function tableName() {
        return 'task';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,100',
            'description' => 'required|string:0,255',
            'status' => 'int:0,9',
            'time_length' => 'required|string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
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

}