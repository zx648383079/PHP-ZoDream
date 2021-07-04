<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Repositories\CartRepository;
use Exception;
use Module\Shop\Domain\Repositories\ShippingRepository;

/**
 * 收银员
 * @package Module\Shop\Service\Mobile
 */
class CashierController extends Controller {

    public function indexAction($cart = '', $type = 0) {
        $goods_list = CartRepository::getGoodsList($cart, $type);
        if (empty($goods_list)) {
            return $this->redirectWithMessage('./mobile', '请选择结算的商品');
        }
        $address = AddressModel::where('user_id', auth()->id())->one();
        $order = OrderModel::preview($goods_list);
        $shipping_list = empty($address) ? [] : ShippingRepository::getByAddress($address);
        $payment_list = PaymentModel::all();
        return $this->show(compact('goods_list', 'address', 'order', 'shipping_list', 'payment_list', 'cart', 'type'));
    }


    public function checkoutAction($address, $shipping, $payment, $cart = '', $type = 0) {
        try {
            $order = CartRepository::checkout($address, $shipping, $payment, $cart, $type);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('cashier/pay', ['id' => $order->id])
        ]);
    }

    public function previewAction($address, $shipping = 0, $payment = 0, $cart = '', $type = 0) {
        try {
            $goods_list = CartRepository::getGoodsList($cart, $type);
            $order = CartRepository::preview($goods_list, $address, $shipping, $payment);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->renderData($order);
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return $this->redirectWithMessage($this->getUrl('order'), '不能支付此订单');
        }
        $data = PaymentModel::all();
        $payment = null;
        $payment_list = [];
        foreach ($data as $item) {
            if ($item->id == $order->payment_id) {
                $payment = $item;
                continue;
            }
            $payment_list[] = $item;
        }
        return $this->show(compact('order', 'payment', 'payment_list'));
    }
}