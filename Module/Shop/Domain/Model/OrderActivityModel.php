<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

class OrderActivityModel extends Model {
    public static function tableName() {
        return 'shop_order_activity';
    }
}