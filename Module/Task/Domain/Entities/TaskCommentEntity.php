<?php
namespace Module\Task\Domain\Entities;


use Domain\Entities\Entity;

/**
 * Class TaskCommentEntity
 * @package Module\Task\Domain\Entities
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $log_id
 * @property string $content
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskCommentEntity extends Entity {
    public static function tableName(): string {
        return 'task_comment';
    }

    public function rules(): array {
        return [
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'log_id' => 'int',
            'content' => 'required|string:0,255',
            'type' => 'int:0,127',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'task_id' => 'Task Id',
            'log_id' => 'Log Id',
            'content' => 'Content',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}