<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $area_id
 * @property string $name
 * @property string $description
 * @property integer $south_id
 * @property integer $east_id
 * @property integer $north_id
 * @property integer $west_id
 * @property integer $x
 * @property integer $y
 */
class MapEntity extends Entity {
    public static function tableName() {
        return 'gm_map';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'area_id' => 'int',
            'name' => 'required|string:0,255',
            'description' => 'string:0,255',
            'south_id' => 'int',
            'east_id' => 'int',
            'north_id' => 'int',
            'west_id' => 'int',
            'x' => 'int',
            'y' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'area_id' => 'Area Id',
            'name' => 'Name',
            'description' => 'Description',
            'south_id' => 'South Id',
            'east_id' => 'East Id',
            'north_id' => 'North Id',
            'west_id' => 'West Id',
            'x' => 'X',
            'y' => 'Y',
        ];
    }
}