<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $index
 * @property integer $grade
 * @property string $grade_alias
 * @property integer $price
 * @property integer $time_scale
 * @property integer $yield_scale
 * @property integer $updated_at
 * @property integer $created_at
 */
class RulePlantingPlotsEntity extends Entity {
    public static function tableName(): string {
        return 'gm_rule_planting';
    }
    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'index' => 'int:0,127',
            'grade' => 'int:0,127',
            'grade_alias' => 'string:0,20',
            'price' => 'int',
            'time_scale' => 'required|int',
            'yield_scale' => 'required|int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'index' => 'Index',
            'grade' => 'Grade',
            'grade_alias' => 'Grade Alias',
            'price' => 'Price',
            'time_scale' => 'Time Scale',
            'yield_scale' => 'Yield Scale',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}