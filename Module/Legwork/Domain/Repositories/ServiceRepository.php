<?php
declare(strict_types=1);
namespace Module\Legwork\Domain\Repositories;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Legwork\Domain\Model\ServiceModel;
use Module\Legwork\Domain\Model\ServiceWaiterModel;

class ServiceRepository {
    public static function getList(string $keywords = '', int $cat_id = 0, int $user_id = 0, int $status = 0) {
        return ServiceModel::query()->when(!empty($keywords), function ($query) {
            ServiceModel::searchWhere($query, ['name']);
        })->when($user_id > 0, function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            $query->where('cat_id', $cat_id);
        })->when($status > 0, function ($query) {
            $query->where('status', ServiceModel::STATUS_ALLOW);
        })->page();
    }

    public static function getSelfList(string $keywords = '') {
        return ServiceModel::query()->when(!empty($keywords), function ($query) {
            ServiceModel::searchWhere($query, ['name']);
        })->where('user_id', auth()->id())->page();
    }

    public static function get(int $id) {
        return ServiceModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf(int $id) {
        $model = ServiceModel::where('id', $id)->where('user_id', auth()->id())->first();
        if (empty($model)) {
            throw new \Exception('无权限操作');
        }
        return $model;
    }

    public static function getPublic(int $id) {
        $model = ServiceModel::where('id', $id)
            ->where('status', ServiceModel::STATUS_ALLOW)->first();
        if (empty($model)) {
            throw new \Exception('无权限操作');
        }
        return $model;
    }

    public static function change(int $id, int $status) {
        $model = self::get($id);
        $model->status = $status;
        $model->save();
        return $model;
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new ServiceModel();
        $model->load($data);
        $model->user_id = auth()->id();
        $model->status = 0;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        ServiceModel::where('id', $id)->delete();
    }

    public static function removeSelf(int $id) {
        ServiceModel::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public static function isAllowWaiter(int $id, int $user_id): bool {
        return ServiceWaiterModel::where('user_id', $user_id)
                ->where('service_id', $id)
                ->where('status', ServiceWaiterModel::STATUS_ALLOW)
                ->count() > 0;
    }

    public static function waiterList(int $id, string $keywords = '', int $status = 0) {
        $userId = ServiceWaiterModel::where('service_id', $id)
            ->when($status > 0, function ($query) {
                $query->where('status', 1);
            })->pluck('user_id');
        return UserSimpleModel::query()->when(!empty($keywords), function ($query) {
            UserSimpleModel::searchWhere($query, ['name']);
        })->whereIn('id', $userId)->page();
    }

    public static function waiterChange(int $id, int|array $user_id, int $status = 0) {
        if (!ProviderRepository::hasService($id)) {
            throw new \Exception('服务错误');
        }
        ServiceWaiterModel::where('service_id', $id)
            ->whereIn('user_id', (array)$user_id)->update([
                'status' => $status
            ]);
    }

    public static function waiterAdd(int $id, int|array $user_id, int $status = 0) {
        if (!ProviderRepository::hasService($id)) {
            throw new \Exception('服务错误');
        }
        $exist = ServiceWaiterModel::where('service_id', $id)
            ->pluck('user_id');
        $items = (array)$user_id;
        $add = array_diff($items, $exist);
        $update = array_diff($exist, $items);
        if (!empty($add)) {
            ServiceWaiterModel::query()->insert(array_map(function ($user_id) use ($id, $status) {
                return [
                    'user_id' => $user_id,
                    'service_id' => $id,
                    'status' => $status,
                ];
            }, $add));
        }
        if (!empty($update)) {
            ServiceWaiterModel::whereIn('user_id', $update)
                ->whereIn('service_id', $id)
                ->update([
                    'status' => $status
                ]);
        }
    }

    public static function waiterRemove(int $id, int|array $user_id) {
        if (!ProviderRepository::hasService($id)) {
            throw new \Exception('服务错误');
        }
        ServiceWaiterModel::where('service_id', $id)
            ->whereIn('user_id', (array)$user_id)->delete();
    }
}