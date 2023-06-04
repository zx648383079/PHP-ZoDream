<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property integer $parent_id
 */
class MapEntity extends Entity {
    public static function tableName() {
        return 'gm_map';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'parent_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'parent_id' => 'Parent Id',
        ];
    }
}