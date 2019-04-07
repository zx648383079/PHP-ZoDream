<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Module;
use Zodream\Helpers\Json;

/**
 * 收银员
 * @package Module\Shop\Service\Mobile
 */
class CashierController extends Controller {

    public function indexAction($cart = '', $type = 0) {
        $goods_list = $this->getGoodsList($cart, $type);
        if (empty($goods_list)) {
            return $this->redirectWithMessage('./', '请选择结算的商品');
        }
        $address = AddressModel::where('user_id', auth()->id())->one();
        $order = OrderModel::preview($goods_list);
        $shipping_list = empty($address) ? [] : ShippingModel::getByAddress($address);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('goods_list', 'address', 'order', 'shipping_list', 'payment_list'));
    }


    public function checkoutAction($address, $shipping, $payment, $cart = '', $type = 0) {
        $goods_list = $this->getGoodsList($cart, $type);
        if (empty($goods_list)) {
            return $this->jsonFailure('请选择结算的商品');
        }
        $order = OrderModel::preview($goods_list);
        if (!$order->setAddress(AddressModel::findWithAuth($address))) {
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
        if ($type < 1) {
            Module::cart()->remove(...$goods_list);
        }
        return $this->jsonSuccess([
            'url' => url('./cashier/pay', ['id' => $order->id])
        ]);
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('order', 'payment_list'));
    }

    /**
     * @param string $cart
     * @param int $type
     * @return CartModel[]
     * @throws \Exception
     */
    protected function getGoodsList($cart = '', $type = 0) {
        if ($type < 1) {
            $cart_ids = explode('-', $cart);
            return Module::cart()->filter(function ($item) use ($cart_ids) {
                return in_array($item['id'], $cart_ids);
            });
        }
        if (empty($cart)) {
            return [];
        }
        $cart = Json::decode($cart);
        $data = [];
        foreach ($cart as $item) {
            if (!isset($item['goods'])) {
                continue;
            }
            $goods = GoodsModel::find($item['goods']);
            if (empty($goods)) {
                continue;
            }
            $data[] = CartModel::fromGoods($goods,
                max(1, isset($item['amount']) ? intval($item['amount']) : 1));
        }
        return $data;
    }
}