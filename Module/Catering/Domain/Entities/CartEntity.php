<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class CartEntity extends Entity {
    public static function tableName() {
        return 'eat_cart';
    }
}