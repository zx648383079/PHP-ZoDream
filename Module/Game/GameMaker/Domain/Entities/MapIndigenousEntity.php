<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $map_id
 * @property integer $indigenous_id
 * @property integer $status
 * @property integer $refresh_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class MapIndigenousEntity extends Entity {
    public static function tableName() {
        return 'gm_map_indigenous';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'map_id' => 'required|int',
            'indigenous_id' => 'required|int',
            'status' => 'int:0,127',
            'refresh_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'map_id' => 'Map Id',
            'indigenous_id' => 'Indigenous Id',
            'status' => 'Status',
            'refresh_at' => 'Refresh At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}