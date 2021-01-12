<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class StoreStockEntity extends Entity {
    public static function tableName() {
        return 'eat_store_stock';
    }
}