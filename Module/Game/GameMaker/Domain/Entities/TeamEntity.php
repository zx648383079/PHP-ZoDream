<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property string $name
 * @property integer $created_at
 */
class TeamEntity extends Entity {
    public static function tableName() {
        return 'gm_team';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'name' => 'required|string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }
}