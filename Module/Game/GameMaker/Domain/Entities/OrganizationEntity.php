<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $name
 * @property string $position_alias
 */
class OrganizationEntity extends Entity {
    public static function tableName(): string {
        return 'gm_organization';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'name' => 'required|int',
            'position_alias' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'position_alias' => 'Position Alias',
        ];
    }
}