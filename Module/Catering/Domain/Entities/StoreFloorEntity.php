<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺楼层布局
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $map
 */
class StoreFloorEntity extends Entity {
    public static function tableName() {
        return 'eat_store_floor';
    }

    protected function rules() {
        return [
            'store_id' => 'required|int',
            'name' => 'required|string:0,20',
            'map' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'name' => 'Name',
            'map' => 'Map',
        ];
    }
}