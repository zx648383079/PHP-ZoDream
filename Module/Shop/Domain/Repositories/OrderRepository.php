<?php
namespace Module\Shop\Domain\Repositories;

use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\OrderRefundModel;
use Module\Shop\Domain\Models\Scene\Order;
use Zodream\Html\Page;
use Exception;

class OrderRepository {

    /**
     * @param int|int[] $status
     * @param string $keywords
     * @return Page<Order>
     * @throws Exception
     */
    public static function getList(int|array $status = 0, string $keywords = '') {
        return Order::with('goods')
            ->where('user_id', auth()->id())
            ->when(!empty($status), function ($query) use ($status) {
                if (is_array($status)) {
                    return $query->whereIn('status', $status);
                }
                $query->where('status', intval($status));
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                Order::searchWhere($query, ['series_number']);
            })
            ->orderBy('created_at', 'desc')
            ->page();
    }

    /**
     * 签收
     * @param $id
     * @return OrderModel
     * @throws Exception
     */
    public static function receive($id) {
        $order = OrderModel::findWithAuth($id);
        if (empty($order)) {
            throw new Exception('订单不存在');
        }
        if ($order->status != OrderModel::STATUS_SHIPPED) {
            throw new Exception('订单签收失败');
        }
        if (!OrderLogModel::receive($order)) {
            throw new Exception('订单签收失败');
        }
        return $order;
    }

    public static function cancel($id) {
        $order = OrderModel::findWithAuth($id);
        if (empty($order)) {
            throw new Exception('订单不存在');
        }
        $status = $order->status;
        if (!in_array($status, [OrderModel::STATUS_UN_PAY, OrderModel::STATUS_PAID_UN_SHIP])) {
            throw new Exception('订单无法取消');
        }
//        if ($status === OrderModel::STATUS_PAID_UN_SHIP) {
//            throw new Exception('请联系商家进行退款');
//        }
        if (!OrderLogModel::cancel($order)) {
            throw new Exception('订单取消失败');
        }
        if ($status === OrderModel::STATUS_PAID_UN_SHIP) {
            $log = PaymentRepository::getPayedLog($order);
            if (empty($log)) {
                throw new Exception('未遭到您的支付记录，请联系商家');
            }
            BulletinModel::system([1, 14], sprintf('订单【%s】申请退款', $order->series_number),
                sprintf('订单%d【%s】的支付流水号【%s】第三方流水号【%s】,<a href="%s">马上查看</a>',
                    $order->id, $order->series_number, $log->id, $log->trade_no,
                    url('./@admin/order/info', ['id' => $order->id])), 66);
        }
        return $order;
    }

    public static function repurchase($id) {
        $order = OrderModel::findWithAuth($id);
        if (empty($order)) {
            throw new Exception('订单不存在');
        }
        $goods_list = OrderGoodsModel::with('goods')
            ->where('order_id', $order->id)->get();
        foreach ($goods_list as $goods) {
            if (!$goods->goods) {
                throw new Exception(sprintf('商品 【%s】已下架', $goods->name));
            }
            CartRepository::checkGoods($goods->goods, $goods->amount);
        }
        foreach ($goods_list as $goods) {
            CartRepository::addGoods($goods->goods, $goods->amount);
        }
        return true;
    }

    public static function getSubtotal() {
        if (auth()->guest()) {
            return [
                'un_pay' => 0,
                'shipped' => 0,
                'finish' => 0,
                'cancel' => 0,
                'invalid' => 0,
                'paid_un_ship' => 0,
                'received' => 0,
                'uncomment' => 0,
                'refunding' => 0
            ];
        }
        $data = OrderModel::where('user_id', auth()->id())->groupBy('status')->asArray()
            ->get('status, COUNT(*) AS count');
        $data = array_column($data, 'count', 'status');
        $args = [
            'un_pay' => OrderModel::STATUS_UN_PAY,
            'shipped' => OrderModel::STATUS_SHIPPED,
            'finish' => OrderModel::STATUS_FINISH,
            'cancel' => OrderModel::STATUS_CANCEL,
            'invalid' => OrderModel::STATUS_INVALID,
            'paid_un_ship' => OrderModel::STATUS_PAID_UN_SHIP,
            'received' => OrderModel::STATUS_RECEIVED
        ];
        foreach ($args as $key => $status) {
            $args[$key] = isset($data[$status])
                ? intval($data[$status]) : 0;
        }
        $args['uncomment'] = OrderGoodsModel::auth()
            ->where('status', OrderModel::STATUS_RECEIVED)->count();
        $args['refunding'] = OrderRefundModel::auth()
            ->where('status', OrderRefundModel::STATUS_IN_REVIEW)->count();
        return $args;
    }
}