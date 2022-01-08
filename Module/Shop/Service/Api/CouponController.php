<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Repositories\CouponRepository;

class CouponController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            '*' => '@'
        ];
    }

    public function indexAction(int $category = 0) {
        $coupon_list = CouponRepository::getCanReceive($category);
        return $this->renderPage($coupon_list);
    }

    public function myAction(int $status = 0) {
        $coupon_list = CouponRepository::getMy($status);
        return $this->renderPage($coupon_list);
    }

    public function receiveAction(int $id) {
        try {
            CouponRepository::receive($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function exchangeAction(string $code) {
        try {
            CouponRepository::exchange($code);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}