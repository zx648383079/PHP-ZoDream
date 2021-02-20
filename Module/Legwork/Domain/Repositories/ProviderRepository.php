<?php
declare(strict_types=1);
namespace Module\Legwork\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Legwork\Domain\Model\CategoryModel;
use Module\Legwork\Domain\Model\CategoryProviderModel;
use Module\Legwork\Domain\Model\OrderLogModel;
use Module\Legwork\Domain\Model\OrderModel;
use Module\Legwork\Domain\Model\OrderSimpleModel;
use Module\Legwork\Domain\Model\ProviderModel;
use Module\Legwork\Domain\Model\ServiceModel;

class ProviderRepository {
    public static function getList(string $keywords = '') {
        return ProviderModel::query()->with('categories')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return ProviderModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf() {
        $model = ProviderModel::with('categories')->where('user_id', auth()->id())->first();
        if (!empty($model)) {
            return $model;
        }
        return new ProviderModel([
            'id' => auth()->id()
        ]);
    }

    public static function applyCategory(int|array $id) {
        $exist = CategoryProviderModel::where('user_id', auth()->id())
            ->pluck('cat_id');
        $items = (array)$id;
        $add = array_diff($items, $exist);
        $update = array_diff($exist, $items);
        if (!empty($add)) {
            CategoryProviderModel::query()->insert(array_map(function ($cat_id) {
                return [
                    'user_id' => auth()->id(),
                    'cat_id' => $cat_id,
                    'status' => 0,
                ];
            }, $add));
        }
        if (!empty($update)) {
            CategoryProviderModel::where('user_id', auth()->id())
                ->whereIn('cat_id', $update)
                ->where('status', '<>', CategoryProviderModel::STATUS_DISALLOW)
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

    public static function changeCategory(int $id, int|array $category, int $status) {
        foreach ((array)$category as $cat_id) {
            CategoryProviderModel::where('user_id', $id)
                ->where('cat_id', $cat_id)->update([
                    'status' => $status
                ]);
        }
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
        if (isset($data['categories'])) {
            static::applyCategory(array_map(function ($item) {
                return is_array($item) ? $item['id'] : $item;
            }, $data['categories']));
        }
        return $model;
    }

    public static function remove(int $id) {
        ProviderModel::where('id', $id)->delete();
    }

    public static function orderList(string $keywords = '', int $status = 0, int $id = 0, int $user_id = 0, int $waiter_id = 0) {
        return OrderSimpleModel::query()->with('service', 'user', 'waiter')
            ->where('provider_id', auth()->id())
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', '>=', OrderModel::STATUS_UN_PAY);
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $serviceId = SearchModel::searchWhere(ServiceModel::query(), ['name'])
                    ->where('status', ServiceModel::STATUS_ALLOW)
                    ->pluck('id');
                if (empty($serviceId)) {
                    return $query->isEmpty();
                }
                $query->whereIn('service_id', $serviceId);
            })
            ->when($id > 0, function ($query) use ($id) {
                $query->where('id', $id);
            })->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->when($waiter_id > 0, function ($query) use ($waiter_id) {
                $query->where('waiter_id', $waiter_id);
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function order(int $id) {
        $model = OrderModel::query()->with('service')
            ->where('id', $id)
            ->where('provider_id', auth()->id())->first();
        if (empty($model)) {
            throw new \Exception('订单不存在');
        }
        return $model;
    }

    public static function assignOrder(int $id, int $waiter_id) {
        /** @var OrderModel $model */
        $model = OrderModel::query()
            ->where('id', $id)->where('provider_id', auth()->id())->first();
        if (empty($model)) {
            throw new \Exception('订单不存在');
        }
        if (WaiterRepository::isWaiter($waiter_id)) {
            throw new \Exception('不能指定此用户');
        }
        if (!ServiceRepository::isAllowWaiter($model->service_id, $waiter_id)) {
            throw new \Exception('不能指定此用户');
        }
        if ($model->status !== OrderModel::STATUS_PAID_UN_TAKING) {
            throw new \Exception('订单状态错误');
        }
        $model->waiter_id = $waiter_id;
        if (OrderLogModel::taking($model)) {
            return $model;
        }
        throw new Exception('接单失败');
    }

    public static function hasService(int $id): bool {
        return ServiceModel::where('id', $id)->where('user_id', auth()->id())
            ->count() > 0;
    }

    public static function categoryList(
        string $keywords = '', int $category = 0, int $status = 0, bool $all = false, $user_id = 0) {
        if (empty($user_id)) {
            $user_id = auth()->id();
        }
        $links = CategoryProviderModel::query()
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })
            ->when($status > 0, function ($query) {
                $query->where('status', 1);
            })
            ->where('user_id', $user_id)
            ->asArray()
            ->pluck(null, 'cat_id');
        $page = CategoryModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
            })->when(!$all, function ($query) use ($links) {
                $query->whereIn('id', array_keys($links));
            })
            ->page();
        foreach ($page as $item) {
            $item['status'] = isset($links[$item['id']])
                ? intval($links[$item['id']]['status']) : -1;
        }
        return $page;
    }
}