<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class PurchaseOrderEntity extends Entity {
    public static function tableName() {
        return 'eat_purchase_order';
    }
}