<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺楼层座位
 * @property integer $id
 * @property integer $store_id
 * @property integer $floor_id
 * @property string $name
 * @property integer $user_id
 */
class StorePlaceEntity extends Entity {
    public static function tableName() {
        return 'eat_store_place';
    }

    protected function rules() {
        return [
            'store_id' => 'required|int',
            'floor_id' => 'required|int',
            'name' => 'required|string:0,20',
            'user_id' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'floor_id' => 'Floor Id',
            'name' => 'Name',
            'user_id' => 'User Id',
        ];
    }

}