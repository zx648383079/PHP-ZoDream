<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\StorePatronEntity;
use Module\Catering\Domain\Entities\StorePatronGroupEntity;
use Module\Catering\Domain\Models\StorePatronModel;

final class PatronRepository {

    public static function merchantList(string $keywords = '', int $group = 0) {
        return StorePatronModel::with('user')
            ->where('store_id', StoreRepository::own())
            ->when($group > 0, function ($query) use ($group) {
                $query->where('group_id', $group);
            })
            ->orderBy('user_id', 'desc')
            ->page();
    }

    public static function merchantGroupList() {
        return StorePatronGroupEntity::query()->where('store_id', StoreRepository::own())->get();
    }

    public static function merchantGroupSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $storeId = StoreRepository::own();
        $model = $id > 0 ? StorePatronGroupEntity::where('id', $id)
            ->where('store_id', $storeId)->first() : new StorePatronGroupEntity();
        if (empty($model)) {
            throw new Exception('error');
        }
        $model->load($data);
        $model->store_id = $storeId;
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function merchantGroupRemove(int $id) {
        StorePatronGroupEntity::query()->where('store_id', StoreRepository::own())
            ->where('id', $id)->delete();
        StorePatronEntity::where('store_id', StoreRepository::own())
            ->where('group_id', $id)->update([
                'group_id' => 0,
            ]);
    }
}