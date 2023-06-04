<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $type
 * @property string $name
 * @property string $description
 */
class ItemEntity extends Entity {
    public static function tableName() {
        return 'gm_item';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'type' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'type' => 'Type',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }
}