<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Shop\Domain\Cron\ExpiredOrder;
use Module\Shop\Domain\Models\Logistics\DeliveryModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\Scene\Order;
use Module\Shop\Domain\Repositories\PaymentRepository;

class OrderController extends Controller {

    public function indexAction($series_number = null, $status = 0, $log_id = null) {
        $model_list = Order::with('user', 'goods', 'address')
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
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $order = OrderModel::find($id);
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        $user = UserSimpleModel::find($order->user_id);
        $delivery = DeliveryModel::where('order_id', $id)->first();
        $data = $order->toArray();
        return $this->render(array_merge($data, compact('goods_list', 'address', 'user', 'delivery')));
    }

    public function saveAction($id, $operate = null) {
        $order = OrderModel::find($id);
        if ($operate == 'shipping') {
            if (!DeliveryModel::createByOrder($order,
                request()->get('logistics_number'),
                request()->get('shipping_id')
                ) || !OrderLogModel::shipping($order)) {
                return $this->renderFailure('发货失败');
            }
        }
        if ($operate == 'pay') {
            if (!OrderLogModel::pay($order, request()->get('remark'))) {
                return $this->renderFailure('支付失败');
            }
        }
        if ($operate == 'cancel') {
            if (!OrderLogModel::cancel($order, request()->get('remark'))) {
                return $this->renderFailure('取消失败');
            }
        }
        if ($operate == 'refund') {
            if (!PaymentRepository::refund($order,
                request()->get('refund_type'),
                request()->get('money'))) {
                return $this->renderFailure('退款失败');
            }
        }
        return $this->render($order);
    }

    public function cronAction() {
        new ExpiredOrder();
        return $this->renderData(true);
    }

}