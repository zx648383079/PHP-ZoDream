<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $map_id
 * @property string $name
 * @property string $description
 * @property integer $south_id
 * @property integer $east_id
 * @property integer $north_id
 * @property integer $west_id
 */
class MapAreaEntity extends Entity {
    public static function tableName() {
        return 'gm_map_area';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'map_id' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'required|string:0,255',
            'south_id' => 'int',
            'east_id' => 'int',
            'north_id' => 'int',
            'west_id' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'map_id' => 'Map Id',
            'name' => 'Name',
            'description' => 'Description',
            'south_id' => 'South Id',
            'east_id' => 'East Id',
            'north_id' => 'North Id',
            'west_id' => 'West Id',
        ];
    }
}