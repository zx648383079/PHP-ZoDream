<?php
namespace Module\Team\Domain\Entities;


use Domain\Entities\Entity;

/**
 * 
 * @property integer $id
 * @property integer $team_id
 * @property integer $user_id
 * @property string $name
 * @property integer $role_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class TeamUserEntity extends Entity {
    public static function tableName(): string {
        return 'team_users';
    }

    protected function rules(): array {
		return [
            'team_id' => 'required|int',
            'user_id' => 'required|int',
            'name' => 'string:0,50',
            'role_id' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'team_id' => 'Team Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'role_id' => 'Role Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}