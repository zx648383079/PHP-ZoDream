<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 用户购物车
 * @package Module\Catering\Domain\Entities
 */
class CartEntity extends Entity {
    public static function tableName() {
        return 'eat_cart';
    }
}