<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $org_id
 * @property integer $user_id
 * @property string $contribution
 * @property integer $position
 */
class OrganizationMemberEntity extends Entity {
    public static function tableName(): string {
        return 'gm_organization_member';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'org_id' => 'required|int',
            'user_id' => 'required|int',
            'contribution' => 'string:0,20',
            'position' => 'int:0,127',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'org_id' => 'Org Id',
            'user_id' => 'User Id',
            'contribution' => 'Contribution',
            'position' => 'Position',
        ];
    }
}