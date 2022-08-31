<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Repositories\CashierRepository;
use Exception;

class CashierController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function shippingAction(array $goods, int $address, int $type = 0) {
        return $this->renderData(
            CashierRepository::shipList(auth()->id(), $goods, $address, $type)
        );
    }

    public function paymentAction(array $goods = [], int $shipping = 0, int $type = 0) {
        return $this->renderData(
            CashierRepository::paymentList($goods, $shipping, $type)
        );
    }

    public function couponAction(array $goods = [], int $type = 0) {
        return $this->renderData(
            CashierRepository::couponList(auth()->id(), $goods, $type)
        );
    }

    public function previewAction(
            $goods, int $address = 0, int $shipping = 0, int $payment = 0, int $coupon = 0, int $type = 0) {
        try {
            $goods_list = CashierRepository::getGoodsList($goods, $type);
            $order = CashierRepository::preview(auth()->id(), $goods_list, $address, $shipping, $payment, $coupon);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->render($order);
    }

    public function checkoutAction($goods, int $address, int $shipping, int $payment, int $coupon = 0, int $type = 0) {
        try {
            $order = CashierRepository::checkout(auth()->id(), $address, $shipping, $payment, $coupon, '', $goods, $type);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        $data = $order->toArray();
        $data['address'] = $address;
        return $this->render($data);
    }

}