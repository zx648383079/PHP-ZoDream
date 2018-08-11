<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class PayLogModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $payment_id
 * @property integer $order_id
 * @property float $amount
 * @property integer $user_id
 * @property integer $update_at
 * @property integer $create_at
 */
class PayLogModel extends Model {
    public static function tableName() {
        return 'shop_pay_log';
    }
}