<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\CouponModel;

/**
 * Class Coupon
 * @package Module\Shop\Domain\Models\Scene
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property integer $type
 * @property integer $rule
 * @property string $rule_value
 * @property float $min_money
 * @property float $money
 * @property integer $send_type
 * @property integer $send_value
 * @property integer $every_amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class Coupon extends CouponModel {

    protected array $append = ['received', 'can_receive'];



}