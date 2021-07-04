<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Repositories\CartRepository;
use Module\Shop\Domain\Repositories\CouponRepository;
use Module\Shop\Domain\Repositories\ShippingRepository;
use Exception;

class CashierController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function shippingAction($goods, int $address, int $type = 0) {
        $goods_list = CartRepository::getGoodsList($goods, $type);
        $data = ShippingRepository::getByAddress(AddressModel::findWithAuth($address));
        if (empty($data)) {
            return $this->renderFailure('当前地址不在配送范围内');
        }
        foreach ($data as $item) {
            $item->shipping_fee = ShippingRepository::getFee($item, $item->settings, $goods_list);
        }
        return $this->render(compact('data'));
    }

    public function paymentAction(array $goods = [], int $shipping = 0) {
        $data = PaymentModel::all();
        return $this->render(compact('data'));
    }

    public function couponAction(array $goods = [], int $type = 0) {
        $goods_list = CartRepository::getGoodsList($goods, $type);
        $coupon_list = CouponRepository::getMyUseGoods($goods_list);
        return $this->render($coupon_list);
    }

    public function previewAction(
            $goods, int $address = 0, int $shipping = 0, int $payment = 0, int $type = 0) {
        try {
            $goods_list = CartRepository::getGoodsList($goods, $type);
            $order = CartRepository::preview($goods_list, $address, $shipping, $payment);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->render($order);
    }

    public function checkoutAction($goods, int $address, int $shipping, int $payment, int $type = 0) {
        try {
            $order = CartRepository::checkout($address, $shipping, $payment, $goods, $type);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        $data = $order->toArray();
        $data['address'] = $address;
        return $this->render($data);
    }

}