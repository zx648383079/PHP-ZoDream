<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 订单
 * @package Module\Catering\Domain\Entities
 */
class OrderGoodsEntity extends Entity {
    public static function tableName() {
        return 'eat_order_goods';
    }
}