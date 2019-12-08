<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Repositories\AddressRepository;
use Module\Shop\Domain\Repositories\CartRepository;
use Exception;

/**
 * 收银员
 * @package Module\Shop\Service\Mobile
 */
class CashierController extends Controller {

    public function indexAction($cart = '', $type = 0) {
        $goods_list = CartRepository::getGoodsList($cart, $type);
        if (empty($goods_list)) {
            return $this->redirectWithMessage('./', '请选择结算的商品');
        }
        $address = AddressModel::where('user_id', auth()->id())->first();
        $order = OrderModel::preview($goods_list);
        $shipping_list = empty($address) ? [] : ShippingModel::getByAddress($address);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('goods_list', 'address', 'order', 'shipping_list', 'payment_list'));
    }


    public function checkoutAction($address, $shipping, $payment, $cart = '', $type = 0) {
        try {
            $order = CartRepository::checkout($address, $shipping, $payment, $cart, $type);
        } catch (Exception $e) {
            return $this->jsonFailure($e->getMessage());
        }
        return $this->jsonSuccess([
            'url' => url('./cashier/pay', ['id' => $order->id])
        ]);
    }

    public function previewAction($address, $shipping = 0, $payment = 0, $cart = '', $type = 0) {
        try {
            $goods_list = CartRepository::getGoodsList($cart, $type);
            $order = CartRepository::preview($goods_list, $address, $shipping, $payment);
        } catch (Exception $e) {
            return $this->jsonFailure($e->getMessage());
        }
        return $this->jsonSuccess($order);
    }

    public function editAddressAction($id = 0) {
        $this->layout = false;
        $address = $id > 0 ? AddressModel::findOrNew($id) : null;
        return $this->show('addressEdit', compact('address'));
    }

    public function saveAddressAction() {
        $data = app('request')->get();
        try {
            $address = AddressRepository::save($data);
        } catch (\Exception $ex) {
            return $this->showContent('false,'.$ex->getMessage());
        }
        $this->layout = false;
        return $this->show('address', compact('address'));
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('order', 'payment_list'));
    }
}