<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\CouponModel;

class Coupon extends CouponModel {

    protected $append = ['received', 'can_receive'];



}