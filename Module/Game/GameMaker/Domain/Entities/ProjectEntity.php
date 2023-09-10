<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $logo
 * @property string $description
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class ProjectEntity extends Entity {
    public static function tableName(): string {
        return 'gm_project';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,255',
            'logo' => 'string:0,255',
            'description' => 'string:0,255',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'logo' => 'Logo',
            'description' => 'Description',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}