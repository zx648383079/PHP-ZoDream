<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺库存
 * @package Module\Catering\Domain\Entities
 */
class StoreStockEntity extends Entity {
    public static function tableName() {
        return 'eat_store_stock';
    }
}