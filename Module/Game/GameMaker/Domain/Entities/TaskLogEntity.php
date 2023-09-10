<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $task_id
 * @property string $step
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class TaskLogEntity extends Entity {
    public static function tableName(): string {
        return 'gm_task_log';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'task_id' => 'required|int',
            'step' => 'string:0,255',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'task_id' => 'Task Id',
            'step' => 'Step',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}