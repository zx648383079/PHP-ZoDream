<?php
namespace Module\Shop\Domain\Repositories\Admin;

use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\OrderRefundModel;
use Module\Legwork\Domain\Model\OrderModel as LegworkOrder;

class OrderRepository {

    public static function getSubtotal() {
        $data = OrderModel::groupBy('status')->asArray()
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
        $args['legwork'] = LegworkOrder::where('runner', 0)
            ->where('status', LegworkOrder::STATUS_PAID_UN_TAKING)->count();
        $args['bulletin'] = BulletinModel::unreadCount();
        return $args;
    }

    public static function checkNew() {
        return [
            'paid_un_ship' => OrderModel::where('status', OrderModel::STATUS_PAID_UN_SHIP)
            ->count(),
            'legwork' => LegworkOrder::where('runner', 0)
                ->where('status', LegworkOrder::STATUS_PAID_UN_TAKING)->count(),
            'bulletin' => BulletinModel::unreadCount()
        ];
    }
}