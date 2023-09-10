<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $user_id
 * @property integer $link_id
 * @property integer $type
 * @property integer $created_at
 */
class UserRelationshipEntity extends Entity {
    public static function tableName(): string {
        return 'user_relationship';
    }

    protected  string $primaryKey = '';

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'link_id' => 'required|int',
            'type' => 'int:0,127',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'user_id' => 'User Id',
            'link_id' => 'Link Id',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}