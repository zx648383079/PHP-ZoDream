<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\StoreEntity;
use Module\Catering\Domain\Entities\StoreStaffEntity;
use Module\Catering\Domain\Models\StoreMetaModel;
use Module\Catering\Domain\Models\StoreModel;

final class StoreRepository {

    public static function manageGetList(string $keywords = '', int $user = 0) {
        return StoreModel::with('user')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], true, '', $keywords);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function manageGet(int $id) {
        $model = StoreModel::with('user')->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('note error');
        }
        return $model;
    }

    public static function manageSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? StoreModel::findOrThrow($id) : new StoreModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function manageRemove(int $id) {
        StoreModel::where('id', $id)->delete();
    }

    public static function profile(): array {
        if (auth()->guest()) {
            return [];
        }
        $userId = auth()->id();
        $has_store = StoreEntity::where('user_id', $userId)
            ->count() > 0;
        $is_waiter = $has_store || StoreStaffEntity::where('user_id', $userId)->count() > 0;
        return compact('has_store', 'is_waiter');
    }

    public static function merchantGet() {
        $model = StoreEntity::where('user_id', auth()->id())->first();
        if (empty($model)) {
            throw new Exception('store is error');
        }
        $data = $model->toArray();
        $data = array_merge($data, StoreMetaModel::getOrDefault($model->id));
        return $data;
    }

    public static function merchantSave(array $data) {
        $model = StoreEntity::where('user_id', auth()->id())->first();
        if (empty($model)) {
            throw new Exception('store is error');
        }
        $model->load($data);
        $model->save();
        StoreMetaModel::saveBatch($model->id, $data);
        return $model;
    }

    public static function own(): int {
        static $store = -1;
        if ($store >= 0) {
            return $store;
        }
        return $store = intval(StoreEntity::where('user_id', auth()->id())
            ->value('id'));
    }
}