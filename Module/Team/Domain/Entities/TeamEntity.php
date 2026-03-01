<?php
namespace Module\Team\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property string $logo
 * @property string $description
 * @property integer $user_id
 * @property integer $open_type
 * @property string $open_rule
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class TeamEntity extends Entity {
    public static function tableName(): string {
        return 'team';
    }

    protected function rules(): array {
		return [
            'type' => 'int:0,127',
            'name' => 'required|string:0,50',
            'logo' => 'required|string:0,100',
            'description' => 'string:0,255',
            'user_id' => 'required|int',
            'open_type' => 'int:0,127',
            'open_rule' => 'string:0,20',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'type' => 'Type',
            'name' => 'Name',
            'logo' => 'Logo',
            'description' => 'Description',
            'user_id' => 'User Id',
            'open_type' => 'Open Type',
            'open_rule' => 'Open Rule',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}