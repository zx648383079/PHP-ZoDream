<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

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

    public static function tableName(): string {
        return 'shop_coupon_log';
    }

    public function rules(): array {
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

    protected function labels(): array {
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

    public function coupon() {
        return $this->hasOne(CouponModel::class, 'id', 'coupon_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}