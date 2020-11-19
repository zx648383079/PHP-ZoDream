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
        $address = AddressRepository::getDefault();
        $order = OrderModel::preview($goods_list);
        $order->setAddress($address);
        $shipping_list = empty($address) ? [] : ShippingModel::getByAddress($address);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('goods_list',
            'address', 'order', 'shipping_list',
            'payment_list'));
    }


    public function checkoutAction($address, $shipping, $payment, $cart = '', $type = 0) {
        try {
            $order = CartRepository::checkout($address, $shipping, $payment, $cart, $type);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        return $this->renderData([
            'url' => url('./cashier/pay', ['id' => $order->id])
        ], '提交订单成功！');
    }

    public function previewAction($address, $shipping = 0, $payment = 0, $cart = '', $type = 0) {
        try {
            $goods_list = CartRepository::getGoodsList($cart, $type);
            $order = CartRepository::preview($goods_list, $address, $shipping, $payment);
        } catch (Exception $e) {
            return $this->renderFailure($e->getMessage());
        }
        $this->layout = false;
        $data = $order->toArray();
        $data['checkout'] = $this->renderHtml('total', compact('order'));
        return $this->renderData($data);
    }

    public function editAddressAction($id = 0, $prev = 0) {
        $this->layout = false;
        $address = $id > 0 ? AddressModel::findOrNew($id) : null;
        if ($id > 0) {
            $prev = $id;
        }
        return $this->show('addressEdit', compact('address', 'prev'));
    }

    public function addressAction($id) {
        $this->layout = false;
        $address = AddressModel::find($id);
        return $this->show(empty($address) ? 'addressEdit' : 'address', compact('address'));
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

    public function addressListAction($selected) {
        $this->layout = false;
        $address_list = AddressRepository::getList();
        return $this->show('addressList', compact('address_list', 'selected'));
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        $payment_list = PaymentModel::all();
        return $this->sendWithShare()->show(compact('order', 'payment_list'));
    }
}