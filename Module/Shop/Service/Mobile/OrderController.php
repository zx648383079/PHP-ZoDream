<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Repositories\OrderRepository;


class OrderController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
        $order_list = OrderRepository::getList($status);
        return $this->show(compact('order_list', 'status'));
    }

    public function detailAction($id) {
        $order = OrderModel::where('id', $id)->where('user_id', auth()->id())->first();
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        return $this->show(compact('order', 'goods_list', 'address'));
    }

    public function receiveAction($id) {
        try {
            OrderRepository::receive($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function cancelAction($id) {
        try {
            OrderRepository::cancel($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
           'refresh' => true
        ]);
    }

    public function repurchaseAction($id) {
        try {
            OrderRepository::repurchase($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('cart')
        ]);
    }
}