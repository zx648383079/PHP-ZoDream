<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\StoreRoleEntity;
use Module\Catering\Domain\Models\StoreStaffModel;

final class StaffRepository {
    public static function merchantList(string $keywords, int $role = 0) {
        return StoreStaffModel::with('user')
            ->where('store_id', StoreRepository::own())
            ->when($role > 0, function ($query) use ($role) {
                $query->where('role_id', $role);
            })
            ->orderBy('user_id', 'desc')
            ->page();
    }

    public static function merchantRoleList() {
        return StoreRoleEntity::query()->where('store_id', StoreRepository::own())->get();
    }

    public static function merchantRoleSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $storeId = StoreRepository::own();
        $model = $id > 0 ? StoreRoleEntity::where('id', $id)
            ->where('store_id', $storeId)->first() : new StoreRoleEntity();
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

    public static function merchantRoleRemove(int $id) {
        StoreRoleEntity::query()->where('store_id', StoreRepository::own())->where('id', $id)
            ->delete();
        StoreStaffModel::where('store_id', StoreRepository::own())
            ->where('role_id', $id)
            ->update([
                'role_id' => 0,
            ]);
    }
}