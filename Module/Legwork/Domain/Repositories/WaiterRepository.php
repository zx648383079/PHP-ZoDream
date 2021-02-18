<?php
declare(strict_types=1);
namespace Module\Legwork\Domain\Repositories;

use Exception;
use Module\Legwork\Domain\Model\OrderLogModel;
use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Model\OrderSimpleModel;
use Module\Legwork\Domain\Model\ServiceModel;
use Module\Legwork\Domain\Model\ServiceSimpleModel;
use Module\Legwork\Domain\Model\ServiceWaiterModel;
use Module\Legwork\Domain\Model\WaiterModel;

class WaiterRepository {
    public static function getList(string $keywords = '') {
        return WaiterModel::query()->when(!empty($keywords), function ($query) {
            WaiterModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return WaiterModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf() {
        $model = WaiterModel::where('user_id', auth()->id())->first();
        if (!empty($model)) {
            return $model;
        }
        return new WaiterModel([
            'id' => auth()->id()
        ]);
    }

    public static function applyService(int|array $id) {
        if (!WaiterRepository::isWaiter(auth()->id())) {
            throw new Exception('您的身份还在审核中。。。');
        }
        $exist = ServiceWaiterModel::where('user_id', auth()->id())
            ->pluck('service_id');
        $items = (array)$id;
        $add = array_diff($items, $exist);
        $update = array_diff($exist, $items);
        if (!empty($add)) {
            ServiceWaiterModel::query()->insert(array_map(function ($service_id) {
                return [
                    'user_id' => auth()->id(),
                    'service_id' => $service_id,
                    'status' => 0,
                ];
            }, $add));
        }
        if (!empty($update)) {
            ServiceWaiterModel::where('user_id', auth()->id())
                ->whereIn('service_id', $update)
                ->where('status', '<>', ServiceWaiterModel::STATUS_DISALLOW)
                ->update([
                    'status' => 0
                ]);
        }
    }

    public static function change(int $id, int $status) {
        $model = self::get($id);
        $model->status = $status;
        $model->save();
        return $model;
    }

    public static function save(array $data) {
        unset($data['id'], $data['user_id']);
        $model = self::getSelf();
        $model->load($data);
        $model->user_id = auth()->id();
        $model->status = 0;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        WaiterModel::where('id', $id)->delete();
    }

    public static function isWaiter(int $id): bool {
        return WaiterModel::where('user_id', $id)
            ->where('status', WaiterModel::STATUS_ALLOW)
            ->count() > 0;
    }

    public static function serviceList(string $keywords = '', int $category = 0, bool $all = false) {
        $links = ServiceWaiterModel::query()->where('user_id', auth()->id())
            ->asArray()
            ->pluck(null, 'service_id');
        $page = ServiceSimpleModel::when(!empty($keywords), function ($query) {
                ServiceModel::searchWhere($query, ['name']);
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when(!$all, function ($query) use ($links) {
                $query->whereIn('id', array_keys($links));
            })
            ->where('status', ServiceModel::STATUS_ALLOW)
            ->page();
        foreach ($page as $item) {
            $item['status'] = isset($links[$item['id']])
                ? intval($links[$item['id']]['status']) : -1;
        }
        return $page;
    }

    public static function orderList(string $keywords = '', int $status = 0) {
        return OrderModel::query()->with('service')
            ->where('waiter_id', auth()->id())
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $serviceId = ServiceModel::searchWhere(ServiceModel::query(), ['name'])
                    ->where('status', ServiceModel::STATUS_ALLOW)
                    ->pluck('id');
                if (empty($serviceId)) {
                    return $query->isEmpty();
                }
                $query->whereIn('service_id', $serviceId);
            })
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', '>=', OrderModel::STATUS_UN_PAY);
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function taking($id) {
        $order = OrderModel::where('waiter_id', 0)
            ->where('status', OrderModel::STATUS_PAID_UN_TAKING)
            ->where('id', $id)
            ->first();
        if (empty($order)) {
            throw new Exception('单已失效');
        }
        if (!ServiceRepository::isAllowWaiter($order->service_id, auth()->id())) {
            throw new Exception('没有接单权限');
        }
        $order->waiter_id = auth()->id();
        if (OrderLogModel::taking($order)) {
            return $order;
        }
        throw new Exception('接单失败');
    }

    public static function taken($id) {
        $order = OrderModel::where('waiter_id', auth()->id())
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

    public static function waitTakingList(string $keywords = '') {
        $query = OrderSimpleModel::query();
        $serviceId = ServiceWaiterModel::where('user_id', auth()->id())
            ->where('status', ServiceWaiterModel::STATUS_ALLOW)
            ->pluck('service_id');
        if (!empty($serviceId) && !empty($keywords)) {
            $serviceId = ServiceModel::searchWhere(ServiceModel::query(), ['name'])
                ->where('status', ServiceModel::STATUS_ALLOW)
                ->whereIn('id', $serviceId)
                ->pluck('id');
        }
        if (empty($serviceId)) {
            $query->isEmpty();
        }
        return $query->with('service')
            ->where('waiter_id', 0)
            ->whereIn('service_id', $serviceId)
            ->where('status', OrderModel::STATUS_PAID_UN_TAKING)
            ->orderBy('id', 'desc')
            ->select([
                'id',
                'user_id',
                'service_id',
                'order_amount',
                'amount',
                'status',
                'pay_at',
                'created_at',
                'updated_at',
            ])
            ->page();
    }
}