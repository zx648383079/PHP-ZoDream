<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\CouponModel;

class CouponController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            '*' => '@'
        ];
    }

    public function indexAction() {
        $cat_list = CategoryModel::where('parent_id', 0)->limit(10)->all();
        $coupon_list = CouponModel::where('send_type', 0)->page();
        return $this->show(compact('cat_list', 'coupon_list'));
    }

    public function myAction() {
        $coupon_list = CouponModel::where('send_type', 0)->page();
        return $this->show(compact('coupon_list'));
    }
}