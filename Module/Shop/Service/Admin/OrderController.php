<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Cron\ExpiredOrder;
use Module\Shop\Domain\Models\Logistics\DeliveryModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Repositories\PaymentRepository;

class OrderController extends Controller {

    public function indexAction($series_number = null, $status = 0, $log_id = null) {
        $model_list = OrderModel::with('user', 'goods', 'address')
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(!empty($log_id), function ($query) use ($log_id) {
                $orderId = PayLogModel::when(strlen($log_id) > 11 || !is_numeric($log_id), function ($query) use ($log_id) {
                    $query->where('trade_no', $log_id);
                }, function ($query) use ($log_id) {
                    $query->where('id', $log_id);
                })->where('type', PayLogModel::TYPE_ORDER)->value('data');
                if (empty($orderId)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', explode(',', $orderId));
            })
            ->orderBy('created_at', 'desc')->page();
        $status_list = OrderModel::$status_list;
        return $this->show(compact('model_list', 'series_number', 'status_list', 'status', 'log_id'));
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

    public function refundAction($id) {
        $order = OrderModel::find($id);
        if (!in_array($order->status, [OrderModel::STATUS_PAID_UN_SHIP])) {
            return $this->redirectWithMessage($this->getUrl('order'), '订单状态有误');
        }
        $payment_list = [
            '原路退回',
            '退到余额',
            '线下退款'
        ];
        return $this->show(compact('order', 'payment_list'));
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
        if ($operate == 'refund') {
            if (!PaymentRepository::refund($order,
                app('request')->get('refund_type'),
                app('request')->get('money'))) {
                return $this->jsonFailure('退款失败');
            }
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('order/info', ['id' => $id])
        ]);
    }

    public function cronAction() {
        new ExpiredOrder();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

}