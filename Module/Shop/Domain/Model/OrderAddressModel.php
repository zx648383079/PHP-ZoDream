<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class OrderGoodsModel
 * @package Domain\Model\Shopping
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $user_id
 */
class OrderAddressModel extends Model {
    public static function tableName() {
        return 'shop_order_address';
    }
}