<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class OrderEntity extends Entity {
    public static function tableName() {
        return 'eat_order';
    }
}