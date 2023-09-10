<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $task_id
 * @property integer $npc_id
 * @property string $text
 * @property string $option
 * @property integer $updated_at
 * @property integer $created_at
 */
class TaskItemEntity extends Entity {
    public static function tableName(): string {
        return 'gm_task_item';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'task_id' => 'required|int',
            'npc_id' => 'required|int',
            'text' => 'required|string:0,255',
            'option' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'task_id' => 'Task Id',
            'npc_id' => 'Npc Id',
            'text' => 'Text',
            'option' => 'Option',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}