<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property int $grade
 * @property int $exp
 */
class RuleGradeEntity extends Entity {
    public static function tableName() {
        return 'gm_rule_character_grade';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'grade' => 'required|int',
            'name' => 'string:0,255',
            'exp' => 'string:0,20',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'exp' => 'Exp',
        ];
    }
}