<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class OrderLogModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $order_status
 * @property integer $pay_status
 * @property integer $shipping_status
 * @property string $data
 * @property create_at
 */
class OrderLogModel extends Model {
    public static function tableName() {
        return 'order_log';
    }
}