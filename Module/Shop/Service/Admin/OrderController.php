<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Model\Logistics\DeliveryModel;
use Module\Shop\Domain\Model\OrderLogModel;
use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\OrderAddressModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Shop\Domain\Model\ShippingModel;

class OrderController extends Controller {

    public function indexAction() {
        $model_list = OrderModel::with('user', 'goods', 'address')
            ->orderBy('created_at', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function infoAction($id) {
        $order = OrderModel::find($id);
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        $user = UserModel::find($order->user_id);
        $delivery = DeliveryModel::where('order_id', $id)->first();
        return $this->show(compact('order', 'goods_list', 'address', 'user', 'delivery'));
    }

    public function shippingAction($id) {
        $order = OrderModel::find($id);
        if ($order->status != OrderModel::STATUS_PAID_UN_SHIP) {
            return $this->redirectWithMessage($this->getUrl('order'), '订单状态有误');
        }
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        $shipping_list = ShippingModel::all();
        return $this->show(compact('order', 'goods_list', 'address', 'shipping_list'));
    }

    public function saveAction($id, $operate = null) {
        $order = OrderModel::find($id);
        if ($operate == 'shipping') {
            if (!DeliveryModel::createByOrder($order,
                app('request')->get('logistics_number'),
                app('request')->get('shipping_id')
                ) || !OrderLogModel::shipping($order)) {
                return $this->jsonFailure('发货失败');
            }
        }
        if ($operate == 'pay') {
            if (!OrderLogModel::pay($order, app('request')->get('remark'))) {
                return $this->jsonFailure('支付失败');
            }
        }
        if ($operate == 'cancel') {
            if (!OrderLogModel::cancel($order, app('request')->get('remark'))) {
                return $this->jsonFailure('取消失败');
            }
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('order/info', ['id' => $id])
        ]);
    }

}