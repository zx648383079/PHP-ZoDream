<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺楼层布局
 * @package Module\Catering\Domain\Entities
 */
class StoreFloorEntity extends Entity {
    public static function tableName() {
        return 'eat_store_floor';
    }
}