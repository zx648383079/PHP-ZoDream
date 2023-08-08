<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\CategoryEntity;

final class CategoryRepository {

    const TYPE_PRODUCT = 0;
    const TYPE_RECIPE = 9;
    const TYPE_STOCK = 4;

    public static function getList(int $store) {
        return CategoryEntity::where('store_id', $store)
            ->where('type', self::TYPE_PRODUCT)->where('parent_id', 0)
            ->get();
    }

    public static function merchantList(int $type) {
        return CategoryEntity::where('store_id', StoreRepository::own())
            ->where('type', $type)
            ->where('parent_id', 0)
            ->get();
    }

    public static function merchantSave(int $type, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $storeId = StoreRepository::own();
        $model = $id > 0 ? CategoryEntity::where('id', $id)
            ->where('store_id', $storeId)->first() : new CategoryEntity();
        if (empty($model)) {
            throw new Exception('error');
        }
        $model->load($data);
        $model->type = $type;
        $model->store_id = $storeId;
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function merchantRemove(int $type, int $id) {
        CategoryEntity::where('id', $id)
            ->where('store_id', StoreRepository::own())->delete();
    }
}