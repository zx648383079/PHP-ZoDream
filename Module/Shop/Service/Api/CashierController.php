<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\AddressModel;
use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\PaymentModel;
use Module\Shop\Domain\Model\ShippingModel;

class CashierController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function shippingAction($goods, $address) {
        return $this->render(ShippingModel::getByAddress($address));
    }

    public function paymentAction($goods, $shipping) {
        return $this->render(PaymentModel::all());
    }

    public function priceAction($goods, $address, $shipping, $payment) {
        return $this->render([
            'total' => 0
        ]);
    }

    public function checkoutAction($goods, $address, $shipping, $payment) {
        $goods_list = Module::cart()->filter(function ($item) use ($goods) {
            return in_array($item['id'], (array)$goods);
        });
        if (empty($goods_list)) {
            return $this->renderFailure('请选择结算的商品');
        }
        $order = OrderModel::preview($goods_list);
        if (!$order->setAddress(AddressModel::findWithAuth($address))) {
            return $this->renderFailure('请选择收货地址');
        }
        if (!$order->setPayment(PaymentModel::find($payment))) {
            return $this->renderFailure('请选择支付方式');
        }
        if (!$order->setShipping(ShippingModel::find($shipping))) {
            return $this->renderFailure('请选择配送方式');
        }
        if (!$order->createOrder()) {
            return $this->renderFailure('操作失败，请重试');
        }
        Module::cart()->remove(...$goods_list);
        return $this->render($order);
    }

}