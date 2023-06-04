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
 * @property integer $updated_at
 * @property integer $created_at
 */
class IndigenousEntity extends Entity {
    public static function tableName() {
        return 'gm_indigenous';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'area_id' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'required|string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'area_id' => 'Area Id',
            'name' => 'Name',
            'description' => 'Description',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}