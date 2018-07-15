<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\AddressModel;
use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Shop\Domain\Model\OrderModel;

/**
 * 收银员
 * @package Module\Shop\Service\Mobile
 */
class CashierController extends Controller {

    public function indexAction() {
        $goods_list = $this->getGoodsList();
        $address = AddressModel::one();
        $order = OrderModel::preview($goods_list);
        return $this->show(compact('goods_list', 'address', 'order'));
    }


    public function checkoutAction() {
        $goods_list = $this->getGoodsList();
        $order = OrderModel::preview($goods_list);
        $order->createOrder();
        return $this->redirect($this->getUrl('cashier/pay', ['id' => $order->id]));
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        return $this->show(compact('order'));
    }


    /**
     * @return CartModel[]
     */
    protected function getGoodsList() {
        return CartModel::getAllGoods();
    }
}