<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

class OrderCouponModel extends Model {
    public static function tableName() {
        return 'shop_order_coupon';
    }
}