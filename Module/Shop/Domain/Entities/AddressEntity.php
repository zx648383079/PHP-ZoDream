<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

class AddressEntity extends Entity {
    public static function tableName() {
        return 'shop_address';
    }

    public function rules() {
        return [
            'name' => 'required|string:0,30',
            'region_id' => 'required|int',
            'user_id' => 'required|int',
            'tel' => 'required|string:0,11',
            'address' => 'required|string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'region_id' => 'Region Id',
            'user_id' => 'User Id',
            'tel' => 'Tel',
            'address' => 'Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}