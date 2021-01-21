<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 采购单
 * @package Module\Catering\Domain\Entities
 */
class PurchaseOrderEntity extends Entity {
    public static function tableName() {
        return 'eat_purchase_order';
    }
}