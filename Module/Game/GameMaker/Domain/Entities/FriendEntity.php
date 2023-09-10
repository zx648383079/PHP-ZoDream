<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $belong_id
 * @property integer $type
 * @property integer $created_at
 */
class FriendEntity extends Entity {
    public static function tableName(): string {
        return 'gm_friend';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'belong_id' => 'required|int',
            'type' => 'int:0,127',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'belong_id' => 'Belong Id',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}