<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 */
class SkillEntity extends Entity {
    public static function tableName() {
        return 'gm_skill';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }
}