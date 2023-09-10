<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺员工
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $updated_at
 * @property integer $created_at
 */
class StoreStaffEntity extends Entity {
    public static function tableName(): string {
        return 'eat_store_staff';
    }

    protected function rules(): array {
        return [
            'store_id' => 'required|int',
            'user_id' => 'required|int',
            'role_id' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'user_id' => 'User Id',
            'role_id' => 'Role Id',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}