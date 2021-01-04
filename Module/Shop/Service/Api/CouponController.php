<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CouponLogModel;
use Module\Shop\Domain\Models\CouponModel;
use Module\Shop\Domain\Models\Scene\Coupon;
use Module\Shop\Domain\Repositories\CouponRepository;

class CouponController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            '*' => '@'
        ];
    }

    public function indexAction($category = 0) {
        $coupon_list = CouponRepository::getCanReceive($category);
        return $this->renderPage($coupon_list);
    }

    public function myAction($status = 0) {
        $coupon_list = CouponRepository::getMy($status);
        return $this->renderPage($coupon_list);
    }

    public function receiveAction($id) {
        $coupon = CouponModel::find($id);
        if (!$coupon || !$coupon->can_receive) {
            return $this->renderFailure('领取失败!');
        }
        CouponLogModel::create([
            'user_id' => auth()->id(),
            'coupon_id' => $coupon->id
        ]);
        return $this->render(['data' => true]);
    }
}