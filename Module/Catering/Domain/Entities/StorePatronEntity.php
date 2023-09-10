<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺顾客
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $amount
 * @property string $name
 * @property string $remark
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $group_id
 */
class StorePatronEntity extends Entity {
    public static function tableName(): string {
        return 'eat_store_patron';
    }

    protected function rules(): array {
        return [
            'store_id' => 'required|int',
            'user_id' => 'required|int',
            'amount' => 'int',
            'name' => 'string:0,20',
            'remark' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
            'group_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'user_id' => 'User Id',
            'amount' => 'Amount',
            'name' => 'Name',
            'remark' => 'Remark',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'group_id' => 'Group Id',
        ];
    }
}