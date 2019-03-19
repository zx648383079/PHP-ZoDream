<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\AddressModel;
use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\PaymentModel;
use Module\Shop\Domain\Model\Scene\Goods;
use Module\Shop\Domain\Model\Scene\Order;
use Module\Shop\Domain\Model\ShippingModel;
use Module\Shop\Module;

class CashierController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function shippingAction($goods, $address, $type = 0) {
        $goods_list = $this->getGoodsList($goods, $type);
        return $this->render(ShippingModel::getByAddress(AddressModel::findWithAuth($address)));
    }

    public function paymentAction($goods = [], $shipping = []) {
        return $this->render(PaymentModel::all());
    }

    public function previewAction(
            $goods, $address = 0, $shipping = 0, $payment = 0, $type = 0) {
        $goods_list = $this->getGoodsList($goods, $type);
        if (empty($goods_list)) {
            return $this->renderFailure('请选择结算的商品');
        }
        $order = OrderModel::preview($goods_list);
        if (!$order->setAddress(AddressModel::findWithAuth($address))) {
            return $this->renderFailure('请选择收货地址');
        }
        if ($payment > 0 && !$order->setPayment(PaymentModel::find($payment))) {
            return $this->renderFailure('请选择支付方式');
        }
        if ($shipping > 0 && !$order->setShipping(ShippingModel::find($shipping))) {
            return $this->renderFailure('请选择配送方式');
        }
        return $this->render($order);
    }

    public function checkoutAction($goods, $address, $shipping, $payment, $type = 0) {
        $goods_list = $this->getGoodsList($goods, $type);
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
        if ($type < 1) {
            Module::cart()->remove(...$goods_list);
        }
        $data = $order->toArray();
        $data['address'] = $address;
        return $this->render($data);
    }

    protected function getGoodsList($goods, $type = 0) {
        if ($type < 1) {
            return Module::cart()->filter(function ($item) use ($goods) {
                return in_array($item['id'], (array)$goods);
            });
        }
        if (empty($goods)) {
            return [];
        }
        $data = [];
        foreach ($goods as $item) {
            if (!isset($item['goods_id'])) {
                continue;
            }
            $model = GoodsModel::find($item['goods_id']);
            if (empty($model)) {
                continue;
            }
            $data[] = CartModel::fromGoods($model,
                max(1, isset($item['amount']) ? intval($item['amount']) : 1));
        }
        return $data;
    }

}