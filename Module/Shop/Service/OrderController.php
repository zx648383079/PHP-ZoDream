<?php
declare(strict_types=1);
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Repositories\OrderRepository;


class OrderController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(int $status = 0) {
        $order_list = OrderRepository::getList($status);
        if (request()->isAjax()) {
            $this->layout = '';
            return $this->show('page', compact('order_list'));
        }
        $order_subtotal = OrderRepository::getSubtotal();
        return $this->sendWithShare()->show(compact('order_list', 'status', 'order_subtotal'));
    }

    public function detailAction(int $id) {
        $order = OrderModel::find($id);
        $goods_list = OrderGoodsModel::where('order_id', $id)->get();
        $address = OrderAddressModel::where('order_id', $id)->first();
        return $this->sendWithShare()->show(compact('order', 'goods_list', 'address'));
    }

    public function payAction(int $id) {

    }

    public function receiveAction(int $id) {
        try {
            OrderRepository::receive($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function cancelAction(int $id) {
        try {
            OrderRepository::cancel($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function repurchaseAction(int $id) {
        try {
            OrderRepository::repurchase($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./cart')
        ]);
    }
}