<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class CouponLogModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property integer $coupon_id
 * @property string $serial_number
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $used_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class CouponLogModel extends Model {

    public static function tableName() {
        return 'shop_coupon_log';
    }

    protected function rules() {
        return [
            'coupon_id' => 'required|int',
            'serial_number' => 'string:0,30',
            'user_id' => 'int',
            'order_id' => 'int',
            'used_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'coupon_id' => 'Coupon Id',
            'serial_number' => 'Serial Number',
            'user_id' => 'User Id',
            'order_id' => 'Order Id',
            'used_at' => 'Used At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}