<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Repositories\CashierRepository;
use Exception;
use Module\Shop\Domain\Repositories\ShippingRepository;

/**
 * 收银员
 * @package Module\Shop\Service\Mobile
 */
class CashierController extends Controller {

    public function indexAction(string $cart = '', int $type = 0) {
        $goods_list = CashierRepository::getGoodsList($cart, $type);
        if (empty($goods_list)) {
            return $this->redirectWithMessage('./mobile', '请选择结算的商品');
        }
        $address = AddressModel::where('user_id', auth()->id())->first();
        $order = OrderModel::preview($goods_list);
        $shipping_list = empty($address) ? [] : ShippingRepository::getByAddress($address);
        $payment_list = PaymentModel::all();
        return $this->show(compact('goods_list', 'address', 'order', 'shipping_list', 'payment_list', 'cart', 'type'));
    }


    public function checkoutAction(int $address, string $shipping, string $payment, int $coupon = 0, mixed $cart = '', int $type = 0) {
        try {
            $order = CashierRepository::checkout($address, $shipping, $payment, $cart, $type);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('cashier/pay', ['id' => $order->id])
        ]);
    }

    public function previewAction(int $address, string $shipping = '', string $payment = '', int $coupon = 0, mixed $cart = '', int $type = 0) {
        try {
            $goods_list = CashierRepository::getGoodsList($cart, $type);
            $order = CashierRepository::preview($goods_list, $address, $shipping, $payment);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->renderData($order);
    }

    public function payAction(int $id) {
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