<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 * @property integer $rule_type
 * @property string $rule
 * @property integer $updated_at
 * @property integer $created_at
 */
class FinancialEntity extends Entity {
    public static function tableName() {
        return 'gm_financial';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'string:0,255',
            'rule_type' => 'int:0,127',
            'rule' => 'string:0,1',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'description' => 'Description',
            'rule_type' => 'Rule Type',
            'rule' => 'Rule',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}