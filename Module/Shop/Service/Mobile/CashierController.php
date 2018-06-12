<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\OrderModel;

/**
 * 收银员
 * @package Module\Shop\Service\Mobile
 */
class CashierController extends Controller {

    public function indexAction() {
        $goods_list = $this->getGoodsList();

        return $this->show(compact('goods_list'));
    }


    public function checkoutAction() {
        $goods_list = $this->getGoodsList();
        $order = new OrderModel();
        $order->addGoods($goods_list);
    }


    /**
     * @return CartModel[]
     */
    protected function getGoodsList() {

    }
}