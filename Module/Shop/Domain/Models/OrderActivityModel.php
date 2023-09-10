<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

class OrderActivityModel extends Model {
    public static function tableName(): string {
        return 'shop_order_activity';
    }
}