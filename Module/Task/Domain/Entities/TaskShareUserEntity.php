<?php
namespace Module\Task\Domain\Entities;


use Domain\Entities\Entity;

/**
 * Class TaskShareUserEntity
 * @package Module\Task\Domain\Entities
 * @property integer $id
 * @property integer $user_id
 * @property integer $share_id
 * @property integer $created_at
 */
class TaskShareUserEntity extends Entity {
    public static function tableName(): string {
        return 'task_share_user';
    }

    public function rules(): array {
        return [
            'user_id' => 'required|int',
            'share_id' => 'required|int',
            'created_at' => 'int',
            'deleted_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'share_id' => 'Share Id',
            'created_at' => 'Created At',
            'deleted_at' => 'deleted_at',
        ];
    }
}