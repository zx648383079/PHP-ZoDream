<?php
namespace Module\Shop\Domain\Models;


use Module\Shop\Domain\Entities\CouponEntity;

/**
 * Class CouponModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property integer $type
 * @property integer $rule
 * @property integer $rule_value
 * @property float $min_money
 * @property float $money
 * @property integer $send_type
 * @property integer $send_value
 * @property integer $every_amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class CouponModel extends CouponEntity {

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

    public function getReceivedAttribute() {
        return CouponLogModel::where('coupon_id', $this->id)->count();
    }

    public function getCanReceiveAttribute() {
        if (auth()->guest()) {
            return true;
        }
        return CouponLogModel::where('coupon_id', $this->id)->where('user_id', auth()->id())
                ->count() < 1;
    }
}