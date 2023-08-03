<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺顾客
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $cat_id
 * @property integer $amount
 * @property string $name
 * @property string $remark
 * @property integer $discount
 * @property integer $updated_at
 * @property integer $created_at
 */
class StorePatronEntity extends Entity {
    public static function tableName() {
        return 'eat_store_patron';
    }

    protected function rules() {
        return [
            'store_id' => 'required|int',
            'user_id' => 'required|int',
            'cat_id' => 'required|int',
            'amount' => 'int',
            'name' => 'string:0,20',
            'remark' => 'string:0,255',
            'discount' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'user_id' => 'User Id',
            'cat_id' => 'Cat Id',
            'amount' => 'Amount',
            'name' => 'Name',
            'remark' => 'Remark',
            'discount' => 'Discount',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}