<?php
namespace Module\Task\Domain\Entities;


use Domain\Entities\Entity;

/**
 * Class TaskShareEntity
 * @package Module\Task\Domain\Entities
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $share_type
 * @property string $share_rule
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskShareEntity extends Entity {
    public static function tableName() {
        return 'task_share';
    }

    public function rules() {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'share_type' => 'int:0,127',
            'share_rule' => 'string:0,20',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'task_id' => 'Task Id',
            'share_type' => 'Share Type',
            'share_rule' => 'Share Rule',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}