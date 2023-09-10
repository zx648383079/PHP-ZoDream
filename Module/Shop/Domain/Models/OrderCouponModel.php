<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

class OrderCouponModel extends Model {
    public static function tableName(): string {
        return 'shop_order_coupon';
    }
}