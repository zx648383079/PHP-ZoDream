<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $plant_id
 * @property integer $index
 * @property integer $grade
 * @property integer $plant_at
 * @property integer $harvest_at
 * @property integer $yield_count
 * @property integer $updated_at
 * @property integer $created_at
 */
class PlantingPlotsEntity extends Entity {
    public static function tableName(): string {
        return 'gm_planting_plots';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'plant_id' => 'int',
            'index' => 'int:0,127',
            'grade' => 'int:0,127',
            'plant_at' => 'int',
            'harvest_at' => 'int',
            'yield_count' => 'required|int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'plant_id' => 'Plant Id',
            'index' => 'Index',
            'grade' => 'Grade',
            'plant_at' => 'Plant At',
            'harvest_at' => 'Harvest At',
            'yield_count' => 'Yield Count',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}