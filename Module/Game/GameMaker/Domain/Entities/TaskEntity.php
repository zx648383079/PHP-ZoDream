<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $title
 * @property string $description
 * @property string $gift
 * @property string $before
 * @property integer $updated_at
 * @property integer $created_at
 */
class TaskEntity extends Entity {
    public static function tableName() {
        return 'gm_task';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'title' => 'required|string:0,255',
            'description' => 'required|string:0,255',
            'gift' => 'required|string:0,255',
            'before' => 'required|string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'title' => 'Title',
            'description' => 'Description',
            'gift' => 'Gift',
            'before' => 'Before',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}