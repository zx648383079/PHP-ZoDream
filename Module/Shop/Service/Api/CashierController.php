<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Repositories\CartRepository;
use Module\Shop\Domain\Repositories\CouponRepository;
use Module\Shop\Domain\Repositories\ShippingRepository;
use Exception;

class CashierController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function shippingAction($goods, $address, $type = 0) {
        $goods_list = CartRepository::getGoodsList($goods, $type);
        $data = ShippingModel::getByAddress(AddressModel::findWithAuth($address));
        if (empty($data)) {
            return $this->renderFailure('当前地址不在配送范围内');
        }
        foreach ($data as $item) {
            $item->shipping_fee = ShippingRepository::getFee($item, $item->settings, $goods_list);
        }
        return $this->render(compact('data'));
    }

    public function paymentAction($goods = [], $shipping = []) {
        $data = PaymentModel::all();
        return $this->render(compact('data'));
    }

    public function couponAction($goods = [], $type = 0) {
        $goods_list = CartRepository::getGoodsList($goods, $type);
        $coupon_list = CouponRepository::getMyUseGoods($goods_list);
        return $this->render($coupon_list);
    }

    public function previewAction(
            $goods, $address = 0, $shipping = 0, $payment = 0, $type = 0) {
        try {
            $goods_list = CartRepository::getGoodsList($goods, $type);
            $order = CartRepository::preview($goods_list, $address, $shipping, $payment);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->render($order);
    }

    public function checkoutAction($goods, $address, $shipping, $payment, $type = 0) {
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