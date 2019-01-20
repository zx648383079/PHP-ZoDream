<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\AddressModel;
use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\PaymentModel;
use Module\Shop\Domain\Model\ShippingModel;
use Module\Shop\Module;

/**
 * 收银员
 * @package Module\Shop\Service\Mobile
 */
class CashierController extends Controller {

    public function indexAction($cart = '') {
        $cart_ids = explode('-', $cart);
        $goods_list = Module::cart()->filter(function ($item) use ($cart_ids) {
            return in_array($item['id'], $cart_ids);
        });
        if (empty($goods_list)) {
            return $this->redirectWithMessage('./', '请选择结算的商品');
        }
        $address = AddressModel::where('user_id', auth()->id())->one();
        $order = OrderModel::preview($goods_list);
        $shipping_list = empty($address) ? [] : ShippingModel::getByAddress($address);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('goods_list', 'address', 'order', 'shipping_list', 'payment_list'));
    }


    public function checkoutAction($address, $shipping, $payment, $cart = '') {
        $cart_ids = explode('-', $cart);
        $goods_list = Module::cart()->filter(function ($item) use ($cart_ids) {
            return in_array($item['id'], $cart_ids);
        });
        if (empty($goods_list)) {
            return $this->jsonFailure('请选择结算的商品');
        }
        $order = OrderModel::preview($goods_list);
        if (!$order->setAddress(AddressModel::find($address))) {
            return $this->jsonFailure('请选择收货地址');
        }
        if (!$order->setPayment(PaymentModel::find($payment))) {
            return $this->jsonFailure('请选择支付方式');
        }
        if (!$order->setShipping(ShippingModel::find($shipping))) {
            return $this->jsonFailure('请选择配送方式');
        }
        if (!$order->createOrder()) {
            return $this->jsonFailure('操作失败，请重试');
        }
        Module::cart()->remove(...$goods_list);
        return $this->jsonSuccess([
            'url' => url('./cashier/pay', ['id' => $order->id])
        ]);
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('order', 'payment_list'));
    }
}