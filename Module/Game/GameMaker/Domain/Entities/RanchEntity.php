<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $animal_id
 * @property integer $farm_at
 * @property integer $harvest_at
 * @property integer $yield_count
 * @property integer $updated_at
 * @property integer $created_at
 */
class RanchEntity extends Entity {
    public static function tableName() {
        return 'gm_ranch';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'animal_id' => 'int',
            'farm_at' => 'int',
            'harvest_at' => 'int',
            'yield_count' => 'required|int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'animal_id' => 'Animal Id',
            'farm_at' => 'Farm At',
            'harvest_at' => 'Harvest At',
            'yield_count' => 'Yield Count',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}