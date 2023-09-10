<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $team_id
 * @property integer $user_id
 * @property integer $created_at
 */
class TeamUserEntity extends Entity {
    public static function tableName(): string {
        return 'gm_team_user';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'team_id' => 'required|int',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'team_id' => 'Team Id',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
    }
}