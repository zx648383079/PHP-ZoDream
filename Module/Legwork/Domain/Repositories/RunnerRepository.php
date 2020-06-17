<?php
namespace Module\Legwork\Domain\Repositories;

use Exception;
use Module\Legwork\Domain\Model\OrderLogModel;
use Module\Legwork\Domain\Model\OrderModel;

class RunnerRepository {

    public static function waitTakingList() {
        return OrderModel::with('service')
            ->where('runner', 0)
            ->where('status', OrderModel::STATUS_PAID_UN_TAKING)
            ->orderBy('id', 'desc')
            ->select([
                'id',
                'user_id',
                'service_id',
                'order_amount',
                'status',
                'pay_at',
                'created_at',
                'updated_at',
            ])
            ->page();
    }

    public static function getOrders($status = 0) {
        return OrderModel::query()->with('service')->where('runner', auth()->id())
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', '>=', OrderModel::STATUS_UN_PAY);
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function taking($id) {
        $order = OrderModel::where('runner', 0)
            ->where('status', OrderModel::STATUS_PAID_UN_TAKING)
            ->where('id', $id)
            ->first();
        if (empty($order)) {
            throw new Exception('单已失效');
        }
        $order->runner = auth()->id();
        if (OrderLogModel::taking($order)) {
            return $order;
        }
        throw new Exception('接单失败');
    }

    public static function taken($id) {
        $order = OrderModel::where('runner', auth()->id())
            ->where('status', OrderModel::STATUS_TAKING_UN_DO)
            ->where('id', $id)
            ->first();
        if (empty($order)) {
            throw new Exception('单已失效');
        }
        if (OrderLogModel::taken($order)) {
            return $order;
        }
        throw new Exception('结束单失败');
    }
}