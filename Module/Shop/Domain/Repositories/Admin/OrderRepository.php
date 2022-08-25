<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Auth\Domain\Repositories\BulletinRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\Legwork\Domain\Repositories\LegworkRepository;
use Module\Shop\Domain\Models\Logistics\DeliveryModel;
use Module\Shop\Domain\Models\OrderActivityModel;
use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderCouponModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\OrderRefundModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\Scene\Order;
use Module\Shop\Domain\Repositories\PaymentRepository;

class OrderRepository {

    public static function getList(
        string $series_number = '',
        int $status = 0,
        string|int $log_id = '',
        string $keywords = '',
        string $start_at = '',
        string $end_at = '',
        string $user = '',
        string $conginee = '',
        string $tel = '',
        string $address = '',
    ) {
        return Order::with('user', 'goods', 'address')
            ->when(!empty($series_number), function ($query) use ($series_number) {
                $query->where('series_number', $series_number);
            })
            ->when(!empty($start_at), function ($query) use ($start_at) {
                $query->where('created_at', '>=', strtotime($start_at));
            })
            ->when(!empty($end_at), function ($query) use ($end_at) {
                $query->where('created_at', '<=', strtotime($end_at));
            })
            ->when(!empty($conginee), function ($query) use ($conginee, $tel, $address) {
                $orderId = OrderAddressModel::query()->where('name', $conginee)
                    ->when(!empty($tel), function ($query) use ($tel) {
                        $query->where('tel', $tel);
                    })->when(!empty($address), function ($query) use ($address) {
                        SearchModel::searchWhere($query, ['address', 'region_name'], false, '', $address);
                    })->pluck('order_id');
                if (empty($orderId)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $orderId);
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $orderId = SearchModel::search(OrderGoodsModel::query(), 'name', false, '', $keywords)
                ->pluck('order_id');
                if (empty($orderId)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $orderId);
            })
            ->when(!empty($user), function ($query) use ($user) {
                $userId = UserRepository::searchUserId($user);
                if (empty($userId)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('user_id', $userId);
            })
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
    }

    public static function get(int $id, bool $full = false) {
        $order = OrderModel::findOrThrow($id, '订单不存在');
        if (!$full) {
            return $order;
        }
        $data = $order->toArray();
        $data['status_label'] = $order->status_label;
        $data['goods_list'] = OrderGoodsModel::where('order_id', $id)->get();
        $data['address'] = OrderAddressModel::where('order_id', $id)->one();
        $data['user'] = $order->user;
        $data['delivery'] = DeliveryModel::where('order_id', $id)->first();
        return $data;
    }

    public static function operate(int $id, string $operate, array $data) {
        $order = OrderModel::findOrThrow($id, '订单不存在');
        switch ($operate) {
            case 'shipping':
                if (!DeliveryModel::createByOrder($order,
                        $data['logistics_number'] ?? '',
                        $data['shipping_id'] ?? ''
                    ) || !OrderLogModel::shipping($order)) {
                    throw new \Exception('发货失败');
                }
                break;
            case 'pay':
                if (!OrderLogModel::pay($order,$data['remark'] ?? '')) {
                    throw new \Exception('支付失败');
                }
                break;
            case 'cancel':
                if (!OrderLogModel::cancel($order, $data['remark'] ?? '')) {
                    throw new \Exception('取消失败');
                }
                break;
            case 'refund':
                if (!PaymentRepository::refund($order,
                    $data['refund_type'] ?? '',
                    $data['money'] ?? '')) {
                    throw new \Exception('退款失败');
                }
                break;
            case 'fee':
                static::updateFee($order, $data);
                break;
        }
        return $order;
    }

    public static function updateFee(OrderModel $order, array $data) {
        $total = 0;
        foreach ([
            'pay_fee', 'shipping_fee'
                 ] as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }
            $value = floatval($data[$key]);
            if ($value < 0) {
                $value = 0;
            }
            $diff = $order->$key - $value;
            $order->$key = $value;
            $order->order_amount -= $diff;
            $total -= $diff;
        }
        if ($order->order_amount < 0) {
            $order->order_amount = 0;
        }
        $order->save();
        OrderLogModel::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'status' => $order->status,
            'remark' => sprintf('调整了费用[%f]', $total),
            'created_at' => time(),
        ]);
    }

    public static function getSubtotal() {
        $data = OrderModel::groupBy('status')->asArray()
            ->get('status, COUNT(*) AS count');
        $data = array_column($data, 'count', 'status');
        $keys = [
            'un_pay' => OrderModel::STATUS_UN_PAY,
            'shipped' => OrderModel::STATUS_SHIPPED,
            'finish' => OrderModel::STATUS_FINISH,
            'cancel' => OrderModel::STATUS_CANCEL,
            'invalid' => OrderModel::STATUS_INVALID,
            'paid_un_ship' => OrderModel::STATUS_PAID_UN_SHIP,
            'received' => OrderModel::STATUS_RECEIVED
        ];
        $args = [];
        foreach ($keys as $key => $status) {
            $args[] = [
                'name' => $key,
                'status' => $status,
                'count' => isset($data[$status])
                    ? intval($data[$status]) : 0,
                'label' => OrderModel::$status_list[$status]
            ];
        }
        $args[] = [
            'name' => 'uncomment',
            'count' => OrderGoodsModel::auth()
                ->where('status', OrderModel::STATUS_RECEIVED)->count(),
            'label' => '未评价'
        ];
        $args[] = [
            'name' => 'refunding',
            'count' => OrderRefundModel::auth()
                ->where('status', OrderRefundModel::STATUS_IN_REVIEW)->count(),
            'label' => '退款中'
        ];
        $args[] = [
            'name' => 'legwork',
            'label' => '待接单',
            'count' => LegworkRepository::waitTaking()
        ];
        $args[] = [
            'name' => 'bulletin',
            'label' => '未读消息',
            'count' => BulletinRepository::unreadCount()
        ];
        return $args;
    }

    public static function checkNew() {
        return [
            [
                'name' => 'paid_un_ship',
                'label' => '待发货',
                'status' => OrderModel::STATUS_PAID_UN_SHIP,
                'count' => OrderModel::where('status', OrderModel::STATUS_PAID_UN_SHIP)
                    ->count()
            ],
            [
                'name' => 'legwork',
                'label' => '待接单',
                'count' => LegworkRepository::waitTaking()
            ],
            [
                'name' => 'bulletin',
                'label' => '未读消息',
                'count' => BulletinRepository::unreadCount()
            ],
        ];
    }

    public static function remove(int $id) {
        $model = OrderModel::findOrThrow($id, '订单不存在');
        if ($model->status > OrderModel::STATUS_UN_PAY) {
            throw new \Exception('不能删除此订单');
        }
        $model->delete();
        OrderAddressModel::where('order_id', $model->id)->delete();
        OrderGoodsModel::where('order_id', $model->id)->delete();
        OrderActivityModel::where('order_id', $model->id)->delete();
        OrderCouponModel::where('order_id', $model->id)->delete();
    }
}