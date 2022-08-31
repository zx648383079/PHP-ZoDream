<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\AddressRepository;
use Module\Shop\Domain\Repositories\CashierRepository;
use Exception;

class CashierController extends Controller {

    public function addressAction(int $user, string $keywords = '') {
        return $this->renderPage(
            AddressRepository::search($user, $keywords)
        );
    }

    public function shippingAction(int $user, array $goods, int|array $address) {
        return $this->renderData(
            CashierRepository::shipList($user, $goods, $address, 1)
        );
    }

    public function paymentAction(array $goods = [], int $shipping = 0) {
        return $this->renderData(
            CashierRepository::paymentList($goods, $shipping, 1)
        );
    }

    public function couponAction(int $user, array $goods = []) {
        return $this->renderData(
            CashierRepository::couponList($user, $goods, 1)
        );
    }

    public function previewAction(
        int $user,
        array $goods, int|array $address = 0, int $shipping = 0, int $payment = 0, int $coupon = 0, string $coupon_code = '') {
        try {
            $goods_list = CashierRepository::getGoodsList($goods, 1);
            $order = CashierRepository::preview($user, $goods_list, $address, $shipping, $payment, $coupon, $coupon_code);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->render($order);
    }

    public function checkoutAction(int $user, array $goods,
                                   int|array $address, int $shipping, int $payment, int $coupon = 0, string $coupon_code = '') {
        try {
            $order = CashierRepository::checkout($user, $address, $shipping, $payment, $coupon, $coupon_code, $goods, 1);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        $data = $order->toArray();
        $data['address'] = $address;
        return $this->render($data);
    }

}