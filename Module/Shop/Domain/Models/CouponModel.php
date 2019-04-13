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

    const TYPE_MONEY = 0;  // 优惠
    const TYPE_DISCOUNT = 1; // 折扣

    const RULE_NONE = 0;
    const RULE_GOODS = 1;
    const RULE_CATEGORY = 2;
    const RULE_BRAND = 3;
    const RULE_STORE = 4;

    const SEND_RECEIVE = 0; // 前台领取
    const SEND_GOODS = 1;   // 购买商品
    const SEND_ORDER = 2;   // 订单金额
    const SEND_SIGN = 3;    // 签到
    const SEND_USER = 4;    // 按用户


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