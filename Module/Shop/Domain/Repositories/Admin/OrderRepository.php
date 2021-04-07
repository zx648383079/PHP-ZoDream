<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Module\Auth\Domain\Repositories\BulletinRepository;
use Module\Legwork\Domain\Repositories\LegworkRepository;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\OrderRefundModel;

class OrderRepository {

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
}