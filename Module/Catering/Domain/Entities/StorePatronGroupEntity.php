<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺顾客
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $remark
 * @property integer $discount
 */
class StorePatronGroupEntity extends Entity {
    public static function tableName() {
        return 'eat_store_patron_group';
    }

    protected function rules() {
        return [
            'store_id' => 'required|int',
            'name' => 'required|string:0,20',
            'remark' => 'string:0,255',
            'discount' => 'int:0,127',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'name' => 'Name',
            'remark' => 'Remark',
            'discount' => 'Discount',
        ];
    }
}