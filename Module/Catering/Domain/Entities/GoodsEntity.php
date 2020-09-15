<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class GoodsEntity extends Entity {
    public static function tableName() {
        return 'eat_goods';
    }
}